<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays for Selangor in 2024, filtered by May
$result = $holiday->fromState('Selangor', 2024)->filterByMonth(5)->get();

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);