<?php

namespace ICC\Enum;

/**
 * Interface EnumInterface
 * @package ICC\Enum
 */
interface EnumInterface
{
    /**
     * @param string $name
     * @param bool $strict
     * @return mixed
     */
    public static function isValidKey(string $name, $strict = false);

    /**
     * @param mixed $value
     * @param bool $strict
     * @return mixed
     */
    public static function isValidValue($value, $strict = true);
}
