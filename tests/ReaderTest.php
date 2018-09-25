<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 25-09-18
 * Time: 12:17
 */

namespace ICC\Tests;

use ICC\File;
use ICC\Reader;
use ICC\Record\AbstractRecord;
use ICC\Record\Condition;
use ICC\Record\ConditionHead;
use PHPUnit\Framework\TestCase;

/**
 * Class ReaderTest
 *
 * Test all the Reader functionality
 *
 * @package ICC\Tests
 */
final class ReaderTest extends TestCase
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @return Reader
     */
    public function reader()
    {
        return new Reader([
            Reader::SETTING_FORMATTER => new \ICC\Formatter()
        ]);
    }

    /**
     * Tests if the test file exists
     *
     * @return File
     */
    public function testFile()
    {
        $fileDir = dirname(__FILE__) . '/resources/test.ICC';

        $this->assertFileExists($fileDir);

        return new File('test-ICC', $fileDir);
    }

    /**
     * Tests if it is possible to read the head of the condition file
     *
     * @depends testFile
     * @throws \ICC\Exception\FileException
     */
    public function testReadHead(File $file)
    {
        $head = $this->reader()->getHead($file);

        // tests keys
        foreach (ConditionHead::getFields() as $key => $field) {
            $this->assertArrayHasKey($field['key'], $head, 'Error. ICC file ' . $file->getPath() . ' head array does not have key ' . $field['key']);
        }

        $this->assertEquals($head['SupplierGLN'], 8713473000010);
        $this->assertEquals($head['CustomerId'], 52610368);
        $this->assertEquals($head['CreatedAt'], 20180215);
        $this->assertEquals($head['LineCount'], '001984');
        $this->assertEquals($head['Version'], '1.1');
        $this->assertEquals($head['SupplierName'], 'Rexel Nederland B.V.');
        $this->assertEquals($head['CustomerGLN'], '');
    }

    /**
     * Tests if it is possible to read the head of the condition file
     *
     * @depends testFile
     * @throws \ICC\Exception\FileException
     */
    public function testReadHeadAsString(File $file)
    {
        $head = $this->reader()->getHeadAsString($file);

        $this->assertEquals('8713473000010       52610368            201802150019841.1  Rexel Nederland B.V.', $head);
    }

    /**
     * Tests if the keys of the first line ar readable and correct
     *
     * @depends testFile
     * @throws \ICC\Exception\FileException
     */
    public function testFirstLineReadRecordKeys(File $file)
    {
        $contents = $this->reader()->read($file);

        $this->assertArrayHasKey(0, $contents);
        $this->assertContainsOnlyInstancesOf(AbstractRecord::class, $contents);

        $line = $contents[0]->getValues();

        foreach (Condition::getFields() as $key => $field) {
            $this->assertArrayHasKey($field['key'], $line, 'Error. ICC file ' . $file->getPath() . ' first line array does not have key ' . $field['key']);
        }

        return $line;
    }

    /**
     * Tests if the allowance group condition record's expected data is correct
     *
     * @depends testFirstLineReadRecordKeys
     * @throws \ICC\Exception\FileException
     */
    public function testReadRecordAllowanceGroup(array $line)
    {
        $this->assertEquals($line['DiscountGroup'], 100001);
        $this->assertEquals($line['TradeItemId'], '');
        $this->assertEquals($line['Description'], 'DRAKA VD 1,5-2,5MM2      (INS01/02)');
        $this->assertEquals($line['Discount1'], '05220');
        $this->assertEquals($line['Discount2'], '');
        $this->assertEquals($line['Discount3'], '');
        $this->assertEquals($line['NetPrice'], '');
        $this->assertEquals($line['StartDate'], '20160119');
        $this->assertEquals($line['EndDate'], '');
    }

    /**
     * Tests if the keys of the second line ar readable and correct
     *
     * @depends testFile
     * @throws \ICC\Exception\FileException
     */
    public function testSecondLineReadRecordKeys(File $file)
    {
        $contents = $this->reader()->read($file);

        $this->assertArrayHasKey(1, $contents);
        $this->assertContainsOnlyInstancesOf(AbstractRecord::class, $contents);

        $line = $contents[1]->getValues();

        foreach (Condition::getFields() as $key => $field) {
            $this->assertArrayHasKey($field['key'], $line, 'Error. ICC file ' . $file->getPath() . ' first line array does not have key ' . $field['key']);
        }

        return $line;
    }

    /**
     * Tests if the trade item condition record's expected data is correct
     *
     * @depends testSecondLineReadRecordKeys
     * @throws \ICC\Exception\FileException
     */
    public function testReadRecordTradeItem(array $line)
    {
        $this->assertEquals($line['DiscountGroup'], '');
        $this->assertEquals($line['TradeItemId'], 2700110336);
        $this->assertEquals($line['Description'], 'PIP FLEXBUIS LF 19MM 100M CR');
        $this->assertEquals($line['Discount1'], '07710');
        $this->assertEquals($line['Discount2'], '00000');
        $this->assertEquals($line['Discount3'], '');
        $this->assertEquals($line['NetPrice'], '');
        $this->assertEquals($line['StartDate'], '20160218');
        $this->assertEquals($line['EndDate'], '');
    }
}
