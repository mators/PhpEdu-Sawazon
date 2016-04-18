<?php

namespace lib\graficonlib;


/**
 * Apstrakcija podatka stavke koju je potrebno nacrtati.
 */
class DataCollectionItem {

    /**
     * Vrijednost stavke.
     * @var number
     */
    private $value;

    /**
     * Naziv stavke.
     * @var string
     */
    private $title;

    /**
     * Stvara novu podatkovnu stavku prema primljenim podacima.
     * @param number $value                     vrijednost stavke
     * @param string $title                     naslov stavke
     */
    public function __construct($value, $title = "") {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("value - number expected");
        }
        if (!is_string($title)) {
            throw new \InvalidArgumentException("title - string expected");
        }
        $this->value = $value;
        $this->title = $title;
    }

    /**
     * Vraca vrijednost.
     * @return int|number|string
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Vraca naslov.
     * @return string
     */
    public function get_title() {
        return $this->title;
    }
}
