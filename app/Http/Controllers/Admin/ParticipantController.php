<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participants = Participant::query()
            ->when(auth()->user()->isSuperAdmin(), fn($q) => $q->with('owner'))
            ->orderBy('name')
            ->get();
        //dd($participants);

        return view('admin.master-participants.index', compact('participants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:participants,email',
            'phone' => 'nullable|string|max:20|unique:participants,phone',
        ]);

        Participant::create($validated);

        return back()->with('success', 'Data peserta berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:participants,email,' . $participant->id,
            'phone' => 'nullable|string|max:20|unique:participants,phone,' . $participant->id,
        ]);

        $participant->update($validated);

        return back()->with('success', 'Data peserta berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        // Cek dependencies jika perlu (misal: cek event_participants)
        // Saat ini CascadeOnDelete biasanya diatur di database, tapi hati-hati
        // jika ingin mencegah hapus jika sudah ikut event.
        
        $participant->delete();

        return back()->with('success', 'Data peserta berhasil dihapus');
    }
}
