<?php

namespace views;


use models\Category;
use models\Currency;
use models\Item;

class ItemFormView extends AbstractView
{

    private $errors = [];

    private $categories = [];

    private $currencies = [];

    /**
     * @var Item
     */
    private $item;

    private $title;

    private $action;

    protected function outputHTML()
    {
        ?>
        <div class="page container">
            <div class="col-md-offset-2 col-md-8">
                <h3><?php echo $this->title; ?></h3>
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Item name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Item name"
                                   value="<?php echo $this->item->getName(); ?>"
                            />
                            <span class="text-danger"><?php echo element("name", $this->errors, ""); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Item description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" id="description" name="description"><?php echo $this->item->getDescription(); ?></textarea>
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="category" name="categoryId">
                                <?php $first = true;
                                /* @var $category Category */
                                foreach ($this->categories as $category) {
                                    if ($category->getDepth() == 1) {
                                        if (!$first) { ?>
                                            </optgroup>
                                            <?php $first = false; } ?>
                                        <optgroup label="<?php echo $category->getName(); ?>">
                                    <?php } else { ?>
                                        <option value="<?php echo $category->getCategoryId(); ?>"
                                            <?php if ($category->getCategoryId() == $this->item->getCategoryId()) { echo "selected"; } ?>>
                                            <?php echo $category->getName(); ?>
                                        </option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo element("category", $this->errors, ""); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-xs-12 col-sm-2 control-label">Price</label>
                        <div class="col-xs-7">
                            <input type="number" step="0.01" class="form-control" value="<?php echo $this->item->getUsdPrice(); ?>" id="price" name="price">
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" id="currency" name="currency">
                                <?php /* @var $currency Currency */
                                foreach ($this->currencies as $currency) { ?>
                                    <option value="<?php echo $currency->getCurrencyId() . "\"";
                                    if ($currency->getShortName() == "USD") { echo " selected"; } ?>>
                                        <?php echo $currency->getShortName(); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="files" class="col-sm-2 control-label">Pictures</label>
                        <div class="col-sm-10">
                            <img id="iconImage" src="">
                            <input type="file" id="files" name="file[]" multiple="multiple">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="button btn btn-default"><?php echo $this->title; ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    public function setCurrencies($currencies)
    {
        $this->currencies = $currencies;
    }

}
