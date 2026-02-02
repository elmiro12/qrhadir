<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\ParticipantType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParticipantTypeController extends Controller
{
    public function index(Event $event)
    {
        $types = $event->participantTypes()->latest()->get();
        return view('admin.events.types.index', compact('event', 'types'));
    }

    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'certificate_text' => 'nullable|string',
        ]);

        $event->participantTypes()->create($data);

        return back()->with('success', 'Tipe partisipan berhasil ditambahkan.');
    }

    public function update(Request $request, Event $event, ParticipantType $type)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'certificate_text' => 'nullable|string',
        ]);

        $type->update($data);

        return back()->with('success', 'Tipe partisipan berhasil diperbarui.');
    }

    public function destroy(Event $event, ParticipantType $type)
    {
        // Optional: Check if type is used before deleting
        if ($type->eventParticipants()->exists()) {
            return back()->with('error', 'Tipe tidak dapat dihapus karena sudah digunakan oleh peserta.');
        }

        $type->delete();

        return back()->with('success', 'Tipe partisipan berhasil dihapus.');
    }
}
