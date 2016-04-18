<?php

namespace controllers;

use db\CategoryRepository;
use db\ItemRepository;
use db\ReviewRepository;
use db\UserRepository;
use db\QuackRepository;
use models\Item;
use models\Quack;
use models\Review;
use views\CommonView;
use views\ErrorView;
use views\HomeView;
use router\Router as R;


class HomeController implements Controller
{

    public function index()
    {
        if (isLoggedIn()) {
            if (isPost()) {
                $quack = new Quack(
                    user()["id"],
                    post("text"),
                    null,
                    null
                );
                if ($quack->validate()) {
                    QuackRepository::getInstance()->save($quack);
                }
            }

            $following = UserRepository::getInstance()->getFollowing(user()["id"]);
            $quacks = QuackRepository::getInstance()->getAllByUsers(array_merge($following, [user()["id"]]));
            $reviews = ReviewRepository::getInstance()->getAllByUsers($following);
            $items = ItemRepository::getInstance()->getAllByUsers($following);

            /** @var Quack $quack */
            foreach ($quacks as $quack) {
                $quack->setUser(UserRepository::getInstance()->get($quack->getUserId()));
            }
            /** @var Review $review */
            foreach ($reviews as $review) {
                $review->setUser(UserRepository::getInstance()->get($review->getUserId()));
                $review->setItem(ItemRepository::getInstance()->get($review->getItemId()));
            }
            /** @var Item $item */
            foreach ($items as $item) {
                $item->setUser(UserRepository::getInstance()->get($item->getUserId()));
            }

            $everything = array_merge($quacks, $reviews, $items);
            usort($everything, "compareByDateCreated");

            $categories = CategoryRepository::getInstance()->getUserCategories(user()["id"]);
            $fromCategories = ItemRepository::getInstance()->getInCategories($categories);

            echo new CommonView([
                "title" => "Sawazon",
                "body" => new HomeView([
                    "posts" => $everything,
                    "fromCategories" => $fromCategories,
                ]),
            ]);
        } else {
            redirect(R::getRoute("browse")->generate());
        }
    }

    public function error()
    {
        echo new CommonView([
            "title" => "Sawazon",
            "body" => new ErrorView([ "message" => "Page not found." ]),
        ]);
    }

}
