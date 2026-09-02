<?php
// Darurat: Script untuk membersihkan cache Laravel di shared hosting
// Akses file ini melalui browser: https://qrhadir.my.id/clearcache.php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h1>Clearing Laravel Caches...</h1>";

try {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    echo "<p>Success! Route, Config, View, and Application caches have been cleared.</p>";
} catch (\Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

echo "<br><p><strong>PENTING:</strong> Segera hapus file ini (clearcache.php) dari server Anda setelah digunakan demi keamanan!</p>";
