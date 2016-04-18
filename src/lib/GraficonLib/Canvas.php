<?php

namespace lib\graficonlib;


/**
 * Razred koji predstavlja "platno" na koje je moguce crtati razlicite
 * grafikone i to vise njih istovremeno.
 */
class Canvas implements Saveable {

    /**
     * Polje u koje se spremaju grafikoni koje je potrebno
     * nacrtati.
     * @var array
     */
    private $charts = array();

    /**
     * Pozicije grafikona na platnu.
     * @var array
     */
    private $positions = array();

    /**
     * Sirina platna.
     * @var int
     */
    private $width;

    /**
     * Visina platna
     * @var int
     */
    private $height;

    public function __construct($width = 1500, $height = 500) {
        if (!is_integer($width) || $width <= 0) {
            throw new \InvalidArgumentException("width - positive integer expected");
        }
        if (!is_integer($height) || $height <= 0) {
            throw new \InvalidArgumentException("height - positive integer expected");
        }
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Dodaje grafikon na platno na poziciju (x,y).
     *
     * @param Chart $chart
     * @param integer $x						pozicija na x-osi
     * @param integer $y						pozicija na y-osi
     * @return integer						    identifikator dodanog grafikona
     */
    public function add_chart(Chart $chart, $x, $y) {
        if (!is_integer($x) || !is_integer($y)) {
            throw new \InvalidArgumentException("x and y - integer expected");
        }
        if ($x > $this->width || $y > $this->height) {
            throw new \InvalidArgumentException("x and y must be inside canvas boundaries");
        }
        array_push($this->charts, $chart);
        end($this->charts);
        $key = key($this->charts);
        $this->positions[$key] = ["x" => $x, "y" => $y];
        return $key;
    }

    /**
     * S platna uklanja grafikon odredjen predanim identifikatorom.
     *
     * @param integer $chart_id				    identifikator grafikona
     * @see Canvas::add_chart()					metoda ciji rezultat je identifikator grafikona
     * @throws ItemNotFoundException
     */
    public function remove_chart($chart_id) {
        if (!array_key_exists($chart_id, $this->charts)) {
            throw new ItemNotFoundException("There is no chart with the given id");
        }
        unset($this->charts[$chart_id]);
        unset($this->positions[$chart_id]);
    }

    /**
     * Stvara sliku grafikona i vraca je klijentu koji onda moze
     * s njom napraviti sto zeli.
     *
     * @return resource
     */
    public function render() {
        $im = imagecreate($this->width, $this->height);

        // White background
        imagecolorallocate($im, 255, 255, 255);

        foreach ($this->charts as $key => $chart) {
            $src = $chart->render();
            imagecopy($im, $src, $this->positions[$key]["x"], $this->positions[$key]["y"], 0, 0, imagesx($src), imagesy($src));
        }

        return $im;
    }

    /**
     * Sprema platno u datoteku.
     * @param string $file
     */
    public function save($file) {
        imagepng($this->render(), $file);
    }
}
