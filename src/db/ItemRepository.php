<?php

namespace db;

use models\Item;


class ItemRepository extends Repository
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new ItemRepository();
        }
        return self::$instance;
    }

    public function save(Item $item)
    {
        return parent::save([
            "name" => $item->getName(),
            "description" => $item->getDescription(),
            "user_id" => $item->getUserId(),
            "category_id" => $item->getCategoryId(),
            "price_usd" => $item->getUsdPrice()
        ]);
    }

    public function update(Item $item)
    {
        return parent::update($item->getItemId(), [
            "name" => $item->getName(),
            "description" => $item->getDescription(),
            "user_id" => $item->getUserId(),
            "category_id" => $item->getCategoryId(),
            "price_usd" => $item->getUsdPrice()
        ]);
    }

    protected function getTable()
    {
        return "items";
    }

    protected function modelFromData($data)
    {
        return new Item(
            $data->name,
            $data->description,
            $data->user_id,
            $data->category_id,
            $data->price_usd,
            $data->date_modified,
            $data->date_created,
            $data->id
        );
    }

}
