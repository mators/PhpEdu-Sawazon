<?php

namespace router;


class Router {

    private static $routes = [];

    public static function register($name, Route $route) {
        self::$routes[$name] = $route;
    }

    /**
     * @param $name
     * @return Route|null
     */
    public static function getRoute($name) {
        return element($name, self::$routes);
    }

    public static function getRoutes() {
        return self::$routes;
    }

    public static function registerRoutes($routes) {
        foreach ($routes as $route) {
            self::register($route[0], $route[1]);
        }
    }

    public static function route($url, $controller, $action, $name, $paramsRegex = []) {
        return [$name, new DefaultRoute($url, $controller, $action, $paramsRegex)];
    }

}
