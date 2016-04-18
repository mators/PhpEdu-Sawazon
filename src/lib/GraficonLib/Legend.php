<?php

namespace lib\graficonlib;


/**
 * Skup podataka koji opisuju znacenje podataka prikazanih grafikonom.
 */
class Legend {

    /**
     * Polje svih stavki legende.
     * @var array
     */
    private $legend_items;

    /**
     * Velicina fonta
     * @var integer
     */
    private $font_size = 2;

    /**
     * Kreira novu legendu sa stavkama predanim parametrom. Ako stavke nisu
     * predane, bit ce definirane tijekom izvrsavanja programa.
     *
     * @param $legend_items array				stavke legende
     */
    public function __construct($legend_items = array()) {
        if (!is_array($legend_items)) {
            throw new \InvalidArgumentException("legend_items - array expected");
        }
        $this->legend_items = $legend_items;
    }

    /**
     * Legendi dodaje jednu novu stavku.
     *
     * @param $item LegendItem  				stavka legende
     * @return integer						    identifikator dodane stavke
     */
    public function add_item(LegendItem $item) {
        array_push($this->legend_items, $item);
        end($this->legend_items);
        return key($this->legend_items);
    }

    /**
     * Legendi dodaje proizvoljan broj stavki
     *
     * @param $items array  					stavke legende
     * @return array							polje identifikatora dodanih stavki
     */
    public function add_items($items = array()) {
        if (!is_array($items)) {
            throw new \InvalidArgumentException("items - array expected");
        }
        $ids = [];
        foreach ($items as $item) {
            array_push($ids, $this->add_item($item));
        }
        return $ids;
    }

    /**
     * Uklanja stavku legende odredjenu identifikatorom.
     *
     * @param $item_id integer  				identifikator stavke
     * @see Legend::add_item()					metoda ciji rezultat je identifikator stavke
     * @throws ItemNotFoundException
     */
    public function remove_item($item_id) {
        if (!array_key_exists($item_id, $this->legend_items)) {
            throw new ItemNotFoundException();
        }
        unset($this->legend_items[$item_id]);
    }

    /**
     * Postavlja velicinu fonta.
     * @param $size integer                     velicina fonta (1 do 5)
     */
    public function set_font_size($size) {
        if (!is_integer($size)) {
            throw new \InvalidArgumentException("size - integer expected");
        }
        if ($size < 1 || $size > 5) {
            throw new \InvalidArgumentException("Size must be a value between 1 and 5.");
        }
        $this->font_size = $size;
    }

    /**
     * Stvara prikaz legende
     * @return resource 						resurs koji sadrzi iscrtanu legendu
     */
    public function render() {
        $rows = 0;
        $maxLength = 0;
        $fontHeight = imagefontheight($this->font_size);

        // Calculate legend size
        foreach ($this->legend_items as $item) {
            $rows++;
            if ($maxLength < ($len = strlen($item->get_title()))) {
                $maxLength = $len;
            }
        }
        $width = imagefontwidth($this->font_size) * $maxLength + 2;
        $height = $fontHeight * $rows + 2;

        // Create image
        $im = imagecreate($width, $height);

        // White background and black text
        imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);

        // Write legend items
        $y = 0;
        foreach ($this->legend_items as $item) {
            $c = $item->get_color();
            $color = imagecolorallocate(
                $im,
                $c["r"],
                $c["g"],
                $c["b"]
            );
            imagestring($im, $this->font_size, 2, $y, $item->get_title(), $color);
            $y += $fontHeight;
        }

        // Border
        imagerectangle($im, 0, 0, $width - 1, $height - 1, $black);

        return $im;
    }

}
