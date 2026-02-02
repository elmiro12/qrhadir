<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name'        => 'required|string|max:255',
            'app_description' => 'nullable|string',
            'app_logo'        => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'app_favicon'     => 'nullable|file|mimes:ico,png|max:1024',
            'footer_text'     => 'nullable|string|max:255',
            'timezone'        => 'required|string',
            'contact_email'    => 'nullable|email',
            'contact_whatsapp' => 'nullable|string|max:20',
        ]);

        // Handle Logo Upload
        if ($request->hasFile('app_logo')) {
            $file = $request->file('app_logo');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/logo'), $filename);
            $data['app_logo'] = $filename;
        }

        // Handle Favicon Upload
        if ($request->hasFile('app_favicon')) {
            $file = $request->file('app_favicon');
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path(), $filename); // Favicon usually in root or public
            $data['app_favicon'] = $filename;
        }


        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Setting::clearCache();

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
