<?php

require_once "src/inc/global.php";

use dispatcher\DefaultDispatcher;
use router\Router;
use models\NotFoundException;

try {
    DefaultDispatcher::getInstance()->dispatch();
} catch (NotFoundException $e) {
    redirect(Router::getRoute("error")->generate());
}
