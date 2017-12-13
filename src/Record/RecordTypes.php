<?php

namespace ICC\Record;

use ICC\Enum\AbstractEnum;

/**
 * Class RecordTypes
 * @package ICC
 */
class RecordTypes extends AbstractEnum
{
    const GROUP = 'Group';
    const ARTICLE = 'Article';

    /**
     * @var string
     */
    protected static $namespace = __NAMESPACE__.'\\Record\\';

    /**
     * @param string $key
     * @return mixed|string
     */
    public static function get(string $key = '')
    {
        $result = self::getConstants();

        if(!$key) {
            return $result;
        }

        if (!is_string($key)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($key) ? get_class($key) : gettype($key))
            ));
        }

        if(self::isValidKey($key)) {
            $result = self::$namespace.$key;
        }

        return $result;
    }
}