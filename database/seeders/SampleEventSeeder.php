<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Participant;
use App\Models\EventParticipant;
use App\Models\AttendanceQrToken;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleEventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Sample Event
        $event = Event::updateOrCreate(
            ['slug' => 'demo-event-presensi'],
            [
                'name'       => 'Demo Event Presensi QR',
                'start_date' => now(),
                'end_date'   => now()->addDays(2),
                'location'   => 'Gedung Serbaguna Digital',
                'status'     => 'active',
            ]
        );

        // 2. Create Sample Participants
        $participants = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.test', 'phone' => '081234567891'],
            ['name' => 'Ani Wijaya', 'email' => 'ani@example.test', 'phone' => '081234567892'],
            ['name' => 'Citra Lestari', 'email' => 'citra@example.test', 'phone' => '081234567893'],
        ];

        foreach ($participants as $pData) {
            $participant = Participant::updateOrCreate(
                ['email' => $pData['email']],
                $pData
            );

            // 3. Link to Event
            $ep = EventParticipant::updateOrCreate(
                [
                    'event_id'       => $event->id,
                    'participant_id' => $participant->id,
                ]
            );

            // 4. Generate QR Token (Must be UUID and match column names)
            AttendanceQrToken::updateOrCreate(
                ['event_participant_id' => $ep->id],
                [
                    'token'      => Str::uuid(),
                    'expired_at' => $event->end_date,
                ]
            );
        }
    }
}
