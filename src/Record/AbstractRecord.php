<?php

namespace ICC\Record;

/**
 * Class AbstractRecord
 * @package ICC\Record
 */
abstract class AbstractRecord implements RecordInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * AbstractRecord constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->setValues($values);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->getTitle(),
            'values' => $this->getValues()
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $data
     * @return array
     */
    public function setValues(array $data)
    {
        $this->values = [];
        foreach ($this->getFields() as $key => $field) {
            if (isset($data[$field['key']])) {
                $format = (isset($field['format'])) ? $field['format'] : null;

                $this->values[$field['key']] = $this->formatValue($format, $data[$field['key']]);
            }
        }
        return $this->values;
    }

    /**
     * @param $format
     * @param $value
     * @return \DateTime|float|int|null
     */
    public function formatValue($format, $value)
    {
        if (!$value) {
            return null;
        }

        switch ($format) {
            case 'integer':
                $value = (int)$value;
                break;
            case 'date':
                $value = new \DateTime($value);
                break;
            case 'currency':
                $value = ($value / 100);
                break;
        }

        return $value;
    }
}
