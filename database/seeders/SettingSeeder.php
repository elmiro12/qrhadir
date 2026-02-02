<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'app_name'        => env('APP_NAME', 'absensiQR'),
            'app_logo'        => 'logo.png',
            'app_favicon'     => 'favicon.ico',
            'app_description' => 'Sistem Presensi Event Berbasis QR Code Terintegrasi',
            'footer_text'     => '© ' . date('Y') . ' ' . env('APP_NAME', 'absensiQR'),
            'timezone'        => env('APP_TIMEZONE', 'Asia/Jayapura'),
            'contact_email'    => 'support@qrhadir.my.id',
            'contact_whatsapp' => '6281234567890',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
