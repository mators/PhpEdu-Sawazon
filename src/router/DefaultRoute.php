<?php

namespace router;


class DefaultRoute implements Route {

    private $routeRegex;

    private $controller;

    private $action;

    private $params;

    public function __construct($route, $controller, $action) {
        $this->routeRegex = $route;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = [];
    }

    public function match($url) {
        $regex = "@^" . $this->routeRegex . "$@uD";
        return (bool) preg_match($regex, $url, $this->params);
    }

    public function generate(array $params = []) {
        return preg_replace_callback("@\\(\\?P<([a-z0-9_]+)>[^\\)]*\\)@iu", function ($match) use ($params) {
            return element($match[1], $params, null);
        }, $this->routeRegex);
    }

    public function getParam($key, $d = "") {
        return element($key, $this->params, $d);
    }

    public function getController() {
        return ucfirst($this->controller) . "Controller";
    }

    public function getAction() {
        return $this->action;
    }

}
