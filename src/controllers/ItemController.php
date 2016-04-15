<?php

namespace controllers;

use db\CategoryRepository;
use db\CurrencyRepository;
use db\ItemRepository;
use db\PictureRepository;
use models\Item;
use models\Picture;
use router\Router as R;
use dispatcher\DefaultDispatcher as D;
use views\CommonView;
use views\ItemFormView;


class ItemController implements Controller
{

    public function index()
    {

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

            if ($item->validate()) {
                $f = $_FILES['file'];
                $filesCount = count($f['name']);
                $pics = [];
                if ($filesCount > 0) {
                    for ($i = 0; $i < $filesCount; ++$i) {
                        $picture = new Picture();
                        $picture->setFile([
                            "name" => $f['name'][$i],
                            "type" => $f['type'][$i],
                            "size" => $f['size'][$i],
                            "tmp_name" => $f['tmp_name'][$i],
                            "error" => $f['error'][$i]
                        ]);
                        if ($picture->validate()) {
                            $pics[] = $picture->getPictureString();
                        }
                    }
                }
                if ($filesCount == count($pics)) {
                    $itemId = ItemRepository::getInstance()->save($item);
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
                "action" => R::getRoute("addItem")->generate()
            ])
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

        if (isPost()) {
            $usdPrice = CurrencyRepository::getInstance()->get(post("currency"));

            $item->setName(post("name"));
            $item->setDescription(post("description"));
            $item->setCategoryId(post("categoryId"));
            $item->setUsdPrice(post("price") / doubleval($usdPrice->getCoefficient()));

            if ($item->validate()) {
                ItemRepository::getInstance()->update($item);
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
                "action" => R::getRoute("editItem")->generate(["id" => $item->getItemId()])
            ])
        ]);
    }

}
