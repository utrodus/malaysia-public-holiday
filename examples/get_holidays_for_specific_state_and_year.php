<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays for Penang in 2025
$result = $holiday->fromState('Penang', 2025)->get();

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);