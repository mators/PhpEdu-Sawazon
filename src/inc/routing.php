<?php

use router\Router as R;


R::registerRoutes([

    // R::route("/url/path/(?P<param>\\d+)", "controller", "action", "name"),

    R::route("/", "home", "index", "index"),
    R::route("/error", "home", "error", "error"),

    R::route("/login", "auth", "login", "login"),
    R::route("/logout", "auth", "logout", "logout"),
    R::route("/register", "auth", "register", "register"),

    R::route("/browse", "category", "browse", "browse"),

    R::route("/categories", "category", "listCategories", "listCategories", ['ADMIN']),
    R::route("/categories/add", "category", "add", "addCategory", ['ADMIN']),
    R::route("/categories/(?P<id>\\d+)/edit", "category", "edit", "editCategory", ['ADMIN']),

    R::route("/categories/(?P<id>\\d+)", "category", "index", "viewCategory"),
    R::route("/categories/(?P<id>\\d+)/icon", "category", "getIcon", "getIcon"),
    R::route("/categories/(?P<id>\\d+)/children", "category", "getChildren", "categoryChildren"),

    R::route("/users/(?P<username>\\w+)", "user", "profile", "profile"),
    R::route("/users/(?P<username>\\w+)/settings", "user", "settings", "accountSettings"),
    R::route("/users/(?P<username>\\w+)/picture/(?P<size>sm|or)", "user", "profilePicture", "profilePicture"),

    R::route("/items/add", "item", "add", "addItem"),
    R::route("/items/(?P<id>\\d+)", "item", "index", "showItem"),
    R::route("/items/(?P<id>\\d+)/edit", "item", "edit", "editItem"),
    R::route("/items/(?P<id>\\d+)/(?P<size>sm|or)", "item", "getFirstPicture", "itemFirstPicture"),
    R::route("/pictures/(?P<id>\\d+)", "item", "getPicture", "itemPicture"),
    R::route("/items/(?P<id>\\d+)/addReview", "review", "add", "addReview"),
    R::route("/items/(?P<id>\\d+)/(?P<revId>\\d+)/edit", "review", "edit", "editReview")

]);
