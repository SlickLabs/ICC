<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 08-12-17
 * Time: 19:46
 */

namespace ICC;

use ICC\Record\AbstractRecord;

/**
 * Interface FormatterInterface
 * @package ICC
 */
interface FormatterInterface
{
    /**
     * @param string $id
     * @param string $content
     * @return AbstractRecord[]
     */
    public function format(string $id, string $content);

    /**
     * @param string $content
     * @return mixed
     */
    public function toArray(string $content, string $record = null);

    /**
     * @param bool $firstLineOnly
     * @return mixed
     */
    public function setFirstLineOnly(bool $firstLineOnly);
}