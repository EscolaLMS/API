<?php

/*
$xml = simplexml_load_file('coverage.xml');

$coveredStatements = $xml->project->metrics['coveredstatements'];
$totalStatements = $xml->project->metrics['statements'];
$percentage = round(min(1, $coveredStatements / $totalStatements) * 100);

file_put_contents('tests/cc-badge.svg', file_get_contents("https://img.shields.io/badge/coverage-$percentage%25-brightgreen"));


*/

// coverage-checker.php
$inputFile  = 'coverage.xml';



$xml             = new SimpleXMLElement(file_get_contents($inputFile));
$metrics         = $xml->xpath('//metrics');
$totalElements   = 0;
$checkedElements = 0;

foreach ($metrics as $metric) {

    $totalElements   += (int) $metric['elements'];
    $checkedElements += (int) $metric['coveredelements'];
}

$coverage = ($checkedElements / $totalElements) * 100;

file_put_contents('tests/cc-badge.svg', file_get_contents("https://img.shields.io/badge/coverage-$percentage%25-brightgreen"));


echo 'Code coverage is ' . $coverage . '% - OK!' . PHP_EOL;
