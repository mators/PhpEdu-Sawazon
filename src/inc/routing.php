<?php

use router\Router as R;


R::registerRoutes([

    // R::route("/url/path/(?P<param>\\d+)", "controller", "action", "name"),

    R::route("/", "home", "index", "index"),
    R::route("/error", "home", "error", "error"),

    R::route("/login", "auth", "login", "login"),
    R::route("/logout", "auth", "logout", "logout"),
    R::route("/register", "auth", "register", "register"),

    R::route("/categories", "category", "listCategories", "listCategories", ['ADMIN']),
    R::route("/categories/add", "category", "add", "addCategory", ['ADMIN']),
    R::route("/categories/(?P<id>\\d+)/edit", "category", "edit", "editCategory", ['ADMIN']),
    R::route("/categories/(?P<id>\\d+)/delete", "category", "delete", "deleteCategory", ['ADMIN'])

]);
