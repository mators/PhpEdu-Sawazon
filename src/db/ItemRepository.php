<?php

namespace db;

use models\Item;
use models\User;


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
        return parent::getAllIn("category_id", $categoryIds, self::$ORDER_BY[$sort]);
    }

    public function getAllByUsers($users)
    {
        return parent::getAllIn("user_id", $users);
    }

    public function addView($itemId)
    {
        $sql = "INSERT INTO `item_views` (`item_id`, `date`, `count`) VALUES (" . $itemId .
            ",'" . date("Y-m-d") ."',1) ON DUPLICATE KEY UPDATE `count`=`count`+1";
        DBPool::getInstance()->prepare($sql)->execute();
    }

    public function getViews($itemId)
    {
        $sql = "SELECT SUM(`count`) AS result FROM `item_views` WHERE `item_id`=".$itemId;
        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute();
        return $statement->fetch()->result;
    }

    public function getWeekViews($itemId)
    {
        $sql = "SELECT * FROM item_week_views WHERE item_id=".$itemId;
        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute();
        foreach ($statement as $row) {
            return $row;
        }
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
