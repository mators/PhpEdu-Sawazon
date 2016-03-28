<?php

namespace views;

use router\Router as R;


class RegisterView extends AbstractView
{

    private $errors = [];

    public function outputHTML()
    {
        ?>
        <div class="page container center_div">
            <h3>Register</h3>

            <form class="form-horizontal" method="post" action="<?php echo R::getRoute("register")->generate(); ?>">
                <div class="form-group">
                    <label for="firstName" class="col-sm-2 control-label">First name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="firstName" name="firstname" placeholder="First name"
                               value="<?php echo post("firstname"); ?>"
                        />
                        <span class="text-danger"><?php echo element("firstname", $this->errors, ""); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastName" class="col-sm-2 control-label">Last name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="lastName" name="lastname" placeholder="Last name"
                               value="<?php echo post("lastname"); ?>"
                        />
                        <span class="text-danger"><?php echo element("lastname", $this->errors, ""); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                               value="<?php echo post("username"); ?>"
                        />
                        <span class="text-danger"><?php echo element("username", $this->errors, ""); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                               value="<?php echo post("email"); ?>"
                        />
                        <span class="text-danger"><?php echo element("email", $this->errors, ""); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="birthday" class="col-sm-2 control-label">Date of birth</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="birthday" name="birthday"
                               value="<?php echo post("birthday"); ?>"
                        />
                        <span class="text-danger"><?php echo element("birthday", $this->errors, ""); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                        <span class="text-danger"><?php echo element("password", $this->errors, ""); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password2" class="col-sm-2 control-label">Repeat password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Password">
                        <span class="text-danger"><?php echo element("password2", $this->errors, ""); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Register</button>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

}
