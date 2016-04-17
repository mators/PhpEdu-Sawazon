<?php

namespace views;

use router\Router as R;


class AccountSettingsView extends AbstractView
{
    private $success = "";

    protected function outputHTML()
    {
        ?>
        <div class="page container">
            <?php if (!empty($this->success)) { ?>
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        <div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4>Success!</h4>
                            <p><?php echo $this->success; ?></p>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <h3>Account settings</h3>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="changePass">
                            <p>Change your password.</p>
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">Old password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="oldPass" name="oldPass" placeholder="Password"/>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">New password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password2" class="col-sm-2 control-label">Repeat password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Password">
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <input type="hidden" name="f" value="passForm">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-default">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="changePic">
                            <p>Change your profile picture.</p>
                            <form class="form-horizontal" method="post"
                                  action=""
                                  enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="file" class="col-sm-2 control-label">Picture</label>
                                    <div class="col-sm-10">
                                        <input type="file" id="file" name="file">
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <input type="hidden" name="f" value="picForm">
                                <img src="" alt="">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-default">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <nav class="">
                        <ul class="pagination">
                            <li class="active"><a href="#changePass" aria-controls="changePass" role="tab" data-toggle="tab">Change password</a></li>
                            <li><a href="#changePic" aria-controls="changePic" role="tab" data-toggle="tab">Change profile picture</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <?php
    }

    public function setSuccess($success) {
        $this->success = $success;
    }

}
