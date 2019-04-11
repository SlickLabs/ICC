<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 08-12-17
 * Time: 21:30
 */

namespace ICC;

use ICC\Record\Condition;
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
    protected $stringHead;

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

    protected $supplierMap = [];

    protected $fileResult = [];

    /**
     * Combiner constructor.
     * @param ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader, array $supplierMap = [])
    {
        $this->reader = $reader;
        $this->supplierMap = $supplierMap;
    }

    /**
     * @param array $files
     * @return void
     */
    public function combine(array $files = [])
    {
        $first = true;

        foreach ($files as $file) {

            $this->fileResult = $this->reader->read(new File($file['id'], $file['path']));

            $this->head = $this->reader->getHead(new File($file['id'], $file['path']));

            if (true === $first) {
                $first = false;
                $this->setBaseFile();
                $this->setStringHead($this->reader->getHeadAsString(new File($file['id'], $file['path'])));
                continue;
            }

            foreach ($this->fileResult as $key => $condition) {

                if (!$condition instanceof RecordInterface) {
                    continue;
                }

                $this->conditionAddSupplier($condition);

                if (isset($this->baseMap[$condition->getType()][$condition->getKey()])) {
                    $matchKey = $this->baseMap[$condition->getType()][$condition->getKey()];
                    $baseCondition = $this->baseFile[$matchKey];

                    $baseDiscount = $this->getConditionDiscount($baseCondition);
                    $discount = $this->getConditionDiscount($condition);

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
                    $this->baseFile[] = $condition;
                }
            }
        }
    }

    /**
     * @param array $fileResult
     */
    protected function setBaseFile()
    {
        foreach ($this->fileResult as $key => $condition) {
            $this->conditionAddSupplier($condition);

            $this->baseMap[$condition->getType()][$condition->getKey()] = $key;
        }

        $this->baseFile = $this->fileResult;
    }

    public function getBaseFile()
    {
        return $this->baseFile;
    }

    public function removeRow($key)
    {
        unset($this->baseFile[$key]);
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
        $string = $this->stringHead;
        
        foreach ($this->baseFile as $condition) {
            $string .= $condition->toString() . "\r\n";
        }

        return $string;
    }

    /**
     * @param $head
     */
    public function setStringHead($head)
    {
        $this->stringHead = $head;
    }

    /**
     * @param RecordInterface $condition
     */
    protected function conditionAddSupplier(RecordInterface $condition)
    {
        if (isset($this->supplierMap[$this->head['CustomerId']])) {
            $supplier = $this->supplierMap[$this->head['CustomerId']];
            $description . ' ' . $condition->getValue('Description');

            $condition->setValue('Description', $description);
        }
    }

    public function getConditionDiscount(Condition $condition)
    {
        return (float) $condition->getValue('Discount1') + $condition->getValue('Discount2') + $condition->getValue('Discount3');
    }
}
