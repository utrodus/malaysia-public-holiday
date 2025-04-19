<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ .'/../vendor/autoload.php';

use MalaysiaHoliday\MalaysiaHoliday;
use MalaysiaHoliday\MalaysiaHolidayRegionValidator;

$holiday = new MalaysiaHoliday;

// $result = $holiday->fromState(MalaysiaHolidayRegionValidator::$regionArray)->get();
$result = $holiday->fromState("KL")->get();

//print_r($result);
header('Content-Type: application/json');
echo json_encode($result);
