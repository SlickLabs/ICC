<?php

namespace ICC\Record;

/**
 * Class Condition
 * @package ICC\Record
 */
class Condition extends AbstractRecord
{
    /**
     * @var array
     */
    public static $fields = [
        0 => [
            'key' => 'DiscountGroup',
            'name' => 'Kortingsgroep',
            'start' => 0,
            'length' => 20,
        ],
        1 => [
            'key' => 'TradeItemId',
            'name' => 'Artikelnummer',
            'start' => 20,
            'length' => 20,
        ],
        2 => [
            'key' => 'Description',
            'name' => 'Omschrijving',
            'start' => 40,
            'length' => 50,
        ],
        3 => [
            'key' => 'Discount1',
            'name' => 'Korting1',
            'start' => 90,
            'length' => 5,
        ],
        4 => [
            'key' => 'Discount2',
            'name' => 'Korting2',
            'start' => 95,
            'length' => 5,
        ],
        5 => [
            'key' => 'Discount3',
            'name' => 'Korting3',
            'start' => 100,
            'length' => 5,
        ],
        6 => [
            'key' => 'NetPrice',
            'name' => 'NettoPrijs',
            'start' => 105,
            'length' => 9,
        ],
        7 => [
            'key' => 'StartDate',
            'name' => 'Ingangsdatum',
            'start' => 114,
            'length' => 8,
        ],
        8 => [
            'key' => 'EndDate',
            'name' => 'Einddatum',
            'start' => 122,
            'length' => 8,
        ],
    ];

    /**
     * @var string
     */
    protected $type = 'Condition';

    /**
     * @var integer
     */
    protected $fileId;

    /**
     * Condition constructor.
     * @param array $values
     */
    public function __construct($fileId, array $values)
    {
        $this->fileId = $fileId;
        $this->key = 'condition';
        $this->title = 'Conditions';

        parent::__construct($values);
    }

    /**
     * @return integer
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        if (null !== $this->values['DiscountGroup']) {
            return 'group';
        } else if (null !== $this->values['TradeItemId']) {
            return 'single';
        }

        return null;
    }

    /**
     * @return null
     */
    public function getKey()
    {
        if (null !== $this->values['DiscountGroup']) {
            return $this->values['DiscountGroup'];
        } else if (null !== $this->values['TradeItemId']) {
            return $this->values['TradeItemId'];
        }

        return null;
    }

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
    public function setValue(string $key, $value)
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

    /**
     * @return string
     */
    public function toString()
    {
        $string = '';
        $values = $this->getValues();

        foreach (self::$fields as $field) {

            $value = '';
            if ($values[$field['key']]) {
                $value = $values[$field['key']];
            }

            $string .= str_pad($value, $field['length'], ' ', STR_PAD_RIGHT);
        }

        return $string;
    }

    public function getTotalDiscountPercentage()
    {
        return (float) $this->getValue('Discount1') + $this->getValue('Discount2') + $this->getValue('Discount3');
    }
}
