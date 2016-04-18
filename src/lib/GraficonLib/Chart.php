<?php

namespace lib\graficonlib;


/**
 * Apstrakcija grafikona
 */
abstract class Chart {

    /**
     * Polje koje sadrzi boje koje je potrebno koristiti prilikom crtanja
     * podataka. Svaki element polja odgovara jednoj kolekciji podataka.
     * @var array
     */
    protected $colors;

    /**
     * Polje u koje se spremaju kolekcije podataka koje treba
     * nacrtati.
     * @var array
     */
    protected $data_collections;

    /**
     * Legenda koja se koristi prilikom crtanja grafikona kako
     * bi objasnila nacrtane podatke.
     * @var Legend
     */
    protected $legend;

    /**
     * Pozicija legende na grafikonu.
     * @var array
     */
    protected $legendPosition;

    /**
     * Zeljena visina grafikona prema kojoj je potrebno skalirati sve
     * velicina kako bi svi podaci stali unutar zadanih dimenzija.
     * @var number
     */
    protected $height;

    /**
     * Zeljena sirina grafikona prema kojoj je potrebno skalirati sve
     * velicina kako bi svi podaci stali unutar zadanih dimenzija.
     * @var number
     */
    protected $width;

    /**
     * Naslov grafikona koji se prikazuje ispod podataka koje je potrebno
     * nacrtati.
     * @var string
     */
    protected $title;

    /**
     * Velicina fonta
     * @var int
     */
    protected $font_size = 5;

    /**
     * Definira novi grafikon prema predanim dimenzijama i naslovu.
     *
     * @param $title string 					naslov grafikona
     * @param $height integer					visina grafikona
     * @param $width integer					sirina grafikona
     */
    public function __construct($title = '', $height = 500, $width = 500) {
        if (!is_string($title)) {
            throw new \InvalidArgumentException("title - string expected");
        }
        if (!is_integer($height) || $height <= 0 ) {
            throw new \InvalidArgumentException("height - positive integer expected");
        }
        if (!is_integer($width) || $width <= 0 ) {
            throw new \InvalidArgumentException("width - positive integer expected");
        }
        $this->title = $title;
        $this->height = $height;
        $this->width = $width;
        $this->data_collections = [];
        $this->colors = [];
        $this->legendPosition = [];
    }

    /**
     * Prihvaća novu kolekciju podataka koju je potrebno nacrtati i vraca identifikator
     * kolekcija unutar grafikona.
     *
     * @param $collection DataCollection		kolekcija s podacima koje je potrebno iscrtati
     * @return integer  						identifikator dodane kolekcije
     */
    public function add_data(DataCollection $collection) {
        array_push($this->data_collections, $collection);
        end($this->data_collections);
        return key($this->data_collections);
    }

    /**
     * Definira boju kojom treba nacrtati kolekciju podataka.
     *
     * @param $red integer					    udio komponente crvene boje
     * @param $green integer				    udio komponente zelene boje
     * @param $blue integer					    udio komponente plave boje
     * @param $data_id integer				    identifikator kolekcije za koju se definira boja
     * @throws ItemNotFoundException
     */
    public function color_data($red, $green, $blue, $data_id) {
        if (!array_key_exists($data_id, $this->data_collections)) {
            throw new ItemNotFoundException();
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
        $this->colors[$data_id] = [
            "r" => $red,
            "g" => $green,
            "b" => $blue
        ];
    }

    /**
     * Uklanja kolekciju podataka s grafikona, pri cemu je kolekcija odredjena
     * identifikatorom koji vraca metoda add_data.
     *
     * @param $data_id integer  				identifikator kolekcije
     * @see Chart::add_data()					metoda koja dodaje kolekciju i vraca njezin id
     * @throws ItemNotFoundException
     */
    public function remove_data($data_id) {
        if (!array_key_exists($data_id, $this->data_collections)) {
            throw new ItemNotFoundException();
        }
        unset($this->data_collections[$data_id]);
        unset($this->colors[$data_id]);
    }

    /**
     * Obavlja crtanje kompletnog grafikona.
     * @return resource 						resurs koji predstavlja grafikon
     */
    public abstract function render();

    /**
     * Uklanja legendu s grafikona.
     */
    public function unset_legend() {
        $this->legend = null;
    }

    /**
     * Grafikonu pridruzuje legendu.
     *
     * @param $legend Legend					legenda koja se dodjeljuje grafikonu
     * @param $x number 						pozicija na x-osi zadana u pikselima
     * @param $y number 						pozicija na y-osi zadana u pikselima
     */
    public function set_legend(Legend $legend, $x, $y) {
        if (!is_integer($x) || $x > $this->width) {
            throw new \InvalidArgumentException("x - integer inside chart boundaries expected");
        }
        if (!is_integer($y) || $y > $this->height) {
            throw new \InvalidArgumentException("y - integer inside chart boundaries expected");
        }
        $this->legend = $legend;
        $this->legendPosition = ["x" => $x, "y" => $y];
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
     * Izracunava najvecu vrijednost medu svim kolekcijama podataka.
     *
     * @return int
     */
    protected function max_value() {
        $max = 0;
        foreach ($this->data_collections as $data_collection) {
            foreach ($data_collection->get_items() as $item) {
                if ($max < $item->get_value()) {
                    $max = $item->get_value();
                }
            }
        }
        return $max;
    }

    /**
     * Na danu sliku crta naslov na sredinu gore.
     *
     * @param $im resource                      slika na koju se crta
     */
    protected function draw_title(&$im) {
        $titleWidth = imagefontwidth($this->font_size) * strlen($this->title);

        $xTitle = ($this->width - $titleWidth) / 2;
        imagestring($im, $this->font_size, $xTitle, 2, $this->title, imagecolorallocate($im, 0, 0, 0));
    }

    /**
     * Na danu sliku crta crni rub debljine 1.
     *
     * @param $im resource                      slika na koju se crta
     */
    protected function draw_border(&$im) {
        imagerectangle($im, 0, 0, $this->width - 1, $this->height - 1, imagecolorallocate($im, 0, 0, 0));
    }

    /**
     * Na danu sliku crta legendu.
     *
     * @param $im resource                      slika na koju se crta
     */
    protected function draw_legend(&$im) {
        if ($this->legend != null) {
            $src = $this->legend->render();
            imagecopy($im, $src, $this->legendPosition["x"], $this->legendPosition["y"], 0, 0, imagesx($src), imagesy($src));
        }
    }

}
