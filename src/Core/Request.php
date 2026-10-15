<?php

namespace App\Core;

final class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        private readonly array $query = [],
    ) {}

    public static function request()
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            is_string($path) && $path !== '' ? $path : '/',
            $_GET,
        );
    }

    public function query(string $key)
    {
        $value = $this->query[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
