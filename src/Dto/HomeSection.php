<?php

namespace App\Dto;

use App\Entity\Article;
use App\Entity\Category;

final class HomeSection
{
    public function __construct(
        public readonly Category $category,
        public readonly array $articles,
    ) {}
}
