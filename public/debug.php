<?php
$lines = file('storage/logs/laravel.log');
$startPos = max(0, count($lines) - 50);
for ($i = $startPos; $i < count($lines); $i++) {
    echo $lines[$i];
}
