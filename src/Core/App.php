<?php

namespace App\Core;

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\ArticleService;
use App\Service\CategoryService;
use App\Support\Sorting;
use App\Exception\HttpException;
use Throwable;

final class App
{    
    private readonly string $templatesDir;
    private readonly string $varDir;
    private readonly array $routes;
    private readonly array $blog;
    private readonly array $config;

    public function __construct(string $rootDir) {
        $this->templatesDir = $rootDir . '/templates';
        $this->varDir = $rootDir . '/var/smarty';
        $this->routes = require $rootDir . '/config/routes.php';
        $this->blog = require $rootDir . '/config/blog.php';
        $this->config = require $rootDir . '/config/app.php';
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
            die((string) $e);
            $response = $this->errorResponse($view, 500, 'Ошибка сервера', []);
        }

        $response->send($request->method !== 'HEAD');
    }

    private function dispatch(Request $request, View $view)
    {
        $router = new Router($this->routes);
        [[$class, $action], $params] = $router->match($request);

        $blog = $this->blog;
        $pdo = Database::connect(...$this->config['db']);

        $categoryRepository = new CategoryRepository($pdo);
        $articleRepository = new ArticleRepository($pdo);

        $categoryService = new CategoryService(
            categories: $categoryRepository,
            articles: $articleRepository,
            sortings: Sorting::fromConfig($blog['sorting']['options']),
            defaultSort: $blog['sorting']['default'],
            perPage: $blog['per_page'],
            homeArticlesLimit: $blog['home_posts_per_category'],
        );
        $articleService = new ArticleService($articleRepository, $categoryRepository, $blog['similar_articles_limit']);

        $controllers = [
            HomeController::class     => new HomeController($view, $categoryService),
            CategoryController::class => new CategoryController($view, $categoryService),
            ArticleController::class  => new ArticleController($view, $articleService),
        ];

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
