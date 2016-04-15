<?php

namespace models;


class Category implements Model
{
    /**
     * @var int
     */
    private $categoryId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var
     */
    private $icon;

    private $depth;

    private $errors = [];

    /**
     * Category constructor.
     * @param int $categoryId
     * @param string $name
     * @param string $description
     * @param $icon
     */
    public function __construct($name, $description, $icon, $categoryId = null)
    {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->description = $description;
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param mixed $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
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
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function validate()
    {
        $this->errors = [];

        if (empty($this->name)) {
            $this->errors["name"] = "Category name is required.";
        } else if (strlen($this->name) > 40) {
            $this->errors["name"] = "Category name can be up to 40 characters long.";
        }

        if (empty($this->description)) {
            $this->errors["description"] = "Category description is required.";
        } else if (strlen($this->description) > 500) {
            $this->errors["description"] = "Category description can be up to 500 characters long.";
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
