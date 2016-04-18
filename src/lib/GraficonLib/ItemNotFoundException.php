<?php

namespace lib\graficonlib;


/**
 * Iznimka se baca kada klijent pokusa s platna, iz grafikona, kolekcije ili legende ukloniti
 * podatak koji zapravo nije ni dodan.
 *
 * @see Canvas::remove_chart()					metoda koja obavlja uklanjanje grafikona s platna
 * @see Chart::remove_data()					metoda koja obavlja uklanjanje kolekcije iz grafikona
 * @see DataCollection::remove_item				metoda koja obavlja uklanjanje stavke iz kolekcije
 * @see Legend::remove_item()					metoda koja obavlja uklanjanje stavke iz legende
 */
class ItemNotFoundException extends \Exception {}
