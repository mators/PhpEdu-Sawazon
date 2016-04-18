<?php

namespace lib\graficonlib;


/**
 * Apstrakcija grafikona koji podatke predstavlja krugom unutar kojeg
 * svaki podatak zauzima dio povrsine.
 */
class PieChart extends Chart {

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

        $pieData = $this->calculatePieData();
        $threeDHeight = 0.1 * $this->height;
        $centerW = $this->width / 2;
        $centerH = $this->height / 2 + $fontHeight - $threeDHeight;

        // 3D Shade
        foreach (array_slice($this->data_collections, 0, -1, true) as $key => $data_collection) {

            // Set color
            $color = imagecolorallocate(
                $im,
                $this->colors[$key]["r"] * 0.25,
                $this->colors[$key]["g"] * 0.25,
                $this->colors[$key]["b"] * 0.25
            );

            for ($i = 0; $i <= $threeDHeight; $i++) {
                imagefilledarc(
                    $im,
                    $centerW,
                    $centerH + $i,
                    0.7 * $this->width,
                    0.35 * $this->height,
                    $pieData[$key]["angle"]["start"],
                    $pieData[$key]["angle"]["end"],
                    $color,
                    IMG_ARC_PIE
                );
            }
        }

        // Data
        foreach ($this->data_collections as $key => $data_collection) {

            // Set color
            $color = imagecolorallocate(
                $im,
                $this->colors[$key]["r"],
                $this->colors[$key]["g"],
                $this->colors[$key]["b"]
            );

            imagefilledarc(
                $im,
                $centerW,
                $centerH,
                0.7 * $this->width,
                0.35 * $this->height,
                $pieData[$key]["angle"]["start"],
                $pieData[$key]["angle"]["end"],
                $color,
                IMG_ARC_PIE
            );
        }

        // Legend
        $this->draw_legend($im);

        return $im;
    }

    /**
     * Calculates angles and percentages for each piece of pie.
     *
     * @return array
     */
    private function calculatePieData() {
        $sum = 0;
        foreach ($this->data_collections as $data_collection) {
            foreach ($data_collection->get_items() as $item) {
                $sum += $item->get_value();
            }
        }

        $data = [];
        $startAngle = 0;
        foreach ($this->data_collections as $key => $data_collection) {
            foreach ($data_collection->get_items() as $item) {
                $per = $item->get_value() / $sum;
                $data[$key]["percentage"] = $per;
                $data[$key]["angle"] = [
                    "start" => $startAngle,
                    "end" => 360 * $per + $startAngle
                ];
            }
            $startAngle = $data[$key]["angle"]["end"];
        }

        return $data;
    }

}
