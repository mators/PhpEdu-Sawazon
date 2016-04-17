<?php

namespace views;

use models\Category;
use models\Currency;use models\Item;
use router\Router as R;


class CategoryView extends AbstractView
{

    /**
     * @var Category
     */
    private $category;

    private $currencies;

    private $items = [];

    protected function outputHTML()
    {
        if (!isAjaxRequest()) { ?>
            <div class="page container">
        <?php } ?>

        <div id="category">
            <h3><?php echo $this->category->getName(); ?></h3>
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" role="form" method="get"
                                action="<?php echo R::getRoute("viewCategory")->generate(["id" => $this->category->getCategoryId()]); ?>">
                                <div class="form-group">
                                    <label for="sort"> <strong>Sort by:</strong> </label>
                                    <select class="form-control" id="sort" name="sort">
                                        <option value="0" <?php if (get("sort") && get("sort")=="0") { echo "selected"; }?>>
                                            Date - newest first
                                        </option>
                                        <option value="1" <?php if (get("sort") && get("sort")=="1") { echo "selected"; }?>>
                                            Date - oldest first
                                        </option>
                                        <option value="2" <?php if (get("sort") && get("sort")=="2") { echo "selected"; }?>>
                                            Price - lowest first
                                        </option>
                                        <option value="3" <?php if (get("sort") && get("sort")=="3") { echo "selected"; }?>>
                                            Price - highest first
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="currency"> <strong>Currency:</strong> </label>
                                    <select class="form-control" id="currency" name="currency">
                                        <?php /* @var $currency Currency */
                                        foreach ($this->currencies as $currency) { ?>
                                            <option value="<?php echo $currency->getCurrencyId() . "\"";
                                            if ($currency->getShortName() == chosenCurrency()["short"]) { echo " selected"; } ?>>
                                                <?php echo $currency->getShortName(); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div id="items" class="panel-body">
                            <?php /** @var Item $item */
                            foreach ($this->items as $item) { ?>
                                <div class="media">
                                    <div class="media-left">
                                        <a href="<?php echo R::getRoute("showItem")->generate(["id" => $item->getItemId()]);?>">
                                            <object data="<?php echo R::getRoute("itemFirstPicture")->generate([
                                                        "id" => $item->getItemId(),
                                                        "size" => "sm"
                                                    ]);?>" type="image/png">
                                                <img src="/assets/img/default-image.jpg">
                                            </object>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <a href="<?php echo R::getRoute("showItem")->generate(["id" => $item->getItemId()]);?>">
                                            <h4 class="media-heading"><?php echo __($item->getName());?></h4>
                                        </a>
                                        <p><?php echo strlen($item->getDescription()) < 100 ? __($item->getDescription()) : __(substr($item->getDescription(), 0, 97)) . "...";?></p>
                                        <p class="text-muted">Date created: <?php echo $item->getCreated();?></p>
                                        <p class="pull-right"><strong>Price: </strong>
                                            <?php echo round($item->getUsdPrice() * chosenCurrency()["coefficient"], 2)." ".chosenCurrency()["short"];?>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <strong>Description:</strong>
                            <p><?php echo $this->category->getDescription(); ?></p>
                            <strong>Number of items:</strong>
                            <p><?php echo count($this->items); ?></p>
                            <strong>Ten most expensive items:</strong>
                            <p></p>
                            <strong>Ten cheapest items:</strong>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!isAjaxRequest()) { ?>
            </div>
        <?php }
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public  function setCurrencies($currencies)
    {
        $this->currencies = $currencies;
    }

}
