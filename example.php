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
    $dir .'/files/52610368.alfana.ICC',
    $dir .'/files/55504192.rexel.lodema.ICC'
]);

file_put_contents(
    $dir .'/files/combined.ICC',
    $combiner->toString()
);

echo "<pre>";
print_r($combiner->getMatches());
echo "</pre>";
die();

/**
 * TODO
 * - Matches wegschrijven
 * - Conditions uit andere bestanden die niet
 *   in de base zitten op de juiste plek in base toevoegen.
 */