<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays for Sabah in the current year
$result = $holiday->fromState('Sabah')->get();
$formatted = $holiday->formatHolidayData($result);

header('Content-Type: application/json');
echo $formatted;