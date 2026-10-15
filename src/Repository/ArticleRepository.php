<?php

namespace App\Repository;

use App\Entity\Article;
use App\Support\Sorting;
use DateTimeImmutable;
use PDO;

final class ArticleRepository
{
    private const COLUMNS = 'a.id, a.slug, a.title, a.description, a.content, a.image, a.views, a.published_at';

    public function __construct(private readonly PDO $pdo) {}

    public function findLatestPerCategory(int $limit)
    {
        $stmt = $this->pdo->prepare(
            'SELECT ranked.*
             FROM (
                 SELECT ac.category_id, ' . self::COLUMNS . ',
                        ROW_NUMBER() OVER (
                            PARTITION BY ac.category_id
                            ORDER BY a.published_at DESC, a.id DESC
                        ) AS row_num
                 FROM article_category ac
                 JOIN articles a ON a.id = ac.article_id
             ) ranked
             WHERE ranked.row_num <= :limit
             ORDER BY ranked.category_id, ranked.row_num'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];

        foreach ($stmt->fetchAll() as $row) {
            $result[$row['category_id']][] = $this->hydrate($row);
        }

        return $result;
    }

    public function countByCategory(int $categoryId)
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM article_category WHERE category_id = ?');
        $stmt->execute([$categoryId]);

        return (int) $stmt->fetchColumn();
    }

    public function findByCategory(int $categoryId, Sorting $sort, int $limit, int $offset)
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNS . '
             FROM articles a
             JOIN article_category ac ON ac.article_id = a.id
             WHERE ac.category_id = :category
             ORDER BY a.' . $sort->column . ' ' . $sort->direction . ', a.id ' . $sort->direction . '
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue('category', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return array_map($this->hydrate(...), $stmt->fetchAll());
    }

    public function findBySlug(string $slug)
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNS . ' FROM articles a WHERE a.slug = ?');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();

        return $row === false ? null : $this->hydrate($row);
    }

    public function incrementViews(string $slug)
    {
        $stmt = $this->pdo->prepare('UPDATE articles SET views = views + 1 WHERE slug = ?');
        $stmt->execute([$slug]);

        return $stmt->rowCount() > 0;
    }

    public function findSimilar(int $articleId, int $limit)
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNS . ', COUNT(*) AS shared_categories
             FROM article_category ac
             JOIN articles a ON a.id = ac.article_id
             WHERE ac.category_id IN (SELECT category_id FROM article_category WHERE article_id = :article)
               AND ac.article_id <> :exclude
             GROUP BY a.id
             ORDER BY shared_categories DESC, a.published_at DESC, a.id DESC
             LIMIT :limit'
        );
        $stmt->bindValue('article', $articleId, PDO::PARAM_INT);
        $stmt->bindValue('exclude', $articleId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return array_map($this->hydrate(...), $stmt->fetchAll());
    }

    private function hydrate(array $row)
    {
        return new Article(
            id: $row['id'],
            slug: $row['slug'],
            title: $row['title'],
            description: $row['description'],
            content: $row['content'],
            image: $row['image'],
            views: (int) $row['views'],
            publishedAt: new DateTimeImmutable($row['published_at']),
        );
    }
}
