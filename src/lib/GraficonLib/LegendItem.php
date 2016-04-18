<?php

namespace lib\graficonlib;


/**
 * Predstavlja jednu stavku legende.
 */
class LegendItem {

    /**
     * Naslov stavke
     * @var string
     */
    private $title;

    private $color;

    /**
     * Stvara novu stavku sadrzaja jednakog vrijednosti argumenta.
     * @param $title string					    naslov stavke
     * @param $red integer
     * @param $green integer
     * @param $blue integer
     */
    public function __construct($title, $red = 0, $green = 0, $blue = 0) {
        if (!is_string($title)) {
            throw new \InvalidArgumentException("title - string expected");
        }
        if (!is_integer($red) || $red < 0 || $red > 255) {
            throw new \InvalidArgumentException("red - integer between 0 and 255 expected");
        }
        if (!is_integer($green) || $green < 0 || $green > 255) {
            throw new \InvalidArgumentException("green - integer between 0 and 255 expected");
        }
        if (!is_integer($blue) || $blue < 0 || $blue > 255) {
            throw new \InvalidArgumentException("blue - integer between 0 and 255 expected");
        }
        $this->title = $title;
        $this->color = [
            "r" => $red,
            "g" => $green,
            "b" => $blue
        ];
    }

    /**
     * Vraca naslov stavke legende.
     * @return string							naslov stavke
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Vraca boju stavke legende.
     * @return array                            boja stavke
     */
    public function get_color() {
        return $this->color;
    }
}
