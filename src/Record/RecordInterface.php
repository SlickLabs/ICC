<?php

namespace ICC\Record;

/**
 * Interface RecordInterface
 * @package ICC\RecordType
 */
interface RecordInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return mixed
     */
    public static function getFields();

    /**
     * @return mixed
     */
    public function getValues();

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getValue(string $key, $default = null);

    /**
     * @param string $key
     * @param null $value
     * @return mixed
     */
    public function setValue(string $key, $value);
}
