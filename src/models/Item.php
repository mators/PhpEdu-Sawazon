<?php

namespace models;


class Item implements Model
{
    /**
     * @var int
     */
    private $itemId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $categoryId;

    /**
     * @var double
     */
    private $usdPrice;

    /**
     * @var string
     */
    private $modified;

    /**
     * @var string
     */
    private $created;

    /**
     * @var array
     */
    private $tags = [];

    private $errors = [];

    /**
     * Item constructor.
     * @param int $itemId
     * @param string $name
     * @param string $description
     * @param int $userId
     * @param int $categoryId
     * @param float $usdPrice
     * @param string $modified
     * @param string $created
     */
    public function __construct($name, $description, $userId, $categoryId, $usdPrice, $modified, $created, $itemId = null)
    {
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
        $this->usdPrice = $usdPrice;
        $this->modified = $modified;
        $this->created = $created;
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return float
     */
    public function getUsdPrice()
    {
        return $this->usdPrice;
    }

    /**
     * @param float $usdPrice
     */
    public function setUsdPrice($usdPrice)
    {
        $this->usdPrice = $usdPrice;
    }

    /**
     * @return string
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function validate()
    {
        $this->errors = [];

        if (empty($this->name)) {
            $this->errors["name"] = "Item name is required.";
        } else if (strlen($this->name) > 100) {
            $this->errors["name"] = "Item name can be up to 100 characters long.";
        }

        if (empty($this->description)) {
            $this->errors["description"] = "Item description is required.";
        } else if (strlen($this->description) > 500) {
            $this->errors["description"] = "Item description can be up to 500 characters long.";
        }

        if (empty($this->categoryId)) {
            $this->errors["category"] = "Category is required.";
        }

        if (empty($this->usdPrice) || $this->usdPrice < 0) {
            $this->errors["price"] = "Price must be positive.";
        } else if (!is_numeric($this->usdPrice)) {
            $this->errors["price"] = "Price must be a number.";
        }

        if (!empty($this->tags)) {
            if (count($this->tags) > 20) {
                $this->errors["tags"] = "Maximum number of tags is 20";
            } else {
                foreach ($this->tags as $tag) {
                    if (strlen($tag) > 30) {
                        $this->errors["tags"] = "Each tag can be maximum 30 characters long";
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
