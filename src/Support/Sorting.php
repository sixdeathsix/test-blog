<?php

namespace App\Support;

use InvalidArgumentException;

final class Sorting
{
    private const DIRECTIONS = ['asc', 'desc'];

    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $column,
        public readonly string $direction,
    ) {
        if (preg_match('/^[a-z_]+$/', $column) !== 1 || !in_array($direction, self::DIRECTIONS, true)) {
            throw new InvalidArgumentException("Неизвестный способ сортировки \"{$key}\"");
        }
    }

    public static function fromConfig(array $options)
    {
        $result = [];

        foreach ($options as $key => $option) {
            $result[$key] = new self($key, ...$option);
        }

        return $result;
    }

    public function withDirection(?string $direction)
    {
        $direction = strtolower((string) $direction);

        if (!in_array($direction, self::DIRECTIONS, true)) {
            return $this;
        }

        return new self($this->key, $this->label, $this->column, $direction);
    }

    public function oppositeDirection(): string
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }
}
