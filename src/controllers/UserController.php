<?php

namespace controllers;

use db\UserRepository;
use models\Picture;
use router\Router as R;
use dispatcher\DefaultDispatcher as D;
use views\AccountSettingsView;
use views\CommonView;


class UserController implements Controller
{
    public function settings()
    {
        $username = D::getInstance()->getMatched()->getParam("username");
        $success = "";
        if (!isCurrentUser($username)) {
            redirect(R::getRoute("error")->generate());
        }

        if (isPost()) {
            $user = UserRepository::getInstance()->getByUsername($username);

            if (post("f") == "passForm") {
                $errors = [];

                if (sha1(post("oldPass")) != $user->getPassword()) {
                    $errors["oldPass"] = "Incorrect password";
                }
                if (empty(post("password"))) {
                    $errors["password"] = "New password is required";
                }
                if (post("password") != post("password2")) {
                    $errors["password2"] = "Passwords don't match";
                }
                if (empty($errors)) {
                    $user->setPassword(sha1(post("password")));
                    UserRepository::getInstance()->update($user);
                    $success = "Password successfully changed";
                }
            } else {
                $picture = new Picture();
                if ($picture->validate()) {
                    $user->setPicture($picture);
                    UserRepository::getInstance()->update($user);
                    $success = "Profile picture successfully changed";
                }
            }
        }
        echo new CommonView([
            "title" => "Sawazon - Settings",
            "body" => new AccountSettingsView([
                "success" => $success
            ])
        ]);
    }
}
