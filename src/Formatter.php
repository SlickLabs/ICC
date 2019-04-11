<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 08-12-17
 * Time: 19:36
 */

namespace ICC;

use ICC\Record\AbstractRecord;
use ICC\Record\Condition;

/**
 * Class Formatter
 * @package ICC
 */
class Formatter implements FormatterInterface
{
    /**
     * @var string
     */
    protected $id;

    protected $firstLineOnly = false;

    /**
     * @param string $content
     * @return AbstractRecord[]
     */
    public function format(string $id, string $content)
    {
        $this->id = $id;

        return $this->doFormat($this->toArray($content, Condition::class));
    }

    /**
     * @param array $data
     * @return array
     */
    protected function doFormat(array $data)
    {
        $data = $this->unsetHead($data);
        return $this->setConditions($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function unsetHead($data)
    {
        if (isset($data[0])) {
            unset($data[0]);
        }

        return $data;
    }

    /**
     * @param $data
     * @return AbstractRecord[]
     */
    private function setConditions($data)
    {
        $conditions = [];

        foreach ($data as $key => $line) {
            $conditions[] = new Condition($this->id, $line);
        }

        return $conditions;
    }

    /**
     * @param string $content
     * @param string|null $record
     * @return array
     */
    public function toArray(string $content, string $record = null)
    {
        if (!$lines = explode(PHP_EOL, $content)) {
            return $lines;
        }

        $firstLine = true;
        foreach ($lines as $key => $line) {
            if ($firstLine) {
                $firstLine = false;

                if (!$this->firstLineOnly) {
                    unset($lines[$key]);
                    continue;
                }
            }

            if ($line) {
                $lines[$key] = [];

                foreach ($record::getFields() as $field) {
                    $lines[$key][$field['key']] = trim(substr($line, $field['start'], $field['length']));
                }

                if ($this->firstLineOnly) {
                    return $lines[$key];
                }

            } else {
                unset($lines[$key]);
            }
            
            if (isset($lines[$key]) && !is_array($lines[$key])) {
                unset($lines[$key]);
            }
        }

        return $lines;
    }

    public function toString(string $content, string $record = null)
    {
        if (!$lines = explode(PHP_EOL, $content)) {
            return $lines;
        }

        if ($this->firstLineOnly) {
            return $lines[0];
        }

        $string = '';
        foreach ($lines as $line) {
            $string .= $line . "\r\n";
        }
        return $string;
    }

    public function setFirstLineOnly(bool $firstLineOnly)
    {
        $this->firstLineOnly = $firstLineOnly;

        return $this;
    }
}
