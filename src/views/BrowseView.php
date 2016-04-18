<?php

namespace views;


use models\Category;
use router\Router as R;

class BrowseView extends AbstractView
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
                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                            <div class="thumbnail">
                                <a class="getChildren" data-id="<?php echo $category->getCategoryId(); ?>" href="#childrenWell">
                                    <img id="smallerImg" src="<?php echo R::getRoute("getIcon")->generate(["id" => $category->getCategoryId()]); ?>">
                                    <div class="caption">
                                        <h3><?php echo __($category->getName()); ?></h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div id="childrenWell" class="well">
                <div id="childrenDiv" class="row">
                    <a href="#category" id="template" class="cat media col-xs-6 col-sm-4 col-md-2" hidden>
                        <img class="pull-left img-responsive child_img" src="">
                        <div class="media-body">
                            <h4 class="media-heading"></h4>
                        </div>
                    </a>
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
