<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceQrToken;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Tampilkan halaman scan QR Public (Kiosk Mode)
     */
    public function scan()
    {
        return view('pages.attendance.scan');
    }

    /**
     * Proses hasil scan code via AJAX/API form submit (Public)
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $tokenStr = $request->token;

        // Cari tokennya
        $qrToken = AttendanceQrToken::with(['eventParticipant.participant', 'eventParticipant.event'])
            ->where('token', $tokenStr)
            ->first();

        // 1. Cek Validitas Token
        if (!$qrToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid/tidak ditemukan.'
            ], 404);
        }
        
        $event = $qrToken->eventParticipant->event;
        $participant = $qrToken->eventParticipant->participant;

        // 2. Cek Event Status
        if ($event->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Event tidak aktif (Draft/Closed).'
            ], 400);
        }

        // 3. Cek Tanggal Event (Range)
        $now = now();
        if ($now->lessThan($event->start_date) || $now->greaterThan($event->end_date)) {
            if ($now->gt($event->end_date)) {
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Event sudah berakhir pada ' . \Carbon\Carbon::parse($event->end_date)->translatedFormat('d F Y H:i')
                ], 400);
            }
            if($now->lt($event->start_date)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event '. $event->name . ' akan dimulai pada ' . \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y H:i')
                ], 400);
            }
        }

        // 4. Cek Expired Token
        if ($qrToken->expired_at && $now->greaterThan($qrToken->expired_at)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token sudah kedaluwarsa.'
            ], 400);
        }

        // 5. Cek Absensi Hari Ini
        $attendedToday = Attendance::where('event_participant_id', $qrToken->event_participant_id)
            ->where('attendance_date', now()->toDateString())
            ->exists();

        if ($attendedToday) {
            return response()->json([
                'status' => 'warning',
                'message' => "Maaf, Peserta $participant->name SUDAH check-in hari ini (" . now()->format('H:i') . ")."
            ], 200);
        }

        // 6. Catat Kehadiran
        DB::transaction(function () use ($qrToken) {
            Attendance::create([
                'event_participant_id' => $qrToken->event_participant_id,
                'attendance_date'      => now()->toDateString(),
                'checkin_time'         => now(),
            ]);
        });
        
        return response()->json([
            'status' => 'success',
            'message' => "Hadir! $participant->name berhasil check-in ($event->name)."
        ], 200);
    }
}
