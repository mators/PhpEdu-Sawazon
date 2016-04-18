<?php

namespace views;

use models\Item;
use models\Picture;
use models\User;
use router\Router as R;


class ItemView extends AbstractView
{

    /**
     * @var Item
     */
    private $item;

    /**
     * @var  User
     */
    private $seller;

    private $usersReview = [];

    private $reviews = [];

    private $pictures = [];

    /**
     * @var int
     */
    private $views;

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

        <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="my-carousel" class="carousel slide" data-ride="carousel" data-interval="false">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#my-carousel" data-slide-to="0" class="active"></li>
                                <?php /** @var Picture $picture */
                                for ($i = 1; $i < count($this->pictures); ++$i) { ?>
                                    <li data-target="#my-carousel" data-slide-to="<?php echo $i;?>"></li>
                                <?php } ?>
                            </ol>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
                                <?php $first = true; /** @var Picture $picture */
                                foreach ($this->pictures as $picture) { ?>
                                    <div class="item <?php if ($first) { echo "active";$first = false; }?>">
                                        <img src="<?php echo R::getRoute("itemPicture")->generate(["id" => $picture->getPictureId()]); ?>" alt="">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- Controls -->
                            <a class="left carousel-control" href="#my-carousel" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#my-carousel" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="page container">
            <h3><?php echo __($this->item->getName()); ?></h3>
            <div class="col-md-8">
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#item" aria-controls="home" role="tab" data-toggle="tab">Item info</a></li>
                        <li role="presentation"><a href="#reviews" aria-controls="profile" role="tab" data-toggle="tab">User reviews</a></li>
                    </ul>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="item">

                                    <div class="pull-right">
                                        <object data="<?php echo R::getRoute("itemFirstPicture")->generate([
                                            "id" => $this->item->getItemId(),
                                            "size" => "sm",
                                        ]);?>" type="image/png" <?php if (!empty($this->pictures)) { ?> data-toggle="modal" data-target="#myModal"<?php } ?>>
                                            <img src="/assets/img/default-image.jpg">
                                        </object>
                                    </div>
                                    <strong>Date created: </strong>
                                    <span class="text-muted"><?php echo $this->item->getCreated(); ?></span>
                                    <strong>Date modified: </strong>
                                    <span class="text-muted"><?php echo $this->item->getModified(); ?></span>
                                    <br><hr>
                                    <strong>Description:</strong>
                                    <p><?php echo at($this->item->getDescription()); ?></p>
                                    <br><hr>
                                    <span class="glyphicon glyphicon-tags"></span>     <?php echo __(implode(" ", $this->item->getTags())); ?>
                                    <hr>
                                    <h4 class="pull-right text-success">
                                        Price: <?php echo round($this->item->getUsdPrice() * chosenCurrency()["coefficient"], 2)." ".chosenCurrency()["short"]; ?>
                                    </h4>

                                </div>
                                <div role="tabpanel" class="tab-pane" id="reviews">
                                    <?php if (isLoggedIn() && !isCurrentUserId($this->seller->getUserID())) { ?>
                                        <form class="form-horizontal" method="post"
                                              action="<?php echo R::getRoute("addReview")->generate(["id" => $this->item->getItemId()]); ?>">
                                            <div class="form-group">
                                                <label for="grade" class="col-sm-2 control-label">Rating</label>
                                                <div class="col-sm-10">
                                                    <input type="number" class="form-control" id="grade" name="grade" min="1" max="5" step="1"/>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="reviewText" class="col-sm-2 control-label">Text</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" rows="5" id="reviewText" name="text"></textarea>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-default">Post review</button>
                                                </div>
                                            </div>
                                        </form>
                                        <hr>
                                    <?php }
                                    for ($j = 0; $j < count($this->reviews); ++$j) {
                                        if (isCurrentUserId($this->usersReview[$j]->getUserID())) { ?>
                                            <button class="open-edit-modal pull-right btn btn-primary"
                                                data-toggle="modal" data-target="#review-form-modal"
                                                 data-grade="<?php echo $this->reviews[$j]->getGrade();?>"
                                                  data-text="<?php echo __($this->reviews[$j]->getText());?>"
                                                   data-id="<?php echo $this->item->getItemId();?>"
                                                    data-rev-id="<?php echo $this->reviews[$j]->getReviewId();?>">
                                                Edit
                                            </button>
                                        <?php } ?>
                                        <p>Rating:
                                            <?php for($i = 0; $i < $this->reviews[$j]->getGrade(); ++$i) { ?>
                                                <span class="glyphicon glyphicon-star"></span>
                                            <?php } ?>
                                            <?php for($i = 0; $i < 5 - $this->reviews[$j]->getGrade(); ++$i) { ?>
                                                <span class="glyphicon glyphicon-star-empty"></span>
                                            <?php } ?>
                                        </p>
                                        <p>
                                            User:
                                            <a href="<?php echo R::getRoute("profile")->generate(["username" => $this->usersReview[$j]->getUsername()]); ?>">
                                                <?php echo __($this->usersReview[$j]->getUsername()); ?>
                                            </a>, <span class="text-muted"><?php echo $this->reviews[$j]->getCreated(); ?></span>
                                        </p>
                                        <div class="well well-sm">
                                            <?php echo __($this->reviews[$j]->getText()); ?>
                                        </div>
                                        <hr>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            <strong>Seller: </strong>
                            <a href="<?php echo R::getRoute("profile")->generate(["username" => $this->seller->getUsername()]); ?>">
                                <?php echo __($this->seller->getUsername()); ?>
                            </a>
                        </p>
                        <p>
                            <strong>Contact: </strong>
                            <a href="mailto:<?php echo __($this->seller->getEMail()); ?>?Subject=Sawazon-<?php echo __($this->item->getName()); ?>" target="_top">
                                <?php echo __($this->seller->getEMail()); ?>
                            </a>
                        </p>
                        <p>
                            <span class="glyphicon glyphicon-eye-open"></span>
                            <strong><?php echo $this->views; ?></strong>
                        </p>
                        <strong>Views last week:</strong>
                        <img class="img img-responsive" src="<?php echo R::getRoute("weekViewsChart")->generate(["id" => $this->item->getItemId()]); ?>"
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function setViews($views)
    {
        $this->views = $views;
    }

    public function setUsersReview($usersReview)
    {
        $this->usersReview = $usersReview;
    }

    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    public function setSeller($seller)
    {
        $this->seller = $seller;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function setPictures($pictures)
    {
        $this->pictures = $pictures;
    }

}
