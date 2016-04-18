<?php

namespace models;


class Comment implements Model
{

    /**
     * @var int
     */
    private $commentId;

    /**
     * @var int
     */
    private $quackId;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $created;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * Comment constructor.
     * @param int $commentId
     * @param int $quackId
     * @param string $text
     * @param string $created
     */
    public function __construct($quackId, $text, $created, $commentId)
    {
        $this->commentId = $commentId;
        $this->quackId = $quackId;
        $this->text = $text;
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * @param int $commentId
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * @return int
     */
    public function getQuackId()
    {
        return $this->quackId;
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

    public function validate()
    {
        $this->errors = [];

        if (empty($this->text)) {
            $this->errors["text"] = "Text is required.";
        } else if (strlen($this->text) > 200) {
            $this->errors["text"] = "Comment can't be more than 200 characters long.";
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
