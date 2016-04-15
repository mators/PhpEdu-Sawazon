<?php

namespace db;


use models\Currency;

class CurrencyRepository extends Repository
{
    private static $instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new CurrencyRepository();
        }
        return self::$instance;
    }

    protected function getTable()
    {
        return "exchange_rates";
    }

    protected function modelFromData($data)
    {
        return new Currency(
            $data->name,
            $data->short_name,
            $data->coefficient,
            $data->id
        );
    }

}
