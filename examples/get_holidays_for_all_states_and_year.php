<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays for all states in 2023
$result = $holiday->fromAllState(2023)->get();
header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;

/**
 *  Get formatted holiday data 
 *  with formatHoildayData function you can easier read and access
 *  */
echo "------------------------------------------------" . PHP_EOL;
echo "Formatted Holiday Data:" . PHP_EOL;
$formatted = $holiday->formatHolidayData($result);
echo $formatted . PHP_EOL;