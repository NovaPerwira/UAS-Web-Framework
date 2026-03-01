<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Since we are running from CLI, the session/auth might not work correctly,
// but let's just see if there is any syntactical error or missing class.
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create(
        '/agreements',
        'GET'
    )
);

echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 302) {
    echo strip_tags($response->getContent());
} else if ($response->getStatusCode() === 302) {
    echo "Redirect: " . $response->headers->get('Location');
}
$kernel->terminate($request, $response);
