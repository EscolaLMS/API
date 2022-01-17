<?php

$inputFile  = 'coverage.xml';



$xml             = new SimpleXMLElement(file_get_contents($inputFile));
$metrics         = $xml->xpath('//metrics');
$totalElements   = 0;
$checkedElements = 0;

foreach ($metrics as $metric) {

    $totalElements   += (int) $metric['elements'];
    $checkedElements += (int) $metric['coveredelements'];
}

$coverage = number_format(($checkedElements / $totalElements) * 100, 2);

file_put_contents('tests/cc-badge.svg', file_get_contents("https://img.shields.io/badge/coverage-$coverage%25-brightgreen"));


echo 'Code coverage is ' . $coverage . '% - OK!' . PHP_EOL;

$coverage_txt = file_get_contents("coverage.txt");

preg_match('/Tests: (\d+)/', $coverage_txt, $output_array1);
preg_match('/Assertions: (\d+)/', $coverage_txt, $output_array2);


// https://img.shields.io/badge/Tests-OK%20(4%20tests%2C%2016%20assertions)-blue
file_put_contents('tests/cc-tests.svg', file_get_contents("https://img.shields.io/badge/tests-$$output_array1[1]%25-brightgreen"));
file_put_contents('tests/cc-assertions.svg', file_get_contents("https://img.shields.io/badge/assertions-$$output_array2[1]%25-brightgreen"));
