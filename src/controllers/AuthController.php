<?php

namespace controllers;

use db\GroupRepository;
use models\Picture;
use views\CommonView;
use views\LoginView;
use views\RegisterView;
use models\User;
use db\UserRepository;
use db\CategoryRepository;
use router\Router as R;


class AuthController implements Controller
{

    public function login()
    {
        if (isLoggedIn()) {
            redirect(R::getRoute("index")->generate());
        }

        if (isPost()) {
            $user = UserRepository::getInstance()->getByUsernameAndPassword(post("username"), post("password"));
            $group = GroupRepository::getInstance()->get($user->getGroupId());

            if (null === $user) {
                $error = "Invalid username or password.";

                echo new CommonView([
                    "title" => "Sawazon - Login",
                    "body" => new LoginView(["error" => $error])
                ]);
            } else {

                $_SESSION["user"] = [
                    "id" => $user->getUserID(),
                    "username" => $user->getUsername(),
                    "email" => $user->getEMail(),
                    "group" => $group->getName()
                ];

                redirect(R::getRoute("index")->generate());
            }

        } else {
            echo new CommonView([
                "title" => "Sawazon - Login",
                "body" => new LoginView()
            ]);
        }
    }

    public function logout()
    {
        if (isLoggedIn()) {
            unset($_SESSION["user"]);
        }
        redirect(R::getRoute("index")->generate());
    }

    public function register()
    {
        if (isLoggedIn()) {
            redirect(R::getRoute("index")->generate());
        }

        $categories = CategoryRepository::getInstance()->getAllWithDepth();

        if (isPost()) {

            $errors = [];

            $picture = new Picture();

            $user = new User(
                post("firstname"),
                post("lastname"),
                post("email"),
                post("username"),
                sha1(post("password")),
                post("birthday"),
                null
            );
            $user->setPicture($picture);

            if (UserRepository::getInstance()->getByUsername(post("username")) != null) {
                $errors["username"] = "Username is taken.";
            }
            if (post("password") != post("password2")) {
                $errors["password2"] = "Passwords don't match.";
            }

            $user->validate();
            $picture->validate();

            $errors = array_merge($user->getErrors(), $picture->getErrors(), $errors);

            if (empty($errors)) {
                UserRepository::getInstance()->save($user);
                redirect(R::getRoute("login")->generate());
            }

            echo new CommonView([
                "title" => "Sawazon - Register",
                "body" => new RegisterView([
                    "errors" => $errors,
                    "categories" => $categories
                ])
            ]);

        } else {
            echo new CommonView([
                "title" => "Sawazon - Register",
                "body" => new RegisterView([
                    "categories" => $categories
                ])
            ]);
        }
    }

}
