<?php

namespace models;


interface Model {

    public function validate();

    public function getErrors();

}
