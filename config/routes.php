<?php
use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/category/{slug}', [CategoryController::class, 'show']],
    ['GET', '/article/{slug}', [ArticleController::class, 'show']],
];