<?php

use App\Core\Database;

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/app.php';
$pdo = Database::connect(...$config['db']);

$files = glob(__DIR__ . '/../database/migrations/*.sql');

foreach ($files as $file) {
    $pdo->exec(file_get_contents($file));

    echo 'Готово: ' . basename($file);
}
