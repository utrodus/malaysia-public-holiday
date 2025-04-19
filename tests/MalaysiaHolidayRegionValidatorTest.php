<?php

namespace Tests\MalaysiaHoliday;

use MalaysiaHoliday\Exceptions\RegionException;
use MalaysiaHoliday\MalaysiaHolidayRegionValidator;
use PHPUnit\Framework\TestCase;

class MalaysiaHolidayRegionValidatorTest extends TestCase
{
    private MalaysiaHolidayRegionValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new MalaysiaHolidayRegionValidator();
        // The region arrays are now loaded in the constructor, no need to set them up here.
    }

    public function testCheckRegionalValidRegion(): void
    {
        $this->assertSame('Johor', $this->validator->checkRegional('Johor'));
        $this->assertSame('Kuala-Lumpur', $this->validator->checkRegional('Kuala Lumpur'));
        $this->assertSame('Penang', $this->validator->checkRegional('Penang'));
    }

    public function testCheckRegionalValidRelatedRegion(): void
    {
        $this->assertSame('Johor', $this->validator->checkRegional('Johore'));
        $this->assertSame('Kuala-Lumpur', $this->validator->checkRegional('KL'));
        $this->assertSame('Melaka', $this->validator->checkRegional('Malacca'));
        $this->assertSame('Penang', $this->validator->checkRegional('Pulau Pinang'));
    }

    public function testCheckRegionalInvalidRegionThrowsException(): void
    {
        $this->expectException(RegionException::class);
        $this->validator->checkRegional('NonExistentState');
    }

    

}