<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays for Sabah in the current year
$result = $holiday->fromState('Sabah')->get();

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);