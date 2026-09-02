<?php

namespace App\Jobs;

use App\Models\EventParticipant;
use App\Services\IdCardService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class GenerateIdCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventParticipant;

    /**
     * Create a new job instance.
     */
    public function __construct(EventParticipant $eventParticipant)
    {
        $this->eventParticipant = $eventParticipant;
    }

    /**
     * Execute the job.
     */
    public function handle(IdCardService $idCardService): void
    {
        if ($this->eventParticipant->qrToken) {
            $idCardService->generateAndSave($this->eventParticipant);
        }
    }
}
