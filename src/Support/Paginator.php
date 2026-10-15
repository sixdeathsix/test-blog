<?php

namespace App\Support;

final class Paginator
{
    public readonly int $totalPages;

    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
        public readonly int $total,
    ) {
        if ($perPage < 1 || $total < 0) {
            throw new InvalidArgumentException('Значение perPage должно быть >= 1');
        }

        $this->totalPages = max(1, intdiv($total + $perPage - 1, $perPage));
    }

    public function offset()
    {
        return ($this->page - 1) * $this->perPage;
    }

    public function isOutOfRange()
    {
        return $this->page < 1 || $this->page > $this->totalPages;
    }
}
