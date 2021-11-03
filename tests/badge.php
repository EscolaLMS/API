<?php

$xml = simplexml_load_file('coverage.xml');

$coveredStatements = $xml->project->metrics['coveredstatements'];
$totalStatements = $xml->project->metrics['statements'];
$percentage = round(min(1, $coveredStatements / $totalStatements) * 100);

file_put_contents('tests/cc-badge.svg', file_get_contents("https://img.shields.io/badge/coverage-$percentage%25-brightgreen"));
