<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays using an alternative region name
$result = $holiday->fromState('KL')->get();

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);