<?php

namespace models;


class Quack implements Model
{
    /**
     * @var int
     */
    private $quackId;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $created;

    /**
     * @var string
     */
    private $modified;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var User
     */
    private $user;

    /**
     * Quack constructor.
     * @param int $quackId
     * @param int $userId
     * @param string $text
     * @param string $created
     * @param string $modified
     */
    public function __construct($userId, $text, $created, $modified, $quackId = null)
    {
        $this->quackId = $quackId;
        $this->userId = $userId;
        $this->text = $text;
        $this->created = $created;
        $this->modified = $modified;
    }

    /**
     * @return int
     */
    public function getQuackId()
    {
        return $this->quackId;
    }

    /**
     * @param int $quackId
     */
    public function setQuackId($quackId)
    {
        $this->quackId = $quackId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getModified()
    {
        return $this->modified;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function validate()
    {
        $this->errors = [];

        if (empty($this->text)) {
            $this->errors["text"] = "Text is required";
        } else if (strlen($this->text) > 200) {
            $this->errors["text"] = "Text can't be more than 200 characters long.";
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
