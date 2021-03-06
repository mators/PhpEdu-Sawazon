<?php

namespace db;

use models\Picture;
use models\User;


class UserRepository extends Repository
{

    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new UserRepository();
        }
        return self::$instance;
    }

    /**
     * @param $username
     * @return User|null
     */
    public function getByUsername($username)
    {
        return parent::getSingleOrNull([ "username" => $username]);
    }

    /**
     * @param $username
     * @param $password
     * @return User|null
     */
    public function getByUsernameAndPassword($username, $password)
    {
        return parent::getSingleOrNull([ "username" => $username, "password" => sha1($password) ]);
    }

    public function save(User $user)
    {
        return parent::save([
            "first_name" => $user->getFirstName(),
            "last_name" => $user->getLastName(),
            "email" => $user->getEMail(),
            "username" => $user->getUsername(),
            "password" => $user->getPassword(),
            "birthday" => $user->getBirthday(),
            "photo" => $user->getPicture()->getPictureString(),
            "group_id" => $user->getGroupId()
        ]);
    }

    public function update(User $user)
    {
        parent::update($user->getUserID(), [
            "first_name" => $user->getFirstName(),
            "last_name" => $user->getLastName(),
            "email" => $user->getEMail(),
            "username" => $user->getUsername(),
            "password" => $user->getPassword(),
            "birthday" => $user->getBirthday(),
            "photo" => $user->getPicture()->getPictureString(),
            "group_id" => $user->getGroupId()
        ]);
    }

    public function getFollowing($userId)
    {
        $sql = "SELECT user_id FROM user_followers WHERE follower_id=?";
        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute([$userId]);
        $ids = [];
        foreach ($statement as $row) {
            $ids[] = $row->user_id;
        }
        return $ids;
    }

    public function isFollowing($userId, $followerId)
    {
        $sql = "SELECT COUNT(*) AS result FROM user_followers WHERE user_id=? AND follower_id=?";
        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute([$userId, $followerId]);
        return $statement->fetch()->result > 0;
    }

    protected function getTable()
    {
        return "users";
    }

    protected function modelFromData($data)
    {
        $user = new User(
            $data->first_name,
            $data->last_name,
            $data->email,
            $data->username,
            $data->password,
            $data->birthday,
            $data->group_id,
            $data->id
        );
        $user->setPicture(new Picture($data->photo));
        return $user;
    }

}
