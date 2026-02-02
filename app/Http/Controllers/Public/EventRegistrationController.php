<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Models\EventParticipant;
use App\Models\AttendanceQrToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventRegistrationController extends Controller
{
    
    /**
     * Tampilkan form registrasi untuk public
     */
    /**
     * Cek apakah peserta sudah terdaftar
     */
    public function checkParticipant(Request $request, Event $event)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;
        $type = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $participant = Participant::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (!$participant) {
            return response()->json([
                'status' => 'new',
                'type' => $type,
                'value' => $identifier
            ]);
        }

        // Cek apakah sudah terdaftar di event ini
        $isRegistered = EventParticipant::where('event_id', $event->id)
            ->where('participant_id', $participant->id)
            ->exists();

        if ($isRegistered) {
            $eventParticipant = EventParticipant::where('event_id', $event->id)
                ->where('participant_id', $participant->id)
                ->first();
            
            $qrToken = AttendanceQrToken::where('event_participant_id', $eventParticipant->id)->first();
            
            return response()->json([
                'status' => 'registered',
                'participant' => $participant,
                'redirect_url' => $qrToken ? route('event.ticket', ['event' => $event->slug, 'qrToken' => $qrToken->token]) : null
            ]);
        }

        return response()->json([
            'status' => 'exists',
            'participant' => $participant,
            'type' => $type,
            'value' => $identifier
        ]);
    }

    /**
     * Tampilkan form registrasi untuk public
     */
    public function show(Event $event)
    {
        $participantTypes = $event->participantTypes()->orderBy('name')->get();

        return view('pages.events.register', compact('event', 'participantTypes'));
    }

    /**
     * Proses registrasi public
     */
    public function store(Request $request, Event $event)
    {
        if ($event->status !== 'active') {
            abort(404); // Atau redirect error
        }

        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|max:255',
            'phone'               => 'required|string|max:20',
            'participant_type_id' => 'required|exists:participant_types,id',
        ]);

        $tokenUuid = null;

        DB::transaction(function () use ($event, $validated, &$tokenUuid) {
            // 1. Cari/Buat Participant (Proteksi Duplikat Email/Phone)
            $participant = Participant::where('email', $validated['email'])
                ->orWhere('phone', $validated['phone'])
                ->first();

            if ($participant) {
                // Update data jika sudah ada (opsional, tergantung policy bisnis)
                $participant->update([
                    'name'  => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ]);
            } else {
                $participant = Participant::create([
                    'name'  => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ]);
            }

            // 2. Daftarkan di EventParticipant
            $eventParticipant = EventParticipant::updateOrCreate(
                [
                    'event_id'       => $event->id,
                    'participant_id' => $participant->id,
                ],
                [
                    'participant_type_id' => $validated['participant_type_id'],
                    'registered_at'       => now(),
                    'registered_via'      => 'self',
                ]
            );

            // 3. Generate QR Token
            // Cek dulu apakah tokennnya sudah ada (kalau dia refresh halaman atau daftar ulang)
            $qrToken = AttendanceQrToken::where('event_participant_id', $eventParticipant->id)->first();

            if (!$qrToken) {
                $qrToken = AttendanceQrToken::create([
                    'event_participant_id' => $eventParticipant->id,
                    'token'                => (string) Str::uuid(),
                    'expired_at'           => $event->end_date ? \Carbon\Carbon::parse($event->end_date)->endOfDay() : null,
                ]);
            }
            
            $tokenUuid = $qrToken->token;
        });

        // Redirect ke halaman tiket
        return redirect()->route('event.ticket', ['event' => $event->slug, 'qrToken' => $tokenUuid])
                         ->with('success', 'Registrasi Berhasil! Simpan QR Code ini.');
    }

    /**
     * Tampilkan Tiket / QR Code
     */
    public function ticket(Event $event, AttendanceQrToken $qrToken)
    {
        // Pastikan token ini milik event yang benar
        if ($qrToken->eventParticipant->event_id != $event->id) {
            abort(404);
        }

        $idCardTemplate = \App\Models\IdCardTemplate::where('is_active', true)->first();

        return view('pages.events.ticket', compact('event', 'qrToken', 'idCardTemplate'));
    }

    /**
     * Halaman ID Card yang menampilkan gambar hasil generate
     */
    public function idCard(Event $event, AttendanceQrToken $qrToken)
    {
        if ($qrToken->eventParticipant->event_id != $event->id) {
            abort(404);
        }

        $token = $qrToken->token;
        $fileName = "card_{$event->id}_{$token}.png";
        $filePath = "assets/images/generated-card/{$event->id}/{$fileName}";
        $isGenerated = file_exists(public_path($filePath));

        return view('pages.events.id-card', compact('event', 'qrToken', 'isGenerated', 'filePath'));
    }

    /**
     * Public generate ID Card
     */
    public function generateIdCard(Event $event, AttendanceQrToken $qrToken, \App\Services\IdCardService $idCardService)
    {
        if ($qrToken->eventParticipant->event_id != $event->id) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid untuk event ini'], 403);
        }

        try {
            $idCardService->generateAndSave($qrToken->eventParticipant);
            return response()->json(['success' => true, 'message' => 'Berhasil generate idcard']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Cari tiket peserta berdasarkan email/phone
     */
    public function checkTickets(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;

        $participant = Participant::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (!$participant) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Email atau Nomor HP belum terdaftar di sistem kami.'
            ], 404);
        }

        // Ambil event yang diikuti peserta
        // Syarat: Event Active & sedang berlangsung (now >= start_date dan now <= end_date)
        $registrations = EventParticipant::with(['event', 'qrToken'])
            ->where('participant_id', $participant->id)
            ->whereHas('event', function($q) {
                $now = now();
                $q->where('status', 'active')
                  ->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now);
            })
            ->get()
            ->map(function($reg) {
                return [
                    'event_name' => $reg->event->name,
                    'event_date' => $reg->event->start_date->translatedFormat('d M Y, H:i'),
                    'ticket_url' => $reg->qrToken ? route('event.ticket', ['event' => $reg->event->slug, 'qrToken' => $reg->qrToken->token]) : null
                ];
            });

        if ($registrations->isEmpty()) {
            return response()->json([
                'status'  => 'empty',
                'message' => 'Anda belum terdaftar di event aktif yang akan datang.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'participant' => $participant->name,
            'data' => $registrations
        ]);
    }
}
