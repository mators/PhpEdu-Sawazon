<?php

namespace controllers;


use db\CategoryRepository;
use models\Category;
use views\CategoriesView;
use views\CommonView;
use router\Router as R;
use dispatcher\DefaultDispatcher as D;

class CategoryController
{

    public function listCategories()
    {
        $categories = CategoryRepository::getInstance()->getAllWithDepth();

        echo new CommonView([
            "title" => "Sawazon - Categories",
            "body" => new CategoriesView([
                "categories" => $categories
            ]),
            "scripts" => ['/assets/js/categories.js']
        ]);
    }

    public function add()
    {
        if (isPost()) {
            $category = new Category(
                post('name'),
                post('description'),
                null
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

            $category->setName(post('name'));
            $category->setDescription(post('description'));

            if ($category->validate()) {
                CategoryRepository::getInstance()->update($category);
            }
        }
        redirect(R::getRoute("listCategories")->generate());
    }

    public function delete()
    {

    }

}
