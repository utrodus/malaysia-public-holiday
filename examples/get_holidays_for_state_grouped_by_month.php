<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays for Perak in 2026, grouped by month
$result = $holiday->fromState('Perak', 2026)->groupByMonth()->get();

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);