<?php

namespace App\Core;

final class Response
{
    public readonly array $headers;

    public function __construct(
        public readonly string $body = '',
        public readonly int $status = 200,
        array $headers = [],
    ) {
        $this->headers = array_key_exists('content-type', array_change_key_case($headers))
                        ? $headers
                        : $headers + ['Content-Type' => 'text/html; charset=utf-8'];
    }

    public static function redirect(string $url, int $status = 302)
    {
        return new self('', $status, ['Location' => $url]);
    }

    public function send(bool $withBody = true)
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        if ($withBody) {
            echo $this->body;
        }
    }
}
