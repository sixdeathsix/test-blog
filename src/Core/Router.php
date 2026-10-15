<?php

namespace App\Core;

use App\Exception\HttpException;

final class Router
{
    public function __construct(private readonly array $routes) {}

    public function match(Request $request)
    {
        $requestMethod = $request->method === 'HEAD' ? 'GET' : $request->method;
        $allowedMethods = [];

        foreach ($this->routes as [$method, $pattern, $handler]) {
            $params = $this->matchPath($pattern, $request->path);

            if ($params === null) {
                continue;
            }

            if ($method !== $request->method) {
                $allowedMethods[] = $method;
                continue;
            }

            return [$handler, $params];
        }

        if ($allowedMethods !== []) {
            throw HttpException::methodNotAllowed(array_values(array_unique($allowedMethods)));
        }

        throw HttpException::notFound();
    }

    private function matchPath(string $pattern, string $path)
    {
        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[a-z0-9-]+)', $pattern) . '$#D';

        if (preg_match($regex, $path, $matches) !== 1) {
            return null;
        }

        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }
}
