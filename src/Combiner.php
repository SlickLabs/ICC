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
     * @return void
     */
    public function combine(array $files = [])
    {
        $first = true;

        foreach ($files as $file) {

            $fileResult = $this->reader->read(new File($file['id'], $file['path']));

            if (true === $first) {
                $first = false;
                $this->setBaseFile($fileResult);
                $this->setHead($this->reader->getHeadAsString(new File($file['id'], $file['path'])));
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

                    // Discount is 3% more than base discount
                    $discountDivergence = ($discount - $baseDiscount) / 100;
                    if ($discountDivergence >= 4) {
                        // Add match
                        $this->matches[] = [
                            'matchKey' => $matchKey,
                            'from' => $baseCondition,
                            'to' => $condition
                        ];

                        // Replace current base condition
                        $this->baseFile[$matchKey] = $condition;
                    }
                } else {
                    // Simply add it because it does not yet exist
                    $this->baseMap[$condition->getType()][$condition->getKey()] = $key;
                    $this->baseFile[$matchKey] = $condition;
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

    public function getBaseFile()
    {
        return $this->baseFile;
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
