<?php

namespace db;


use models\Quack;
use models\User;

class QuackRepository extends Repository
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new QuackRepository();
        }
        return self::$instance;
    }

    protected function getTable()
    {
        return "quacks";
    }

    public function save(Quack $quack)
    {
        return parent::save([
            "user_id" => $quack->getUserId(),
            "text" => $quack->getText(),
        ]);
    }

    public function update(Quack $quack)
    {
        return parent::update($quack->getQuackId(), [
            "user_id" => $quack->getUserId(),
            "text" => $quack->getText(),
        ]);
    }

    public function getAllByUsers($users)
    {
        return parent::getAllIn("user_id", $users);
    }

    protected function modelFromData($data)
    {
        return new Quack(
            $data->user_id,
            $data->text,
            $data->date_created,
            $data->date_modified,
            $data->id
        );
    }
}
