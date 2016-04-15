<?php

namespace models;


class Currency implements Model
{
    /**
     * @var int
     */
    private $currencyId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $shortName;

    /**
     * @var double
     */
    private $coefficient;

    /**
     * Currency constructor.
     * @param int $currencyId
     * @param string $name
     * @param string $shortName
     * @param float $coefficient
     */
    public function __construct($name, $shortName, $coefficient, $currencyId = null)
    {
        $this->currencyId = $currencyId;
        $this->name = $name;
        $this->shortName = $shortName;
        $this->coefficient = $coefficient;
    }

    /**
     * @return int
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * @param int $currencyId
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;
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
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return float
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * @param float $coefficient
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;
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