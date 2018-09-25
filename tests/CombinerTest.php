<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 25-09-18
 * Time: 12:17
 */

namespace ICC\Tests;

use ICC\Reader;
use PHPUnit\Framework\TestCase;

class CombinerTest extends TestCase
{
    /**
     * @return Reader
     */
    public function reader()
    {
        return new Reader([
            Reader::SETTING_FORMATTER => new \ICC\Formatter()
        ]);
    }

    public function testCombine()
    {
        $resourcesDir = dirname(__FILE__) . '/resources';

        $combiner = new \ICC\Combiner($this->reader());
        $combiner->combine([
            [
                'id' => 1,
                'path' => $resourcesDir . '/52610368.rexel.alfana.ICC',
            ],[
                'id' => 2,
                'path' => $resourcesDir . '/55504192.rexel.lodema.ICC'
            ]
        ]);

        // Creates the temporary combined file
        file_put_contents($resourcesDir . '/combined.ICC', $combiner->toString());

        $expectedFile = $resourcesDir . '/combined.expected.ICC';
        $actualFile = $resourcesDir . '/combined.ICC';

        // Checks if the expected file is the same as the combined file
        $this->assertFileEquals($expectedFile, $actualFile);

        unlink($actualFile);
    }
}