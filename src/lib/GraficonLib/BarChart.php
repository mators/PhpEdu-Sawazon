<?php

namespace lib\graficonlib;


/**
 * Apstrakcija grafikona koji podatke predstavlja vertikalnim pravokutnicima
 * cija visina ovisi o pojedinom podatku.
 */
class BarChart extends AxesChart {

    public function render() {
        $im = imagecreate($this->width, $this->height);

        $fontHeight = imagefontheight($this->font_size);

        // White background
        imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);

        // Border
        //$this->draw_border($im);

        // Title
        $this->draw_title($im);

        // Data
        $maxValue = $this->max_value();
        imagestring($im, $this->font_size, 2, 0.05 * $this->height + $fontHeight, $maxValue, $black);

        $titlesWritten = false;
        $numberOfCollections = count($this->data_collections);

        $n = 0;
        foreach ($this->data_collections as $key => $data_collection) {

            // Set color
            $color = imagecolorallocate(
                $im,
                $this->colors[$key]["r"],
                $this->colors[$key]["g"],
                $this->colors[$key]["b"]
            );

            $items = $data_collection->get_items();
            $collectionWidth = 0.95 * $this->width / count($items);
            $barWidth = $collectionWidth / $numberOfCollections;
            $currentX = 0.05 * $this->width + $barWidth * $n;

            foreach ($items as $item) {
                $currentY = 0.85 * ($this->height - ($item->get_value() / $maxValue * $this->height))
                    + $fontHeight + 0.05 * $this->height;

                if (!$titlesWritten) {
                    $this->draw_collection_name($im, $item, $currentX + $collectionWidth / 2);
                }

                $leftX = $currentX - ($titlesWritten ? 8 : 0) * $n;
                imagefilledrectangle($im, $leftX, $currentY, $leftX + $barWidth, 0.95 * $this->height, $color);
                imagerectangle($im, $leftX, 0.95 * $this->height, $leftX + $barWidth, $currentY, $black);

                $currentX += $collectionWidth;
            }
            $titlesWritten = true;
            $n++;
        }

        // Axes
        $this->draw_axes($im);

        // Legend
        $this->draw_legend($im);

        return $im;
    }

}
