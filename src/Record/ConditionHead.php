<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 25-09-18
 * Time: 11:51
 */

namespace ICC\Record;

class ConditionHead extends \ICC\Record\AbstractRecord
{
    /**
     * @var array
     */
    public static $fields = [
        0 => [
            'key' => 'SupplierGLN',
            'name' => 'GLN leverancier',
            'start' => 0,
            'length' => 13
        ],
        2 => [
            'key' => 'CustomerId',
            'name' => 'Debiteurnummer klant',
            'start' => 20,
            'length' => 20
        ],
        3 => [
            'key' => 'CreatedAt',
            'name' => 'Datum aanmaken bestand',
            'start' => 40,
            'length' => 8
        ],
        4 => [
            'key' => 'LineCount',
            'name' => 'Aantal conditierecords',
            'start' => 48,
            'length' => 6
        ],
        5 => [
            'key' => 'Version',
            'name' => 'Versieindeling conditiebericht',
            'start' => 54,
            'length' => 5
        ],
        6 => [
            'key' => 'SupplierName',
            'name' => 'Naam van de leverancier',
            'start' => 59,
            'length' => 35
        ],
        7 => [
            'key' => 'CustomerGLN',
            'name' => 'GLN klant',
            'start' => 107,
            'length' => 35
        ],
    ];

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function getValue(string $key, $default = null)
    {
        return (isset($this->values[$key]))? $this->values[$key] : $default;
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function setValue(string $key, $value = null)
    {
        return $this->values[$key] = $value;
    }

    /**
     * @return array
     */
    public static function getFields()
    {
        return self::$fields;
    }

}