<?php

namespace controllers;

use views\CommonView;
use views\ErrorView;
use views\HomeView;
use router\Router as R;


class HomeController implements Controller
{

    public function index()
    {
        if (isLoggedIn()) {
            echo new CommonView([
                "title" => "Sawazon",
                "body" => new HomeView()
            ]);
        } else {
            redirect(R::getRoute('browse')->generate());
        }
    }

    public function error()
    {
        echo new CommonView([
            "title" => "Sawazon",
            "body" => new ErrorView([ "message" => "Page not found." ])
        ]);
    }

}
