<?php

namespace views;


use models\Category;

class CategoriesView extends AbstractView
{
    private $categories = [];

    private $errors = [];

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
                        <form class="form-horizontal" method="post" id="catForm" action="">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Category name" value=""/>
                                    <span class="text-danger"><?php echo element("name", $this->errors, ""); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="5" id="description" name="description"></textarea>
                                    <span class="text-danger"><?php echo element("description", $this->errors, ""); ?></span>
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

        <div class="page container center_div">
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
                            data-name="<?php echo $category->getName(); ?>"
                             data-description="<?php echo $category->getDescription(); ?>">
                            <?php echo $category->getName(); ?>
                        </a>&nbsp;
                        <button type="button" class="open-add-modal btn btn-default btn-xs" data-toggle="modal" data-target="#myModal"
                                data-parent-id="<?php echo $category->getCategoryId(); ?>">
                            <span class="glyphicon glyphicon-plus"></span>Add
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

}
