<?php

namespace lib\graficonlib;


/**
 * Sucelje koje razredi implementiraju kako bi naglasili
 * da ih je moguce spremiti u datoteku.
 */
interface Saveable {

    /**
     * Obavlja spremanje u datoteku
     * @param $file string  					naziv datoteke u koju je potrebno spremiti sadrzaj
     */
    public function save($file);
}
