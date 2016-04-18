<?php

namespace views;


use models\Item;
use models\Quack;
use models\Review;
use router\Router as R;

class HomeView extends AbstractView
{

    private $posts;

    private $fromCategories;

    protected function outputHTML()
    {
        ?>
        <div class='page container'>
            <div class="col-sm-offset-2 col-sm-8">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#wall" aria-controls="home" role="tab" data-toggle="tab">SawaWall</a></li>
                    <li role="presentation"><a href="#fromCategories" aria-controls="profile" role="tab" data-toggle="tab">Interested items</a></li>
                </ul>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="wall">
                                <?php if (isLoggedIn()) { ?>
                                    <form class="form-horizontal" method="post">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea type="text" class="form-control" id="quack" name="text"></textarea>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <button class="pull-right btn btn-primary" type="submit">Quack!</button>
                                    </form>
                                    <br><hr>
                                <?php } ?>
                                <?php foreach ($this->posts as $post) { ?>
                                    <div class="row">
                                        <div class="col-xs-3 col-sm-2 col-md-1">
                                            <a href="<?php echo R::getRoute("profile")->generate(["username"=> $post->getUser()->getUsername()]);?>">
                                                <img class="img img-responsive" src="<?php echo R::getRoute("profilePicture")->generate([
                                                    "username" => __($post->getUser()->getUsername()),
                                                    "size" => "sm",
                                                ])?>" onerror="this.src='/assets/img/default-avatar.jpg'"/>
                                            </a>
                                        </div>
                                        <div class="col-xs-9 col-sm-10 col-md-11">
                                            <span class="text-muted"><?php echo $post->getCreated(); ?></span>
                                            <?php if ($post instanceof Quack) { ?>
                                                <p>
                                                    <a href="<?php echo R::getRoute("profile")->generate(["username"=> __($post->getUser()->getUsername())]);?>">
                                                        <?php echo __($post->getUser()->getUsername()); ?></a>:
                                                    <?php echo at($post->getText()); ?>
                                                </p>
                                            <?php } else if ($post instanceof Review) {?>
                                                <p>
                                                    <a href="<?php echo R::getRoute("profile")->generate(["username"=> __($post->getUser()->getUsername())]);?>">
                                                        <?php echo __($post->getUser()->getUsername()); ?></a>
                                                    just wrote a review for the
                                                    <a href="<?php echo R::getRoute("showItem")->generate(["id" => $post->getItemId()])?>">
                                                        <?php echo __($post->getItem()->getName()); ?>.
                                                    </a>
                                                </p>
                                            <?php } else {?>
                                                <p>
                                                    <a href="<?php echo R::getRoute("profile")->generate(["username"=> $post->getUser()->getUsername()]);?>">
                                                        <?php echo __($post->getUser()->getUsername()); ?></a>
                                                    is now selling
                                                    <a href="<?php echo R::getRoute("showItem")->generate(["id" => $post->getItemId()])?>">
                                                        <?php echo __($post->getName()); ?>.
                                                    </a>
                                                </p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <hr>
                                <?php } ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="fromCategories">
                                <?php /** @var Item $item */
                                foreach ($this->fromCategories as $item) { ?>
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
                                            <p><?php echo strlen($item->getDescription()) < 100 ? at($item->getDescription()) : at(substr($item->getDescription(), 0, 97)) . "...";?></p>
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
                </div>
            </div>
        </div>
        <?php
    }

    public function setFromCategories($fromCategories)
    {
        $this->fromCategories = $fromCategories;
    }

    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

}
