<?php

namespace lib\graficonlib;


/**
 * Apstrakcija grafikona koji podatke prestavlja tockama koje su
 * povezane linijama.
 */
class LineChart extends AxesChart {

    public function render() {
        $im = imagecreate($this->width, $this->height);

        $fontHeight = imagefontheight($this->font_size);

        // White background
        imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);

        // Border
        $this->draw_border($im);

        // Title
        $this->draw_title($im);

        // Axes
        $this->draw_axes($im);

        // Data
        $maxValue = $this->max_value();
        imagestring($im, $this->font_size, 2, 0.05 * $this->height + $fontHeight, $maxValue, $black);

        $titlesWritten = false;
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
            $currentX = 0.05 * $this->width + $collectionWidth / 2;
            $lastX = $lastY = null;

            foreach ($items as $item) {
                $currentY = 0.85 * ($this->height - ($item->get_value() / $maxValue * $this->height))
                    + $fontHeight + 0.05 * $this->height;

                if ($lastX != null && $lastY != null) {
                    imageline($im, $currentX, $currentY, $lastX, $lastY, $color);
                }
                if (!$titlesWritten) {
                    $this->draw_collection_name($im, $item, $currentX);
                }

                imagefilledellipse($im, $currentX, $currentY, 5, 5, $color);

                $lastX = $currentX;
                $lastY = $currentY;

                $currentX += $collectionWidth;
            }
            $titlesWritten = true;
        }

        // Legend
        $this->draw_legend($im);

        return $im;
    }

}
