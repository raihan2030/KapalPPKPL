<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Cek apakah user dengan ID 1 ada
$user = User::find(1);

if (!$user) {
    // Buat user default
    $user = User::create([
        'name' => 'System Inspector',
        'email' => 'inspector@system.local',
        'password' => bcrypt('system123'),
    ]);
    echo "✅ User berhasil dibuat!\n";
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
} else {
    echo "✅ User sudah ada!\n";
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
}
