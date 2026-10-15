<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

final class HomeController
{
    public function __construct(private readonly View $view) {}

    public function index(Request $request)
    {
        return new Response($this->view->render('pages/home.tpl', [
            'heading' => 'Блог'
        ]));
    }
}
