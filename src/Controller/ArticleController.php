<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Exception\HttpException;
use App\Service\ArticleService;

final class ArticleController
{
    public function __construct(
        private readonly View $view,
        private readonly ArticleService $articles,
    ) {}

    public function show(Request $request, string $slug)
    {
        $articlePage = $this->articles->getForView($slug);

        if ($articlePage === null) {
            throw HttpException::notFound();
        }

        return new Response($this->view->render('pages/article.tpl', [
            'articlePage' => $articlePage,
        ]));
    }
}
