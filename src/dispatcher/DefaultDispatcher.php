<?php

namespace dispatcher;

use models\NotFoundException;
use router\DefaultRoute;
use router\Route;
use router\Router;


class DefaultDispatcher implements Dispatcher {

    private static $instance;

    /**
     * @var DefaultRoute
     */
    private $matchedRoute;

    private function __construct() {}

    public static function getInstance() {
        if (null == self::$instance) {
            self::$instance = new DefaultDispatcher();
        }
        return self::$instance;
    }

    public function dispatch() {
        $requestURI = $_SERVER["REQUEST_URI"];

        if (($pos = strpos($requestURI, "?")) !== false) {
            $requestURI = substr($requestURI, 0, $pos);
        }

        $this->matchedRoute = null;

        /* @var $route Route */
        foreach (Router::getRoutes() as $route) {
            if ($route->match($requestURI)) {
                $this->matchedRoute = $route;
                break;
            }
        }

        if (null === $this->matchedRoute) {
            throw new NotFoundException();
        }
        $controllerName = "\\controllers\\" . $this->matchedRoute->getController();
        $action = $this->matchedRoute->getAction();

        //dirty fix to try and load class
        $func = function ($className) {
            throw new \Exception();
        };
        spl_autoload_register($func);

        $controller = null;

        try {
            $controller = new $controllerName;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }

        spl_autoload_unregister($func);

        if (!is_callable(array($controller, $action))) {
            throw new NotFoundException();
        }

        $controller->$action();
    }

    public function getMatched() {
        return $this->matchedRoute;
    }

}
