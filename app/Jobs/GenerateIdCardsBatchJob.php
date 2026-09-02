<?php

namespace App\Jobs;

use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;

class GenerateIdCardsBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $forceRegenerate;

    /**
     * Create a new job instance.
     */
    public function __construct(Event $event, $forceRegenerate = false)
    {
        $this->event = $event;
        $this->forceRegenerate = $forceRegenerate;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $participants = $this->event->participants()->with('qrToken')->get();
        $directory = public_path('assets/images/generated-card/' . $this->event->id);

        foreach ($participants as $ep) {
            if (!$ep->qrToken) continue;

            $fileName = "card_{$this->event->id}_{$ep->qrToken->token}.png";
            $filePath = $directory . '/' . $fileName;

            // Jika file belum ada, atau dipaksa regenerate, dispatch job per peserta
            if ($this->forceRegenerate || !File::exists($filePath)) {
                GenerateIdCardJob::dispatch($ep);
            }
        }
    }
}
