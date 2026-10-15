<?php

use App\Core\Database;
use Database\Seeders\ArticleSeeder;
use Database\Seeders\CategorySeeder;

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/app.php';

$pdo = Database::connect(...$config['db']);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
foreach (['article_category', 'articles', 'categories'] as $table) {
    $pdo->exec("TRUNCATE TABLE {$table}");
}
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$pdo->beginTransaction();

$categoryIds = (new CategorySeeder($pdo))->run();
(new ArticleSeeder($pdo))->run($categoryIds);

$pdo->commit();

echo 'Записи добавлены';
