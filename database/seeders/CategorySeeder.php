<?php

namespace Database\Seeders;

use PDO;

final class CategorySeeder
{
    private const CATEGORIES = [
        ['php', 'PHP', 'Язык, его новые версии и практики написания современного кода.'],
        ['databases', 'Базы данных', 'MySQL, проектирование схем, индексы и оптимизация запросов.'],
        ['devops', 'DevOps', 'Docker, окружение разработки и запуск веб-приложений.'],
        ['frontend', 'Фронтенд', 'Вёрстка, CSS, SCSS и всё, что видит пользователь.'],
        ['architecture', 'Архитектура', 'Слои приложения, паттерны и принципы проектирования.'],
        ['career', 'Карьера', 'Собеседования, код-ревью и рост разработчика.'],
        ['news', 'Новости', 'Анонсы и события из мира разработки.'],
    ];

    public function __construct(private readonly PDO $pdo) {}

    public function run()
    {
        $insert = $this->pdo->prepare(
            'INSERT INTO categories (slug, name, description) VALUES (?, ?, ?)'
        );

        $ids = [];

        foreach (self::CATEGORIES as [$slug, $name, $description]) {
            $insert->execute([$slug, $name, $description]);
            $ids[$slug] = (int) $this->pdo->lastInsertId();
        }

        return $ids;
    }
}
