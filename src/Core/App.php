<?php

namespace App\Core;

use App\Controller\HomeController;
use App\Exception\HttpException;
use Throwable;

final class App
{    
    private readonly string $templatesDir;
    private readonly string $varDir;
    private readonly string $routesFile;

    public function __construct(string $rootDir) {
        $this->templatesDir = $rootDir . '/templates';
        $this->varDir = $rootDir . '/var/smarty';
        $this->routesFile = $rootDir . '/config/routes.php';
    }

    public function run()
    {
        $request = Request::request();
        $view = null;

        try {
            $view = new View($this->templatesDir, $this->varDir);
            $response = $this->dispatch($request, $view);
        } catch (HttpException $e) {
            $response = $this->errorResponse($view, $e->getCode(), $e->getMessage(), $e->headers);
        } catch (Throwable $e) {
            $response = $this->errorResponse($view, 500, 'Внутренняя ошибка сервера', []);
        }

        $response->send($request->method !== 'HEAD');
    }

    private function dispatch(Request $request, View $view)
    {
        $controllers = [
            HomeController::class => new HomeController($view),
        ];

        $routes = require $this->routesFile;
        $router = new Router($routes);
        
        [[$class, $action], $params] = $router->match($request);

        return $controllers[$class]->$action($request, ...$params);
    }

    private function errorResponse(?View $view, int $status, string $message, array $headers)
    {
        if ($view !== null) {
            try {
                $html = $view->render('errors/error.tpl', [
                    'status'  => $status,
                    'message' => $message,
                ]);
        
                return new Response($html, $status, $headers);
            } catch (Throwable) {}
        }

        $html = sprintf(
            '<title>%1$d</title><h1>%1$d</h1><p>%2$s</p>',
            $status,
            htmlspecialchars($message),
        );

        return new Response($html, $status, $headers);
    }
}
