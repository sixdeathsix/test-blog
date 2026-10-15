<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Service\CategoryService;

final class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryService $categories,
    ) {}

    public function index(Request $request)
    {
        return new Response($this->view->render('pages/home.tpl', [
            'sections' => $this->categories->getHomeSections(),
        ]));
    }
}
