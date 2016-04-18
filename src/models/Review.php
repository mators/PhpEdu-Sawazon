<?php

namespace models;


class Review implements Model
{

    /**
     * @var int
     */
    private $reviewId;

    /**
     * @var int
     */
    private $grade;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $created;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $itemId;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var User
     */
    private $user;

    /**
     * @var Item
     */
    private $item;

    /**
     * Review constructor.
     * @param int $reviewId
     * @param int $grade
     * @param string $text
     * @param int $userId
     * @param int $itemId
     * @param string $created
     */
    public function __construct($grade, $text, $userId, $itemId, $created, $reviewId = null)
    {
        $this->reviewId = $reviewId;
        $this->grade = $grade;
        $this->text = $text;
        $this->userId = $userId;
        $this->itemId = $itemId;
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function getReviewId()
    {
        return $this->reviewId;
    }

    /**
     * @param int $reviewId
     */
    public function setReviewId($reviewId)
    {
        $this->reviewId = $reviewId;
    }

    /**
     * @return int
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param int $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
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
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
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
     * @return int
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param int $itemId
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }

    public function validate()
    {
        $this->errors = [];

        if (!is_numeric($this->grade) || $this->grade < 1 || $this->grade > 5) {
            $this->errors["grade"] = "Grade must be a value between 1 and 5.";
        }

        if (empty($this->text)) {
            $this->errors["text"] = "Review text is required.";
        } else if (strlen($this->text) > 1000) {
            $this->errors["text"] = "Review can't have more than 1000 characters.";
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
