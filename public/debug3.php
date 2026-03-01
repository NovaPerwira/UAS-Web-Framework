<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Boot app
$app->boot();

// Create pseudo request
$request = Illuminate\Http\Request::create('/agreements', 'GET');

try {
    // login as user 1
    auth()->loginUsingId(1);

    // hit route
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() !== 200) {
        echo strip_tags($response->getContent());
    } else {
        echo "OK! Rendered successfully. Length: " . strlen($response->getContent());
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine();
}
