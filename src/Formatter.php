<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 08-12-17
 * Time: 19:36
 */

namespace ICC;

use ICC\Record\Condition;

/**
 * Class Formatter
 * @package ICC
 */
class Formatter implements FormatterInterface
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @param string $content
     * @return array
     */
    public function format(File $file, string $content)
    {
        $this->file = $file;

        return $this->doFormat($this->toArray($content));
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
     * @return mixed
     */
    private function setConditions($data)
    {
        $conditions = [];

        foreach ($data as $key => $line) {
            $conditions[] = new Condition($this->file->getId(), $line);
        }

        return $conditions;
    }

    /**
     * @param string $content
     * @return array
     */
    public function toArray(string $content)
    {
        if (!$lines = explode(PHP_EOL, $content)) {
            return $lines;
        }

        $firstLine = true;

        foreach ($lines as $key => $line) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }

            if ($line) {
                $lines[$key] = [];

                foreach (Condition::$fields as $field) {
                    $lines[$key][$field['key']] = trim(substr($line, $field['start'], $field['length']));
                }
            } else {
                unset($lines[$key]);
            }
        }

        return $lines;
    }
}
