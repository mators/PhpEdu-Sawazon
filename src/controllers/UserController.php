<?php

namespace controllers;

use db\ItemRepository;
use db\ReviewRepository;
use db\UserRepository;
use models\Picture;
use models\User;
use router\Router as R;
use dispatcher\DefaultDispatcher as D;
use views\AccountSettingsView;
use views\CommonView;
use views\ProfileView;


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

    public function profile()
    {
        $username = D::getInstance()->getMatched()->getParam("username");
        $user = UserRepository::getInstance()->getByUsername($username);

        if (null == $user) {
            redirect(R::getRoute("error")->generate());
        }

        $items = ItemRepository::getInstance()->getUsersItems($user->getUserID());
        $reviews = ReviewRepository::getInstance()->getAll(["user_id" => $user->getUserID()]);
        // quacks

        echo new CommonView([
            "title" => "Sawazon - ".$username,
            "body" => new ProfileView([
                "user" => $user,
                "reviews" => $reviews,
                "items" => $items
            ]),
            "scripts" => ["/assets/js/editReviewModal.js"]
        ]);
    }

    public function profilePicture()
    {
        header("Content-type: image/png");
        $username = D::getInstance()->getMatched()->getParam("username");
        $size = D::getInstance()->getMatched()->getParam("size");
        /** @var User $user */
        $user = UserRepository::getInstance()->getByUsername($username);

        if (null == $user) {
            echo "tu bi trebalo staviti defaultnu sliku :)";
        } else {
            $image = imagecreatefromstring($user->getPicture()->getPictureString());
            if ($size == "sm") {
                $image = Picture::getResized($image, 128, 128);
            }
            imagepng($image);
            imagedestroy($image);
        }
    }
}
