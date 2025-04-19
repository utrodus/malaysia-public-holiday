<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Attempt to get holidays for an invalid region
$result = $holiday->fromState('Coba')->get();

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);