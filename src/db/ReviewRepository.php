<?php

namespace db;

use models\Review;


class ReviewRepository extends Repository
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new ReviewRepository();
        }
        return self::$instance;
    }

    public function save(Review $review)
    {
        return parent::save([
            "grade" => $review->getGrade(),
            "text" => $review->getText(),
            "user_id" => $review->getUserId(),
            "item_id" => $review->getItemId()
        ]);
    }

    public function update(Review $review)
    {
        return parent::update($review->getReviewId(), [
            "grade" => $review->getGrade(),
            "text" => $review->getText(),
            "user_id" => $review->getUserId(),
            "item_id" => $review->getItemId()
        ]);
    }

    protected function getTable()
    {
        return "reviews";
    }

    protected function modelFromData($data)
    {
        return new Review(
            $data->grade,
            $data->text,
            $data->user_id,
            $data->item_id,
            $data->date_created,
            $data->id
        );
    }

}
