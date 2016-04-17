<?php

namespace db;

use models\Item;


class ItemRepository extends Repository
{
    private static $ORDER_BY = ["date_created DESC", "date_created", "price_usd", "price_usd DESC"];

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

    public function getUsersItems($userId, $sort = 0)
    {
        $sql = "SELECT * FROM items WHERE user_id = ".$userId
            ." ORDER BY " . self::$ORDER_BY[$sort];
        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute();

        if ($statement->rowCount() < 1) {
            return [];
        }

        $ret = [];
        foreach ($statement as $row) {
            $ret[] = $this->modelFromData($row);
        }
        return $ret;
    }

    public function getInCategories($categoryIds, $sort = 0)
    {
        $placeholders = [];
        for ($i = count($categoryIds); $i > 0 ; --$i) {
            $placeholders[] = "?";
        }
        $sql = "SELECT * FROM items WHERE category_id IN ("
            . implode(",", $placeholders) . ") " .
            "ORDER BY " . self::$ORDER_BY[$sort];

        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute($categoryIds);

        if ($statement->rowCount() < 1) {
            return [];
        }

        $ret = [];
        foreach ($statement as $row) {
            $ret[] = $this->modelFromData($row);
        }
        return $ret;
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
