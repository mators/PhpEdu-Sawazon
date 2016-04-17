<?php

namespace db;


class TagRepository extends Repository {

    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new TagRepository();
        }
        return self::$instance;
    }

    public function getByItem($itemId) {
        return parent::getAll(["item_id" => $itemId]);
    }

    public function save($itemId, $tag) {
        return parent::save([
            "item_id" => $itemId,
            "tag" => $tag
        ]);
    }

    public function saveAll($itemId, $tags) {
        $ret = [];
        foreach ($tags as $tag) {
            $ret[] = $this->save($itemId, $tag);
        }
        return $ret;
    }

    public function delete($itemId, $tag)
    {
        $sql = "DELETE FROM tags WHERE item_id = ? AND tag = ?";
        return DBPool::getInstance()->prepare($sql)->execute([$itemId, $tag]);
    }

    public function deleteAll($itemId, $tags)
    {
        foreach ($tags as $tag) {
            $this->delete($itemId, $tag);
        }
    }

    protected function getTable() {
        return "tags";
    }

    protected function modelFromData($data) {
        return $data;
    }

}
