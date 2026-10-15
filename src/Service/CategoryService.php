<?php

namespace App\Service;

use App\Dto\CategoryListing;
use App\Dto\HomeSection;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Support\Paginator;
use App\Support\Sorting;

final class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly ArticleRepository $articles,
        private readonly array $sortings,
        private readonly string $defaultSort,
        private readonly int $perPage,
        private readonly int $homeArticlesLimit,
    ) {}

    public function getHomeSections()
    {
        $latest = $this->articles->findLatestPerCategory($this->homeArticlesLimit);
        $sections = [];

        foreach ($this->categories->findAll() as $category) {
            if (isset($latest[$category->id])) {
                $sections[] = new HomeSection($category, $latest[$category->id]);
            }
        }

        return $sections;
    }

    public function getListing(string $slug, ?string $sortKey, ?string $direction, int $page)
    {
        $category = $this->categories->findBySlug($slug);

        if ($category === null) {
            return null;
        }

        $sort = ($this->sortings[$sortKey ?? ''] ?? $this->sortings[$this->defaultSort])
            ->withDirection($direction);
        $paginator = new Paginator($page, $this->perPage, $this->articles->countByCategory($category->id));

        if ($paginator->isOutOfRange()) {
            return null;
        }

        return new CategoryListing(
            $category,
            $this->articles->findByCategory($category->id, $sort, $paginator->perPage, $paginator->offset()),
            $paginator,
            $sort,
            array_values($this->sortings),
        );
    }
}
