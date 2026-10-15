<?php

namespace App\Repository;

use App\Entity\Category;
use PDO;

final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function findAll()
    {
        $rows = $this->pdo->query('SELECT id, slug, name, description FROM categories ORDER BY id')->fetchAll();

        return array_map($this->hydrate(...), $rows);
    }

    public function findBySlug(string $slug)
    {
        $stmt = $this->pdo->prepare('SELECT id, slug, name, description FROM categories WHERE slug = ?');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();

        return $row === false ? null : $this->hydrate($row);
    }

    public function findByArticleId(int $articleId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.id, c.slug, c.name, c.description
             FROM categories c
             JOIN article_category ac ON ac.category_id = c.id
             WHERE ac.article_id = ?
             ORDER BY c.name'
        );
        $stmt->execute([$articleId]);

        return array_map($this->hydrate(...), $stmt->fetchAll());
    }

    private function hydrate(array $row)
    {
        return new Category($row['id'], $row['slug'], $row['name'], $row['description']);
    }
}
