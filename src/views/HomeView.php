<?php

namespace views;


use models\Category;
use router\Router as R;

class HomeView extends AbstractView
{
    /**
     * @var array
     */
    private $categories;

    protected function outputHTML()
    {
        ?>
        <div class='page container'>
            <h3>Browse categories</h3>
            <div class="well">
                <div class="row">
                    <?php
                    /** @var Category $category */
                    foreach ($this->categories as $category) { ?>
                        <div class="col-xs-6 col-sm-4 col-md-3">
                            <div class="thumbnail">
                                <a class="getChildren" data-id="<?php echo $category->getCategoryId(); ?>" href="#">
                                    <img id="smallerImg" src="<?php echo R::getRoute("getIcon")->generate(["id" => $category->getCategoryId()]); ?>" alt="...">
                                    <div class="caption">
                                        <h3><?php echo $category->getName(); ?></h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div id="at-well" class="well" hidden>
                <div id="ajaxtest" class="row">

                </div>
            </div>
        </div>
        <?php
    }

    /**
     * @param array $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

}
