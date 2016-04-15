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

    public function update(Category $category) {
        return parent::update($category->getCategoryId(), [
            "name" => $category->getName(),
            "description" => $category->getDescription(),
            "icon" => $category->getIcon()
        ]);
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

    public function getAllWithDepth($havingConditions = [])
    {
        $sql = "SELECT node.*, (COUNT(parent.name) - 1) AS depth
                FROM categories AS node,
                      categories AS parent
                WHERE node.lft BETWEEN parent.lft AND parent.rgt
                GROUP BY node.name HAVING ";

        if (empty($havingConditions)) {
            $sql .= "depth > 0 ";
        } else {
            foreach ($havingConditions as $attr => $cond) {
                $sql .= $attr . " = " .  $cond . " AND ";
            }
            $sql = substr($sql, 0, -5);
        }

        $sql .= " ORDER BY node.lft";

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

    public function getChildren($parentId)
    {
        $sql = "SELECT node.*, (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth
                FROM categories AS node,
                        categories AS parent,
                        categories AS sub_parent,
                        (
                                SELECT node.name, (COUNT(parent.name) - 1) AS depth
                                FROM categories AS node,
                                        categories AS parent
                                WHERE node.lft BETWEEN parent.lft AND parent.rgt
                                        AND node.id = " . $parentId . "
                                GROUP BY node.name
                                ORDER BY node.lft
                        )AS sub_tree
                WHERE node.lft BETWEEN parent.lft AND parent.rgt
                        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
                        AND sub_parent.name = sub_tree.name
                GROUP BY node.name
                HAVING depth = 1
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

    public function  saveUserCategories($userId, $catIds)
    {
        $sql = "INSERT INTO users_categories (user_id, category_id)
                VALUES ";
        foreach ($catIds as $catId) {
            $sql .= "(" . $userId . ", " . $catId . "),";
        }
        $sql = substr($sql, 0, -1);
        DBPool::getInstance()->prepare($sql)->execute();
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
        if (property_exists($data, "depth")){
            $category->setDepth($data->depth);
        }
        return $category;
    }

}
