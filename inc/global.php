<?php

require_once "func.php";

session_start();

spl_autoload_register(function ($className) {
    $fileName = "./" . str_replace("\\", "/", $className) . ".php";

    if (!is_readable($fileName)) {
        return false;
    }

    require_once $fileName;
    return true;
});

require_once "routing.php";
