<?php

namespace router;


interface Route {

    public function match($request);

    public function generate(array $params = []);

    public function getParam($key, $d = "");
}
