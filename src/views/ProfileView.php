<?php

namespace views;

use models\Item;
use models\Review;
use models\User;
use router\Router as R;


class ProfileView extends AbstractView
{
    /**
     * @var User
     */
    private $user;

    private $reviews = [];

    private $items = [];

    protected function outputHTML()
    {
        ?>
        <div id="review-form-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Edit review</h4>
                    </div>
                    <div class="review-modal modal-body">
                        <form id="reviewForm" class="form-horizontal" method="post" action="">
                            <div class="form-group">
                                <label for="grade" class="col-sm-2 control-label">Rating</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="grade" name="grade" min="1" max="5" step="1"/>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="text" class="col-sm-2 control-label">Text</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="5" id="text" name="text"></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="postButton" type="submit" class="btn btn-primary" form="reviewForm">Edit review</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="page container">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3><?php echo $this->user->getUsername(); ?></h3>
                            <div class="col-xs-3 col-md-12 thumbnail">
                                <div class="image">
                                    <img src="<?php echo R::getRoute("profilePicture")->generate([
                                        "username" => $this->user->getUsername(),
                                        "size" => "or",
                                    ])?>" onerror="this.src='/assets/img/default-avatar.jpg'" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#wall" aria-controls="wall" role="tab" data-toggle="tab">
                                <?php echo $this->user->getUsername(); ?>'s items
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab">
                                <?php echo $this->user->getUsername(); ?>'s reviews
                            </a>
                        </li>
                    </ul>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="wall">
                                    <?php /** @var Item $item */
                                    foreach ($this->items as $item) {
                                        if (isCurrentUserId($item->getUserID())) { ?>
                                            <a class="pull-right btn btn-primary"
                                                href="<?php echo R::getRoute("editItem")->generate(["id" => $item->getItemId()]); ?>">
                                                Edit
                                            </a>
                                        <?php } ?>
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
                                <div role="tabpanel" class="tab-pane" id="reviews">
                                    <?php /** @var Review $review */
                                    foreach ($this->reviews as $review) {
                                        if (isCurrentUserId($review->getUserID())) { ?>
                                            <button class="open-edit-modal pull-right btn btn-primary"
                                                data-toggle="modal" data-target="#review-form-modal"
                                                    data-grade="<?php echo $review->getGrade();?>"
                                                        data-text="<?php echo $review->getText();?>"
                                                            data-id="<?php echo $review->getItemId();?>"
                                                                data-rev-id="<?php echo $review->getReviewId();?>">
                                                Edit
                                            </button>
                                        <?php } ?>
                                        <p>Rating:
                                            <?php for($i = 0; $i < $review->getGrade(); ++$i) { ?>
                                                <span class="glyphicon glyphicon-star"></span>
                                            <?php } ?>
                                            <?php for($i = 0; $i < 5 - $review->getGrade(); ++$i) { ?>
                                                <span class="glyphicon glyphicon-star-empty"></span>
                                            <?php } ?>
                                        </p>
                                        <p>
                                            User:
                                            <a href="<?php echo R::getRoute("profile")->generate(["username" => $this->user->getUsername()]); ?>">
                                                <?php echo $this->user->getUsername(); ?>
                                            </a>, <span class="text-muted"><?php echo $review->getCreated(); ?></span>
                                        </p>
                                        <div class="well well-sm">
                                            <?php echo $review->getText(); ?>
                                        </div>
                                        <hr>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

}
