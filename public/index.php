<?php

use App\Core\App;

try {
    require __DIR__ . '/../vendor/autoload.php';

    (new App(dirname(__DIR__)))->run();
} catch (Throwable $e) {
    echo 'Ошибка запуска сервера';
}
