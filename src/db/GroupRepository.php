<?php

namespace db;


use models\Group;

class GroupRepository extends Repository
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new GroupRepository();
        }
        return self::$instance;
    }

    protected function getTable()
    {
        return "groups";
    }

    protected function modelFromData($data)
    {
        return new Group(
            $data->name,
            $data->id
        );
    }

}
