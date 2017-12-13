<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 08-12-17
 * Time: 18:14
 */

namespace ICC;

use ICC\Exception\FileException;

/**
 * Class File
 * @package ICC
 */
class File
{
    /**
     * @var
     */
    protected $path;

    /**
     * File constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->setPath($path);
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * Returns whether or not the given file path exists
     *
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->getPath());
    }

    /**
     * Returns the file contents
     *
     * @return bool|null|string
     */
    public function getContents()
    {
        if (!$this->exists()) {
            throw new FileException(sprintf(
                'The requested file does not exist on path %s',
                $this->getPath()
            ));
        }

        return file_get_contents($this->getPath());
    }
}
