<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = App\Models\User::first();
    if ($user) {
        auth()->login($user);
    }

    $agreements = App\Models\Agreement::with(['invoice', 'creator'])->latest()->paginate(10);
    $view = view('agreements.index', compact('agreements'))->render();
    echo "OK!";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
