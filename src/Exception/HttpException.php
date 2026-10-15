<?php

namespace App\Exception;

use RuntimeException;

final class HttpException extends RuntimeException
{
    private function __construct(
        int $status, 
        string $message, 
        public readonly array $headers = []
    ) {
        parent::__construct($message, $status);
    }

    public static function notFound()
    {
        return new self(404, 'Страница не найдена');
    }

    public static function methodNotAllowed(array $allowedMethods)
    {
        return new self(405, 'Метод не поддерживается', ['Allow' => implode(', ', $allowedMethods)]);
    }
}