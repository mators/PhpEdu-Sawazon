<?php

namespace db;

use models\Category;


class CategoryRepository extends Repository
{

    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new CategoryRepository();
        }
        return self::$instance;
    }

    public function addCategory(Category $category, $parentId)
    {
        $sql = "CALL add_category(?, ?, ?, ?)";

        DBPool::getInstance()->prepare($sql)->execute([
            $category->getName(),
            $category->getDescription(),
            $category->getIcon(),
            $parentId
        ]);

        return DBPool::getInstance()->lastInsertId();
    }

    public function getAllWithDepth()
    {
        // $sql = "SELECT * FROM categories_depth";
        $sql = "SELECT node.*, (COUNT(parent.name) - 1) AS depth
                FROM " . $this->getTable() . " AS node,
                      " . $this->getTable() . " AS parent
                WHERE node.lft BETWEEN parent.lft AND parent.rgt
                GROUP BY node.name
                HAVING depth > 0
                ORDER BY node.lft";

        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute();

        if ($statement->rowCount() < 1) {
            return null;
        }

        $ret = [];
        foreach ($statement as $row) {
            $ret[] = $this->modelFromData($row);
        }
        return $ret;
    }

    public function  getLeafs()
    {

    }

    protected function getTable()
    {
        return "categories";
    }

    protected function modelFromData($data)
    {
        $category = new Category(
            $data->name,
            $data->description,
            $data->icon,
            $data->id
        );
        if ($data->depth) {
            $category->setDepth($data->depth);
        }
        return $category;
    }

}