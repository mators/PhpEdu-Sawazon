<?php

namespace controllers;

use views\CommonView;
use views\LoginView;
use views\RegisterView;
use models\User;
use db\UserRepository;
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
                    "email" => $user->getEMail()
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

        if (isPost()) {

            $user = new User(
                post("firstname"),
                post("lastname"),
                post("email"),
                post("username"),
                sha1(post("password")),
                post("birthday")
            );

            $errors = [];

            if (UserRepository::getInstance()->getByUsername(post("username")) != null) {
                $errors["username"] = "Username is taken.";
            }
            if (post("password") != post("password2")) {
                $errors["password2"] = "Passwords don't match.";
            }

            if ($user->validate() && empty($errors)) {
                UserRepository::getInstance()->save($user);
                redirect(R::getRoute("login")->generate());
            }

            echo new CommonView([
                "title" => "Sawazon - Register",
                "body" => new RegisterView([
                    "errors" => array_merge($user->getErrors(), $errors)
                ])
            ]);

        } else {
            echo new CommonView([
                "title" => "Sawazon - Register",
                "body" => new RegisterView()
            ]);
        }
    }

}
