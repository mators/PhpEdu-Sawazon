<?php

namespace views;

use router\Router as R;


class LoginView extends AbstractView
{

    private $error = "";

    protected function outputHTML()
    {
        ?>
        <div class="page container">
            <div class="col-md-offset-2 col-md-8">
                <h3>Login</h3>

                <form class="form-horizontal" method="post" action="<?php echo R::getRoute("login")->generate(); ?>">
                    <div class="form-group">
                        <label for="username" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                   value="<?php echo post("username"); ?>"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            <span class="text-danger"><?php echo $this->error; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    public function setError($error)
    {
        $this->error = $error;
    }

}