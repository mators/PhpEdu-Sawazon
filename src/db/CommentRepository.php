<?php

namespace db;


use models\Comment;

class CommentRepository extends Repository
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new CommentRepository();
        }
        return self::$instance;
    }

    protected function getTable()
    {
        return "comments";
    }

    public function save(Comment $comment)
    {
        parent::save([
            "quack_id" => $comment->getQuackId(),
            "text" => $comment->getText()
        ]);
    }

    public function update(Comment $comment)
    {
        parent::update($comment->getCommentId(), [
            "quack_id" => $comment->getQuackId(),
            "text" => $comment->getText()
        ]);
    }

    protected function modelFromData($data)
    {
        return new Comment(
            $data->quack_id,
            $data->text,
            $data->date_created,
            $data->id
        );
    }
}
