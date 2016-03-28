<?php

namespace controllers;

use views\CommonView;
use views\ErrorView;


class HomeController implements Controller
{

    public function index()
    {
        echo new CommonView([
            "title" => "Sawazon",
            "body" => "<div class='page'>hehe</div>"
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
