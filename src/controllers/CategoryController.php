<?php

namespace controllers;


use db\CategoryRepository;
use db\CurrencyRepository;
use db\ItemRepository;
use models\Category;
use models\Currency;
use models\Picture;
use views\CategoriesView;
use views\CategoryView;
use views\CommonView;
use views\BrowseView;
use router\Router as R;
use dispatcher\DefaultDispatcher as D;

class CategoryController
{

    public function index()
    {
        $categoryId = D::getInstance()->getMatched()->getParam("id");
        $category = CategoryRepository::getInstance()->get($categoryId);
        $categories = CategoryRepository::getInstance()->getChildren($categoryId);
        $currencies = CurrencyRepository::getInstance()->getAll();

        $sort = get("sort") ?: 0;
        if ($sort < 0 || $sort > 3) {
            $sort = 0;
        }
        $currencyId = get("currency");
        if ($currencyId != null) {
            /** @var Currency $currency */
            $currency = CurrencyRepository::getInstance()->get($currencyId);
            if ($currency != null) {
                $_SESSION["currency"] = [
                    "short" => $currency->getShortName(),
                    "coefficient" => $currency->getCoefficient()
                ];
            }
        }


        $ids = [$categoryId];
        /** @var Category $cat */
        foreach ($categories as $cat) {
            $ids[] = $cat->getCategoryId();
        }

        $items = ItemRepository::getInstance()->getInCategories($ids, $sort);

        if (isAjaxRequest()) {
            echo new CategoryView([
                "category" => $category,
                "items" => $items,
                "currencies" => $currencies
            ]);
        } else {
            echo new CommonView([
                "title" => "Sawazon - " . $category->getName(),
                "body" => new CategoryView([
                    "category" => $category,
                    "items" => $items,
                    "currencies" => $currencies
                ]),
            ]);
        }
    }

    public function browse()
    {
        $categories = CategoryRepository::getInstance()->getAllWithDepth(["depth" => "1"]);
        echo new CommonView([
            "title" => "Sawazon",
            "body" => new BrowseView([
                "categories" => $categories,
            ]),
            "scripts" => ['/assets/js/browse.js'],
        ]);
    }

    public function listCategories()
    {
        $categories = CategoryRepository::getInstance()->getAllWithDepth();

        echo new CommonView([
            "title" => "Sawazon - Categories",
            "body" => new CategoriesView([
                "categories" => $categories,
            ]),
            "scripts" => ['/assets/js/categories.js'],
        ]);
    }

    public function add()
    {
        if (isPost()) {
            $icon = new Picture();
            $icon->setResizeTo(["128", "128"]);
            if (!$icon->validate()) {
                redirect(R::getRoute("listCategories")->generate());
            }

            $category = new Category(
                post('name'),
                post('description'),
                $icon->getPictureString()
            );

            $parent = CategoryRepository::getInstance()->get(post('parentId'));

            if ($parent != null && $category->validate()) {
                CategoryRepository::getInstance()->addCategory($category, $parent->getCategoryId());
            }
        }
        redirect(R::getRoute("listCategories")->generate());
    }

    public function edit()
    {
        if (isPost()) {
            $categoryId = D::getInstance()->getMatched()->getParam("id");
            $category = CategoryRepository::getInstance()->get($categoryId);

            $icon = new Picture();
            $icon->setResizeTo(["128", "128"]);
            if (!empty($_FILES['file']['name'])) {
                if (!$icon->validate()) {
                    redirect(R::getRoute("listCategories")->generate());
                }
                $category->setIcon($icon->getPictureString());
            }

            $category->setName(post('name'));
            $category->setDescription(post('description'));

            if ($category->validate()) {
                CategoryRepository::getInstance()->update($category);
            }
        }
        redirect(R::getRoute("listCategories")->generate());
    }

    public function getIcon()
    {
        header("Content-type: image/png");
        $categoryId = D::getInstance()->getMatched()->getParam("id");
        $category = CategoryRepository::getInstance()->get($categoryId);

        $image = imagecreatefromstring($category->getIcon());
        imagepng($image);
        imagedestroy($image);
    }

    public function getChildren()
    {
        $categoryId = D::getInstance()->getMatched()->getParam("id");
        $categories = CategoryRepository::getInstance()->getChildren($categoryId);

        $arr = [];

        /** @var Category $category */
        foreach ($categories as $category) {
            $arr[] = [
                "id" => $category->getCategoryId(),
                "name" => $category->getName(),
                "description" => $category->getDescription(),
                "iconURL" => R::getRoute("getIcon")->generate(["id" => $category->getCategoryId()]),
            ];
        }

        echo json_encode($arr);
    }

    public function delete()
    {

    }

}
