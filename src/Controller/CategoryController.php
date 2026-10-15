<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Exception\HttpException;
use App\Service\CategoryService;

final class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryService $categories,
    ) {}

    public function show(Request $request, string $slug)
    {
        $page = filter_var($request->query('page'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;

        $listing = $this->categories->getListing($slug, $request->query('sort'), $request->query('dir'), $page);

        if ($listing === null) {
            throw HttpException::notFound();
        }

        return new Response($this->view->render('pages/category.tpl', [
            'listing' => $listing,
        ]));
    }
}
