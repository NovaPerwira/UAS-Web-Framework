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

    $invoices = App\Models\Invoice::doesntHave('agreement')->get();
    $templates = App\Models\AgreementTemplate::all();
    $selectedInvoice = null;
    $view = view('agreements.create', compact('invoices', 'templates', 'selectedInvoice'))->render();
    echo "OK create!";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
