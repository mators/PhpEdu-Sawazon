<?php

namespace views;

use models\Category;
use router\Router as R;


class RegisterView extends AbstractView
{

    private $errors = [];

    private $categories = [];

    public function outputHTML()
    {
        ?>
        <div class="page container">
            <div class="col-md-offset-2 col-md-8">
                <h3>Register</h3>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="home">

                        <form class="form-horizontal" method="post" id="regForm"
                              action="<?php echo R::getRoute("register")->generate(); ?>"
                              enctype="multipart/form-data">

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
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="categories">
                        <p>Select the categories you would like to follow. You can change this later.</p>
                        <?php
                        /** @var Category $category */
                        foreach($this->categories as $category) {
                            if ($category->getDepth() == 1) { ?>
                                <h5><?php echo $category->getName(); ?></h5>
                            <?php } else { ?>
                                <div class="checkbox">
                                    <label>
                                        <input form="regForm" type="checkbox" name="categories[]"
                                          value="<?php echo $category->getCategoryId(); ?>"> <?php echo $category->getName(); ?>
                                    </label>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="photo">
                        <p>Choose your profile picture.</p>
                        <div class="form-group">
                            <label for="file" class="col-sm-2 control-label">Photo</label>
                            <div class="col-sm-10">
                                <input  form="regForm" type="file" id="file" name="file">
                                <span class="text-danger"><?php echo element("file", $this->errors, ""); ?></span>
                            </div>
                        </div>
                        <button form="regForm" type="submit" class="btn btn-default">Register</button>
                    </div>
                </div>
                <nav class="">
                    <ul class="pagination">
                        <li class="active" aria-label="Previous"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Basic info</a></li>
                        <li><a href="#categories" aria-controls="categories" role="tab" data-toggle="tab">Categories</a></li>
                        <li><a href="#photo" aria-controls="photo" role="tab" data-toggle="tab">Profile picture</a></li>
                    </ul>
                </nav>
            </div>
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
