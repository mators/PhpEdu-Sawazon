<?php

namespace views;

use router\Router as R;


class CommonView extends AbstractView
{

    private $body;

    private $title;

    private $scripts = [];

    protected function outputHTML()
    {
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="description" content="Sawazon">
            <meta name="author" content="Matija Oršolić, orsolic.matija@gmail.com">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title><?php echo $this->title; ?></title>

            <!-- Bootstrap Core CSS -->
            <link href="/assets/css/bootstrap.min.css" rel="stylesheet">

            <!-- Custom CSS -->
            <link href="/assets/css/style.css" rel="stylesheet">

        </head>
        <body>
            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href=<?php echo R::getRoute("index")->generate(); ?>>Sawazon</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="navbar-ex1-collapse">
                        <ul class="nav navbar-nav">
                            <li><a href=<?php echo R::getRoute("index")->generate(); ?>>Home</a></li>
                            <?php if(isLoggedIn()) { ?>
                            <?php } else { ?>
                            <?php } ?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <?php if(isLoggedIn()) { ?>
                                <li><a href=<?php echo R::getRoute("logout")->generate(); ?>>Logout</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Settings <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-header">User settings</li>
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <!--                                    <li role="separator" class="divider"></li>-->
                                        <?php if(isAdmin()) { ?>
                                            <li class="dropdown-header">Admin settings</li>
                                            <li><a href="<?php echo R::getRoute("listCategories")->generate(); ?>">Categories</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } else { ?>
                                <li><a href="<?php echo R::getRoute("register")->generate(); ?>">Register</a></li>
                                <li><a href="<?php echo R::getRoute("login")->generate(); ?>">Login</a></li>
                            <?php } ?>
                        </ul>
                        <form class="navbar-form navbar-right" role="search">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search by tag..">
                            </div>
                            <button type="submit" class="btn btn-default">Search</button>
                        </form>

                    </div>
                    <!-- /.navbar-collapse -->
                </div>
                <!-- /.container -->
            </nav>

            <?php
                if (!empty($this->body)) {
                    echo $this->body;
                }
            ?>

            <!-- JQuery -->
            <script src="/assets/js/jquery-2.2.1.min.js"></script>

            <!-- Bootstrap Core JavaScript -->
            <script src="/assets/js/bootstrap.min.js"></script>

            <?php foreach ($this->scripts as $script) { ?>
                <script src="<?php echo $script; ?>"></script>
            <?php } ?>

        </body>
        </html>
        <?php
    }

    public function setScripts($scripts)
    {
        $this->scripts = $scripts;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

}
