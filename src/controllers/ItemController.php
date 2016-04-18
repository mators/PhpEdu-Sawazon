<?php

namespace controllers;

use db\CategoryRepository;
use db\CurrencyRepository;
use db\ItemRepository;
use db\PictureRepository;
use db\ReviewRepository;
use db\TagRepository;
use db\UserRepository;
use lib\graficonlib\BarChart;
use lib\graficonlib\DataCollection;
use lib\graficonlib\DataCollectionItem;
use models\Item;
use models\Picture;
use models\Review;
use router\Router as R;
use dispatcher\DefaultDispatcher as D;
use views\CommonView;
use views\ItemFormView;
use views\ItemView;


class ItemController implements Controller
{

    public function index()
    {
        $itemId = D::getInstance()->getMatched()->getParam("id");
        /** @var Item $item */
        $item = ItemRepository::getInstance()->get($itemId);

        if (null == $item) {
            redirect(R::getRoute("error")->generate());
        }
        $tagsData = TagRepository::getInstance()->getAll(["item_id" => $itemId]);
        $tags = [];
        foreach ($tagsData as $data) {
            $tags[] = $data->tag;
        }
        $item->setTags($tags);

        $seller = UserRepository::getInstance()->get($item->getUserId());
        $pictures = PictureRepository::getInstance()->getAll(["item_id" => $itemId]);
        $reviews = ReviewRepository::getInstance()->getAll(["item_id" => $itemId]);
        $usersReview = [];
        /** @var Review $review */
        foreach ($reviews as $review) {
            $usersReview[] = UserRepository::getInstance()->get($review->getUserId());
        }

        ItemRepository::getInstance()->addView($itemId);

        echo new CommonView([
            "title" => "Sawazon - ".$item->getName(),
            "body" => new ItemView([
                "item" => $item,
                "pictures" => $pictures,
                "seller" => $seller,
                "reviews" => $reviews,
                "usersReview" => $usersReview,
                "views" => ItemRepository::getInstance()->getViews($itemId)
            ]),
            "scripts" => ["/assets/js/editReviewModal.js"]
        ]);
    }

    public function add()
    {
        if (!isLoggedIn()) {
            redirect(R::getRoute("login")->generate());
        }

        $categories = CategoryRepository::getInstance()->getAllWithDepth();
        $currencies = CurrencyRepository::getInstance()->getAll();
        $item = new Item("", "", "", "", "", null, null);

        if (isPost()) {
            $usdPrice = CurrencyRepository::getInstance()->get(post("currency"));

            $item = new Item(
                post("name"),
                post("description"),
                user()["id"],
                post("categoryId"),
                post("price") / doubleval($usdPrice->getCoefficient()),
                null,
                null
            );

            $tags = array_unique(preg_split("/[\\s,]+/", post("tags")));

            if (!empty($tags[0])) {
                $item->setTags($tags);
            }

            if ($item->validate()) {
                $f = $_FILES['file'];
                $filesCount = empty($f['name'][0]) ? 0 : count($f['name']);
                $pics = [];
                if ($filesCount > 0) {
                    for ($i = 0; $i < $filesCount; ++$i) {
                        $picture = new Picture();
                        $picture->setFile([
                            "name" => $f['name'][$i],
                            "type" => $f['type'][$i],
                            "size" => $f['size'][$i],
                            "tmp_name" => $f['tmp_name'][$i],
                            "error" => $f['error'][$i],
                        ]);
                        if ($picture->validate()) {
                            $pics[] = $picture->getPictureString();
                        }
                    }
                }
                if ($filesCount == count($pics)) {
                    $itemId = ItemRepository::getInstance()->save($item);
                    TagRepository::getInstance()->saveAll($itemId, $tags);
                    PictureRepository::getInstance()->saveAll($itemId, $pics);
                    redirect(R::getRoute("index")->generate());
                }
            }
        }
        echo new CommonView([
            "title" => "Sawazon - Add item",
            "body" => new ItemFormView([
                "categories" => $categories,
                "currencies" => $currencies,
                "item" => $item,
                "title" => "Add item",
                "action" => R::getRoute("addItem")->generate(),
                "errors" => $item->getErrors(),
            ]),
        ]);
    }

    public function edit()
    {
        $itemId = D::getInstance()->getMatched()->getParam("id");
        /** @var Item $item */
        $item = ItemRepository::getInstance()->get($itemId);

        if (null == $item) {
            redirect(R::getRoute("error")->generate());
        }

        if (!isCurrentUserId($item->getUserId())) {
            redirect(R::getRoute("login")->generate());
        }

        $categories = CategoryRepository::getInstance()->getAllWithDepth();
        $currencies = CurrencyRepository::getInstance()->getAll();
        $tagsData = TagRepository::getInstance()->getByItem($itemId);
        $tags = [];
        foreach ($tagsData as $data) {
            $tags[] = $data->tag;
        }
        $item->setTags($tags);

        if (isPost()) {
            $usdPrice = CurrencyRepository::getInstance()->get(post("currency"));
            $newTags = array_unique(preg_split("/[\\s,]+/", post("tags")));
            $deleteTags = array_diff($tags, $newTags);
            $addTags = array_diff($newTags, $tags);

            $item->setName(post("name"));
            $item->setDescription(post("description"));
            $item->setCategoryId(post("categoryId"));
            $item->setUsdPrice(post("price") / doubleval($usdPrice->getCoefficient()));
            $item->setTags($newTags);

            if ($item->validate()) {
                ItemRepository::getInstance()->update($item);
                TagRepository::getInstance()->deleteAll($itemId, $deleteTags);
                TagRepository::getInstance()->saveAll($itemId, $addTags);
                redirect(R::getRoute("index")->generate());
            }
        }
        echo new CommonView([
            "title" => "Sawazon - Edit item",
            "body" => new ItemFormView([
                "categories" => $categories,
                "currencies" => $currencies,
                "item" => $item,
                "title" => "Edit item",
                "errors" => $item->getErrors(),
                "action" => R::getRoute("editItem")->generate(["id" => $item->getItemId()]),
            ]),
        ]);
    }

    public function getFirstPicture()
    {
        header("Content-type: image/png");
        $itemId = D::getInstance()->getMatched()->getParam("id");
        $size = D::getInstance()->getMatched()->getParam("size");
        /** @var Picture $picture */
        $picture = PictureRepository::getInstance()->getSingleOrNull(["item_id" => $itemId]);

        if (null == $picture) {
            echo "tu bi trebalo staviti defaultnu sliku :)";
        } else {
            $image = imagecreatefromstring($picture->getPictureString());
            if ($size == "sm") {
                $image = Picture::getResized($image, 128, 128);
            }
            imagepng($image);
            imagedestroy($image);
        }
    }

    public function getPicture()
    {
        header("Content-type: image/png");
        $pictureId = D::getInstance()->getMatched()->getParam("id");
        /** @var Picture $picture */
        $picture = PictureRepository::getInstance()->get($pictureId);

        if (null == $picture) {
            echo "tu bi trebalo staviti defaultnu sliku :)";
        } else {
            $image = imagecreatefromstring($picture->getPictureString());
            imagepng($image);
            imagedestroy($image);
        }
    }

    public function weekViewsChart()
    {
        header('Content-Type: image/png');

        $itemId = D::getInstance()->getMatched()->getParam("id");
        $stats = ItemRepository::getInstance()->getWeekViews($itemId);

        $barChart = new BarChart("Views last week", 300, 700);
        $barChart->set_font_size(5);

        $data = new DataCollection();
        $data->add_items([
            new DataCollectionItem((int)$stats->day1, date("Y-m-d", strtotime("-6 days"))),
            new DataCollectionItem((int)$stats->day2, date("Y-m-d", strtotime("-5 days"))),
            new DataCollectionItem((int)$stats->day3, date("Y-m-d", strtotime("-4 days"))),
            new DataCollectionItem((int)$stats->day4, date("Y-m-d", strtotime("-3 days"))),
            new DataCollectionItem((int)$stats->day5, date("Y-m-d", strtotime("-2 days"))),
            new DataCollectionItem((int)$stats->day6, date("Y-m-d", strtotime("-1 days"))),
            new DataCollectionItem((int)$stats->day7, date("Y-m-d"))
        ]);
        $ids = $barChart->add_data($data);
        $barChart->color_data(66, 139, 202, $ids);

        imagepng($barChart->render());
    }

}
