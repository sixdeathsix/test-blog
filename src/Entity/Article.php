<?php

namespace App\Entity;

use DateTimeImmutable;

final class Article
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly string $title,
        public readonly string $description,
        public readonly string $content,
        public readonly string $image,
        public readonly int $views,
        public readonly DateTimeImmutable $publishedAt,
    ) {
    }
}
