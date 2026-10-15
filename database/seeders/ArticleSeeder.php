<?php

declare(strict_types=1);

namespace Database\Seeders;

use DateTimeImmutable;
use PDO;

final class ArticleSeeder
{
    private const TOPICS = [
        ['Строгая типизация в PHP', 'php-strict-types', 'php'],
        ['Readonly-свойства', 'php-readonly', 'php'],
        ['Composer', 'composer', 'php'],
        ['Индексы в MySQL', 'mysql-indexes', 'databases'],
        ['Оконные функции SQL', 'sql-window-functions', 'databases'],
        ['Транзакции', 'transactions', 'databases'],
        ['Docker Compose', 'docker-compose', 'devops'],
        ['Nginx и PHP-FPM', 'nginx-php-fpm', 'devops'],
        ['SCSS', 'scss', 'frontend'],
        ['CSS Grid', 'css-grid', 'frontend'],
        ['Паттерн Repository', 'repository-pattern', 'architecture'],
        ['Front controller', 'front-controller', 'architecture'],
        ['Код-ревью', 'code-review', 'career'],
        ['Тестовое задание', 'test-assignment', 'career'],
    ];

    private const TITLE_TEMPLATES = [
        ['%s: с чего начать', 'getting-started'],
        ['%s на практике', 'in-practice'],
        ['Типичные ошибки: %s', 'common-mistakes'],
    ];

    private const DESCRIPTIONS = [
        'Коротко о главном: что такое «%s» и когда это нужно.',
        'Разбираем тему «%s» на примерах из реальных проектов.',
        'Практические советы по теме «%s» без лишней теории.',
    ];

    private const PARAGRAPHS = [
        'Начинать стоит с простого рабочего варианта. Оптимизировать и обобщать код имеет смысл только тогда, когда появилась реальная потребность.',
        'Хорошее решение легко объяснить коллеге за пару минут. Если для объяснения нужна схема на всю доску, скорее всего, решение можно упростить.',
        'Большинство ошибок появляется на границах системы: во входных данных, в запросах к базе и в ответах внешних сервисов. Именно там проверки нужнее всего.',
        'Читаемость кода важнее его краткости. Через полгода этот код будет читать человек, который не помнит контекст, и этим человеком можете оказаться вы.',
        'Перед тем как добавить зависимость, стоит спросить себя, какую задачу она решает и сколько будет стоить её поддержка в будущем.',
        'Автоматизация рутинных действий окупается быстро: команда, которую запускают каждый день, должна выполняться одной строкой.',
        'Измерения важнее догадок. Прежде чем что-то ускорять, нужно найти, где на самом деле теряется время.',
        'Документация в README экономит часы: как запустить проект, как его проверить и какие решения были приняты осознанно.',
    ];

    private const IMAGES_COUNT = 6;

    private const SAME_VIEWS = 1000;

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function run(array $categoryIds)
    {
        mt_srand(42);

        $insertArticle = $this->pdo->prepare(
            'INSERT INTO articles (slug, title, description, content, image, views, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $attachCategory = $this->pdo->prepare(
            'INSERT INTO article_category (article_id, category_id) VALUES (?, ?)'
        );

        $topicCategories = array_values(array_unique(array_column(self::TOPICS, 2)));
        $number = 0;

        foreach (self::TITLE_TEMPLATES as [$titleTemplate, $slugSuffix]) {
            foreach (self::TOPICS as [$topic, $topicSlug, $mainCategory]) {
                $number++;

                $insertArticle->execute([
                    $topicSlug . '-' . $slugSuffix,
                    sprintf($titleTemplate, $topic),
                    sprintf($this->pick(self::DESCRIPTIONS), $topic),
                    $this->content($topic),
                    sprintf('/assets/img/placeholders/%d.jpg', $number % self::IMAGES_COUNT + 1),
                    $number % 7 === 0 ? self::SAME_VIEWS : mt_rand(0, 5000),
                    $this->publishedAt(),
                ]);

                $articleId = (int) $this->pdo->lastInsertId();

                foreach ($this->categoriesFor($mainCategory, $topicCategories) as $slug) {
                    $attachCategory->execute([$articleId, $categoryIds[$slug]]);
                }
            }
        }
    }

    private function categoriesFor(string $mainCategory, array $allCategories)
    {
        $others = array_values(array_diff($allCategories, [$mainCategory]));
        $categories = [$mainCategory];

        for ($i = mt_rand(0, 2); $i > 0; $i--) {
            $categories[] = $this->pick($others);
        }

        return array_values(array_unique($categories));
    }

    private function content(string $topic)
    {
        $paragraphs = [
            sprintf('В этой статье разбираем тему «%s»: зачем она нужна и как применить её в своём проекте.', $topic),
        ];

        foreach (array_rand(self::PARAGRAPHS, mt_rand(3, 5)) as $key) {
            $paragraphs[] = self::PARAGRAPHS[$key];
        }

        return implode("\n\n", $paragraphs);
    }

    private function publishedAt()
    {
        return (new DateTimeImmutable('2026-01-01 09:00:00'))
            ->modify(sprintf('+%d days +%d hours', mt_rand(0, 270), mt_rand(0, 10)))
            ->format('Y-m-d H:i:s');
    }

    private function pick(array $items)
    {
        return $items[mt_rand(0, count($items) - 1)];
    }
}
