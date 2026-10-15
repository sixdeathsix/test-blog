<?php

namespace App\Dto;

use App\Entity\Article;
use App\Entity\Category;

final class ArticlePage
{
    public function __construct(
        public readonly Article $article,
        public readonly array $categories,
        public readonly array $similar,
    ) {}
}
