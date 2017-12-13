<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 08-12-17
 * Time: 21:30
 */

namespace ICC;

use ICC\Record\RecordInterface;

/**
 * Class Combiner
 * @package ICC
 */
class Combiner
{
    /**
     * @var array[ConditionInterface]
     */
    protected $baseFile;

    /**
     * @var ReaderInterface
     */
    protected $reader;

    /**
     * @var
     */
    protected $head;

    /**
     * @var array
     */
    protected $matches = [];

    /**
     * @var array
     */
    protected $baseMap = [
        'group' => [],
        'single' => []
    ];

    /**
     * Combiner constructor.
     * @param ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param array $files
     * @return File $combinedFile
     */
    public function combine(array $files = [])
    {
        $first = true;

        foreach ($files as $filePath) {

            $fileResult = $this->reader->read(new File($filePath));

            if (true === $first) {
                $first = false;
                $this->setBaseFile($fileResult);
                $this->setHead($this->reader->getHead(new File($filePath)));
                continue;
            }

            foreach ($fileResult as $condition) {
                if (!$condition instanceof RecordInterface) {
                    continue;
                }

                if (isset($this->baseMap[$condition->getType()][$condition->getKey()])) {
                    $matchKey = $this->baseMap[$condition->getType()][$condition->getKey()];
                    $baseCondition = $this->baseFile[$matchKey];

                    $baseDiscount = $baseCondition->getValue('Discount1');

                    $discount = $condition->getValue('Discount1');

                    // Discount is 1% more than base discount
                    if (substr($baseDiscount, 0, 2) <= substr($discount, 0, 2) -1) {

                        // Add match
                        $this->matches[] = [
                            'matchKey' => $matchKey,
                            'from' => $baseCondition,
                            'to' => $condition
                        ];

                        // Replace current base condition
                        $this->baseFile[$matchKey] = $condition;
                    }
                }
            }
        }
    }

    /**
     * @param array $fileResult
     */
    public function setBaseFile(array $fileResult)
    {
        foreach ($fileResult as $key => $condition) {
            $this->baseMap[$condition->getType()][$condition->getKey()] = $key;
        }

        $this->baseFile = $fileResult;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $string = $this->head;

        foreach ($this->baseFile as $condition) {
            $string .= $condition->toString() . PHP_EOL;
        }

        return $string;
    }

    /**
     * @param $head
     */
    public function setHead($head)
    {
        $this->head = $head;
    }
}