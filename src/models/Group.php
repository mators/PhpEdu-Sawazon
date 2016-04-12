<?php

namespace models;


class Group implements Model
{
    /**
     * @var int
     */
    private $groupId;

    /**
     * @var string
     */
    private $name;

    /**
     * Group constructor.
     * @param int $groupId
     * @param string $name
     */
    public function __construct($name, $groupId = null)
    {
        $this->groupId = $groupId;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param int $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
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

    public function validate()
    {
        // TODO: Implement validate() method.
    }

    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }

}
