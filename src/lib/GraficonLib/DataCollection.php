<?php

namespace lib\graficonlib;


/**
 * Kolekcija podataka koje treba vizualizirati grafikonom.
 */
class DataCollection {

    protected $data_collection_items = [];

    /**
     * Dodaje novi podatak u kolekciju
     *
     * @param $item DataCollectionItem  		novi podatak kolekcije
     * @return integer   						identifikator dodanog podatka
     */
    public function add_item(DataCollectionItem $item) {
        array_push($this->data_collection_items, $item);
        end($this->data_collection_items);
        return key($this->data_collection_items);
    }

    /**
     * Dodaje proizvoljan broj podataka u kolekciju
     *
     * @param $items array      				novi podaci kolekcije
     * @return array							polje identifikatora dodanh podataka
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
     * Uklanja stavku kolekcije odredjenu identifikatorom.
     *
     * @param $item_id integer  				identifikator stavke
     * @see DataCollection::add_item()			metoda ciji rezultat je identifikator stavke
     * @throws ItemNotFoundException
     */
    public function remove_item($item_id) {
        if (!array_key_exists($item_id, $this->data_collection_items)) {
            throw new ItemNotFoundException();
        }
        unset($this->data_collection_items[$item_id]);
    }

    /**
     * Dohvaca sve podatke koji se nalaze u kolekciji.
     * @return array							podaci kolekcije
     */
    public function get_items() {
        return $this->data_collection_items;
    }

}
