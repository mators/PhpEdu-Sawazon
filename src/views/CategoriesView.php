<?php

namespace views;


use models\Category;

class CategoriesView extends AbstractView
{
    private $categories = [];

    protected function outputHTML()
    {
        ?>
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" id="catForm" enctype="multipart/form-data" action="">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Category name" value=""/>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="5" id="description" name="description"></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="icon" class="col-sm-2 control-label">Icon</label>
                                <div class="col-sm-10">
                                    <img id="iconImage" src="">
                                    <input type="file" id="icon" name="file">
                                </div>
                            </div>
                            <input id="parent-cat" type="hidden" name="parentId" value="">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="postButton" type="submit" class="btn btn-primary" form="catForm"></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="page container">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                <h3>Categories</h3>

                <button type="button" class="open-add-modal btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal"
                        data-parent-id="3">
                    <span class="glyphicon glyphicon-plus"></span>Add root category
                </button>
                <br>
                <br>
                <?php
                /** @var Category $category */
                foreach($this->categories as $category) { ?>
                    <div class="row">
                        <div class="col-xs-offset-<?php echo $category->getDepth() - 1; ?> col-xs-10">
                            <a class="open-edit-modal" href="" data-toggle="modal" data-target="#myModal"
                               data-id="<?php echo $category->getCategoryId(); ?>"
                                data-name="<?php echo __($category->getName()); ?>"
                                 data-description="<?php echo __($category->getDescription()); ?>">
                                <?php echo __($category->getName()); ?>
                            </a>&nbsp;
                            <?php if ($category->getDepth() == 1) { ?>
                                <button type="button" class="open-add-modal btn btn-default btn-xs" data-toggle="modal" data-target="#myModal"
                                        data-parent-id="<?php echo $category->getCategoryId(); ?>">

                                    <span class="glyphicon glyphicon-plus"></span>Add
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

}
