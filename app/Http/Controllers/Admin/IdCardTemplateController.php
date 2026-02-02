<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\IdCardTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class IdCardTemplateController extends Controller
{
    public function show(Event $event)
    {
        Gate::authorize('update', $event);
        
        $template = $event->idCardTemplate;
        return view('admin.events.id_card_template', compact('event', 'template'));
    }

    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        $request->validate([
            'id_card_template' => 'required|image|mimes:png|max:3072',
        ]);

        if ($request->hasFile('id_card_template')) {
            $file = $request->file('id_card_template');
            $filename = 'id_card_template_' . time() . '.png';
            
            // folder: public/assets/images/templates/{event_id}/
            $destinationPath = public_path('assets/images/templates/' . $event->id);
            
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Optional: Delete old file if exists
            if ($event->idCardTemplate) {
                $oldPath = $destinationPath . '/' . $event->idCardTemplate->file_path;
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file->move($destinationPath, $filename);

            IdCardTemplate::updateOrCreate(
                ['event_id' => $event->id],
                ['file_path' => $filename, 'is_active' => true]
            );

            return back()->with('success', 'Template ID Card berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal mengunggah template.');
    }
}
