<?php

use router\Router as R;


R::registerRoutes([

    // R::route("/url/path/(?P<param>\\d+)", "controller", "action", "name"),

    R::route("/", "home", "index", "index"),
    R::route("/error", "home", "error", "error"),

    R::route("/login", "auth", "login", "login"),
    R::route("/logout", "auth", "logout", "logout"),
    R::route("/register", "auth", "register", "register"),

    R::route("/admin", "admin", "index", "adminpanel"),
    R::route("/admin/categories", "admin", "listCategories", "listCategories"),


]);
