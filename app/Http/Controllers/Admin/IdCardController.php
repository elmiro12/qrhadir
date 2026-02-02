<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Services\IdCardService;
use App\Models\AttendanceQrToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class IdCardController extends Controller
{
    protected $idCardService;

    public function __construct(IdCardService $idCardService)
    {
        $this->idCardService = $idCardService;
    }

    public function getParticipants(Event $event)
    {
        $participants = $event->participants()
            ->with(['participant', 'qrToken'])
            ->get()
            ->map(function ($ep) {
                return [
                    'name' => $ep->participant->name,
                    'token' => $ep->qrToken ? $ep->qrToken->token : null,
                ];
            });

        return response()->json($participants);
    }

    public function downloadBatch(Event $event)
    {
        $participants = $event->participants()->with(['participant', 'qrToken'])->get();
        $directory = public_path('assets/images/generated-card/' . $event->id);
        
        $files = [];
        $missing = [];

        foreach ($participants as $ep) {
            if (!$ep->qrToken) {
                $missing[] = $ep->participant->name;
                continue;
            }

            $fileName = "card_{$event->id}_{$ep->qrToken->token}.png";
            $filePath = $directory . '/' . $fileName;

            if (File::exists($filePath)) {
                $files[] = [
                    'path' => $filePath,
                    'name' => Str::slug($ep->participant->name) . '.png'
                ];
            } else {
                $missing[] = $ep->participant->name;
            }
        }

        if (count($files) === 0) {
            return back()->with('error', 'Belum ada id-card yang digenerate untuk event ini.');
        }

        if (count($missing) > 0) {
            return back()->with('error', 'Ada peserta yang belum digenerate id-card: ' . implode(', ', $missing));
        }

        $zipName = "ID_Cards_{$event->slug}_" . time() . ".zip";
        $zipPath = storage_path('app/public/' . $zipName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file['path'], $file['name']);
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function generateSingle(Event $event, AttendanceQrToken $qrToken)
    {
        $ep = $qrToken->eventParticipant;
        
        if ($ep->event_id != $event->id) {
            abort(404);
        }

        try {
            $this->idCardService->generateAndSave($ep);

            return response()->json(['success' => true, 'message' => 'Berhasil generate idcard']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
