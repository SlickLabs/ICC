<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

$dir = getcwd();
$readerSettings = [
    ICC\Reader::SETTING_FORMATTER => new \ICC\Formatter()
];
$reader = new ICC\Reader($readerSettings);

$combiner = new \ICC\Combiner($reader);
$combiner->combine([
    [
        'id' => 2,
        'path' => $dir .'/tests/resources/52610368.rexel.alfana.ICC',
    ], [
        'id' => 3,
        'path' => $dir .'/tests/resources/55504192.rexel.lodema.ICC'
    ]
]);

file_put_contents($dir . '/tests/resources/combined.ICC', $combiner->toString());

echo "<pre>";
print_r($combiner->getMatches());
echo "</pre>";

/**
 * Read head
 */
$file = new ICC\File('test-ICC', $dir . '/tests/resources/test.ICC');

$formatter = new \ICC\Formatter();
$formatter->setFirstLineOnly(true);

$reader = new ICC\Reader([
    ICC\Reader::SETTING_FORMATTER => $formatter
]);

$reader = new ICC\Reader($readerSettings);
$head = $reader->getHead($file);


echo "<pre>";
print_r($head);
echo "</pre>";

/**
 * Read first line
 */
$file = new ICC\File('test-ICC', $dir . '/tests/resources/test.ICC');

$formatter = new \ICC\Formatter();
$formatter->setFirstLineOnly(false);

$reader = new ICC\Reader([
    ICC\Reader::SETTING_FORMATTER => $formatter
]);

$contents = $reader->read($file);
$content = $contents[0];

echo "<pre>";
print_r($content);
echo "</pre>";

/**
 * Read second line
 */
$file = new ICC\File('test-ICC', $dir . '/tests/resources/test.ICC');

$formatter = new \ICC\Formatter();
$formatter->setFirstLineOnly(false);

$reader = new ICC\Reader([
    ICC\Reader::SETTING_FORMATTER => $formatter
]);

$contents = $reader->read($file);
$content = $contents[1];

echo "<pre>";
print_r($content);
echo "</pre>";

/**
 * TODO
 * - Matches wegschrijven
 * - Conditions uit andere bestanden die niet
 *   in de base zitten op de juiste plek in base toevoegen.
 */