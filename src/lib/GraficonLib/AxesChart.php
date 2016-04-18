<?php

namespace lib\graficonlib;


/**
 * Predstavlja grafikon sa x i y koordinatama.
 */
abstract class AxesChart extends Chart {

    /**
     * Crta koordinate na primljenu sliku.
     *
     * @param $im resource                           slika na koju se crta
     */
    protected function draw_axes(&$im) {
        $xL = 0.05 * $this->width;
        $xR = 0.99 * $this->width;
        $yU = 0.05 * $this->height;
        $yD = 0.95 * $this->height;
        $black = imagecolorallocate($im, 0, 0, 0);
        $fontHeight = imagefontheight($this->font_size);

        imageline($im, $xL, $yD, $xR, $yD, $black);
        imageline($im, $xL, $yD + 1, $xR, $yD + 1, $black);
        imageline($im, $xL, $yD, $xL, $yU + $fontHeight, $black);
        imageline($im, $xL + 1, $yD, $xL + 1, $yU + $fontHeight, $black);
    }

    /**
     * Crta ime stavke podatka na sliku
     *
     * @param $im resource                          slika na koju se crta
     * @param $item DataCollectionItem              stavka cije se ime crta
     * @param $x integer                            x koordinata na koju se crta
     */
    protected function draw_collection_name(&$im, DataCollectionItem $item, $x) {
        $title = $item->get_title();
        $titleW = imagefontwidth($this->font_size) * strlen($title);
        $titleX = $x - $titleW / 2;
        imagestring($im, $this->font_size, $titleX, 0.95 * $this->height, $title, imagecolorallocate($im, 0, 0, 0));
    }

}
