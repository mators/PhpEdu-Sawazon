<?php

namespace controllers;

use db\CategoryRepository;
use views\CommonView;
use views\ErrorView;
use views\HomeView;


class HomeController implements Controller
{

    public function index()
    {
        $categories = CategoryRepository::getInstance()->getAllWithDepth(["depth" => "1"]);
        echo new CommonView([
            "title" => "Sawazon",
            "body" => new HomeView([
                "categories" => $categories
            ]),
            "scripts" => ['/assets/js/home.js']
        ]);
    }

    public function error()
    {
        echo new CommonView([
            "title" => "Sawazon",
            "body" => new ErrorView([ "message" => "Page not found." ])
        ]);
    }

}
