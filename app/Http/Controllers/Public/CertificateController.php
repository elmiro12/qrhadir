<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Models\EventParticipant;
use App\Services\CertificateGenerator;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Show search form.
     */
    public function index()
    {
        return view('public.certificates.index');
    }

    /**
     * Search and list events for the participant.
     */
    /**
     * Search and list events for the participant (AJAX).
     */
    public function search(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required|string',
        ]);

        $query = $request->email_or_phone;
        
        $participant = Participant::where('email', $query)
            ->orWhere('phone', $query)
            ->first();

        if (!$participant) {
            return response()->json([
                'status' => 404, 
                'message' => 'Peserta tidak ditemukan.'
            ], 404);
        }

        // Get eligible events:
        // 1. Participant registered
        // 2. Has Certificate enabled
        // 3. Event ended or closed
        // 4. Participant has attendance
        $events = EventParticipant::where('participant_id', $participant->id)
            ->whereHas('event', function ($q) {
                $q->where('has_certificate', true)
                  ->where(function($sq) {
                      $sq->where('status', 'closed')
                        ->orWhere('end_date', '<', now());
                  });
            })
            ->whereHas('attendances') // Check if attended at least once
            ->with(['event', 'qrToken'])
            ->get();

        if ($events->isEmpty()) {
            return response()->json([
                'status' => 'empty',
                'message' => 'Anda belum memiliki sertifikat yang tersedia (Event harus selesai & Anda harus hadir).'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'participant' => $participant,
            'events' => $events
        ]);
    }

    /**
     * Show/Preview Certificate.
     */
    public function show(Event $event, $qrToken)
    {
        // Security check: verify token matches
        $tokenRecord = \App\Models\AttendanceQrToken::where('token', $qrToken)->firstOrFail();
        
        if ($tokenRecord->eventParticipant->event_id !== $event->id) {
            abort(404);
        }
        
        $eventParticipant = $tokenRecord->eventParticipant;

        return view('public.certificates.show', compact('event', 'eventParticipant', 'qrToken'));
    }

    /**
     * Download Certificate.
     */
    public function download(CertificateGenerator $generator, Event $event, $qrToken)
    {
        $tokenRecord = \App\Models\AttendanceQrToken::where('token', $qrToken)->firstOrFail();
        if ($tokenRecord->eventParticipant->event_id !== $event->id) {
            abort(404);
        }

        try {
            $image = $generator->generate($tokenRecord->eventParticipant);
            return response()->streamDownload(function() use ($image) {
                echo $image->toJpeg(90);
            }, 'Sertifikat-' . $event->slug . '.jpg');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Certificate Generation Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Gagal generate sertifikat: ' . $e->getMessage());
        }
    }

    /**
     * Submit Report.
     */
    public function report(Request $request, Event $event, $qrToken)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $tokenRecord = \App\Models\AttendanceQrToken::where('token', $qrToken)->firstOrFail();
        
        $tokenRecord->eventParticipant->certificateReports()->create([
            'message' => $request->message,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Laporan kesalahan berhasil dikirim. Admin akan segera meninjau.');
    }
}
