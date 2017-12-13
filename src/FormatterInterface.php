<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 08-12-17
 * Time: 19:46
 */

namespace ICC;

/**
 * Interface FormatterInterface
 * @package ICC
 */
interface FormatterInterface
{
    /**
     * @param string $content
     * @return mixed
     */
    public function format(string $content);

    /**
     * @param string $content
     * @return mixed
     */
    public function toArray(string $content);
}