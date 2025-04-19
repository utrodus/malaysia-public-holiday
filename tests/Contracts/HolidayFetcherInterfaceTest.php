<?php

namespace Tests\MalaysiaHoliday\Contracts;

use MalaysiaHoliday\Contracts\HolidayFetcherInterface;
use PHPUnit\Framework\TestCase;

class HolidayFetcherInterfaceTest extends TestCase
{
    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(HolidayFetcherInterface::class));
    }
}