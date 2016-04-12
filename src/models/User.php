<?php

namespace models;


/**
 * Application user.
 */
class User implements Model
{

    /**
     * @var int
     */
    private $userID;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $eMail;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $birthday;

    /**
     * @var Picture
     */
    private $picture;

    /**
     * @var string
     */
    private $groupId;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * User constructor.
     * @param $firstName string
     * @param $lastName string
     * @param $eMail string
     * @param $username string
     * @param $password string
     * @param $birthday string
     * @param $groupId string
     * @param $userID int|null
     */
    public function __construct($firstName, $lastName, $eMail, $username, $password, $birthday, $groupId, $userID = null)
    {
        $this->userID = $userID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->eMail = $eMail;
        $this->username = $username;
        $this->password = $password;
        $this->birthday = $birthday;
        $this->groupId = $groupId;
    }

    /**
     * @return string
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param string $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * @return Picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param Picture $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return int
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEMail()
    {
        return $this->eMail;
    }

    /**
     * @param string $eMail
     */
    public function setEMail($eMail)
    {
        $this->eMail = $eMail;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * User validation. Returns true if everything is ok. Otherwise it returns false
     * and fills errors array with validation error messages.
     * @return bool
     */
    public function validate()
    {
        $this->errors = [];

        if (empty($this->firstName)) {
            $this->errors["firstname"] = "First name is required.";
        } else if (strlen($this->firstName) > 40) {
            $this->errors["firstname"] = "First name can be up to 40 characters long.";
        }

        if (empty($this->lastName)) {
            $this->errors["lastname"] = "Last name is required.";
        } else if (strlen($this->lastName) > 40) {
            $this->errors["lastname"] = "Last name can be up to 40 characters long.";
        }

        if (empty($this->username)) {
            $this->errors["username"] = "Username is required.";
        } else if (strlen($this->username) > 40) {
            $this->errors["username"] = "Username can be up to 40 characters long.";
        }

        if (empty($this->eMail)) {
            $this->errors["email"] = "E-mail is required.";
        } else if (preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$/i", $this->eMail) != 1) {
            $this->errors["email"] = "Invalid E-mail format.";
        }

        if (empty($this->password)) {
            $this->errors["password"] = "Password is required.";
        }

        $date = \DateTime::createFromFormat("Y-m-d", $this->birthday);
        if (!$date) {
            $this->errors["birthday"] = "Birthday is required.";
        } else if ($date > new \DateTime()) {
            $this->errors["birthday"] = "Birthday can't be in future.";
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
