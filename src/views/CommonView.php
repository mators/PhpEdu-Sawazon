<?php

namespace views;

use router\Router as R;


class CommonView extends AbstractView
{

    private $body;

    private $title;

    protected function outputHTML()
    {
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="description" content="Sawazon">
            <meta name="author" content="Matija Oršolić, orsolic.matija@gmail.com">

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
                            <?php } else { ?>
                                <li><a href=<?php echo R::getRoute("register")->generate(); ?>>Register</a></li>
                                <li><a href=<?php echo R::getRoute("login")->generate(); ?>>Login</a></li>
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

        </body>
        </html>
        <?php
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
