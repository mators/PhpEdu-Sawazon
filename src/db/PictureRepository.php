<?php

namespace db;


use models\Picture;

class PictureRepository extends Repository
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new PictureRepository();
        }
        return self::$instance;
    }

    public function saveAll($itemId, $pictures)
    {
        foreach ($pictures as $picture) {
            parent::save([
                "photo" => $picture,
                "item_id" => $itemId
            ]);
        }
    }

    protected function getTable()
    {
        return "photos";
    }

    protected function modelFromData($data)
    {
        return new Picture($data->photo);
    }

}