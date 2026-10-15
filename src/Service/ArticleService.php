<?php

namespace App\Service;

use App\Dto\ArticlePage;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

final class ArticleService
{
    public function __construct(
        private readonly ArticleRepository $articles,
        private readonly CategoryRepository $categories,
        private readonly int $similarLimit,
    ) {}

    public function getForView(string $slug)
    {
        if (!$this->articles->incrementViews($slug)) {
            return null;
        }

        $article = $this->articles->findBySlug($slug);

        if ($article === null) {
            return null;
        }

        return new ArticlePage(
            $article,
            $this->categories->findByArticleId($article->id),
            $this->articles->findSimilar($article->id, $this->similarLimit),
        );
    }
}
