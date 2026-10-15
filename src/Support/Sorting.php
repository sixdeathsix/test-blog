<?php

namespace App\Support;

use InvalidArgumentException;

final class Sorting
{
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $column,
        public readonly string $direction,
    ) {
        if (preg_match('/^[a-z_]+$/D', $column) !== 1) {
            throw new InvalidArgumentException("Ошибка сортировки \"{$key}\"");
        }

        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            throw new InvalidArgumentException("Ошибка типа сортировки \"{$key}\"");
        }
    }

    public static function fromConfig(array $options)
    {
        $result = [];

        foreach ($options as $key => $option) {
            $result[$key] = new self($key, $option['label'], $option['column'], $option['direction']);
        }

        return $result;
    }
}
