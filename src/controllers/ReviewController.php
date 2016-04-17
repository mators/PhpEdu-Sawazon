<?php

namespace controllers;

use db\ItemRepository;
use db\ReviewRepository;
use dispatcher\DefaultDispatcher as D;
use models\Item;
use models\Review;
use router\Router as R;


class ReviewController implements Controller
{

    public function add()
    {
        $itemId = D::getInstance()->getMatched()->getParam("id");
        /** @var Item $item */
        $item = ItemRepository::getInstance()->get($itemId);

        if (null == $item) {
            redirect(R::getRoute("error")->generate());
        }

        if (!isLoggedIn() || isCurrentUserId($item->getUserId())) {
            redirect(R::getRoute("error")->generate());
        }

        $review = new Review(
            post("grade"),
            post("text"),
            user()["id"],
            $itemId,
            null
        );

        $oldReview = ReviewRepository::getInstance()->getSingleOrNull([
            "user_id" => user()["id"],
            "item_id" => $itemId
        ]);

        if ($review->validate() && null == $oldReview) {
            ReviewRepository::getInstance()->save($review);
        }

        redirect(R::getRoute("showItem")->generate(["id" => $itemId]));
    }

    public function edit()
    {
        $itemId = D::getInstance()->getMatched()->getParam("id");
        $reviewId = D::getInstance()->getMatched()->getParam("revId");
        /** @var Item $item */
        $item = ItemRepository::getInstance()->get($itemId);
        /** @var Review $review */
        $review = ReviewRepository::getInstance()->get($reviewId);

        if (null == $item || null == $review) {
            redirect(R::getRoute("error")->generate());
        }

        if (!isCurrentUserId($review->getUserId())) {
            redirect(R::getRoute("error")->generate());
        }

        $review->setGrade(post("grade"));
        $review->setText(post("text"));

        if ($review->validate()) {
            ReviewRepository::getInstance()->update($review);
        }

        redirect(R::getRoute("showItem")->generate(["id" => $itemId]));
    }

}
