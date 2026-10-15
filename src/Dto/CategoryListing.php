<?php

namespace App\Dto;

use App\Entity\Article;
use App\Entity\Category;
use App\Support\Paginator;
use App\Support\Sorting;

final class CategoryListing
{
    public function __construct(
        public readonly Category $category,
        public readonly array $articles,
        public readonly Paginator $paginator,
        public readonly Sorting $sort,
        public readonly array $sortings,
    ) {}
}
