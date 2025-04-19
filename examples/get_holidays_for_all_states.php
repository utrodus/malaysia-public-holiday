<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays for all states in the current year
$result = $holiday->fromAllState()->get();

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);