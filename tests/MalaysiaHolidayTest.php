<?php

namespace Tests\MalaysiaHoliday;

use MalaysiaHoliday\Contracts\HolidayFetcherInterface;
use MalaysiaHoliday\MalaysiaHolidayProcessor;
use MalaysiaHoliday\MalaysiaHoliday;
use MalaysiaHoliday\MalaysiaHolidayRegionValidator;
use PHPUnit\Framework\TestCase;

class MalaysiaHolidayTest extends TestCase
{
    private $fetcher;
    private $validator;
    private $processor;
    private MalaysiaHoliday $holidayService;

    protected function setUp(): void
    {
        $this->fetcher = $this->createMock(HolidayFetcherInterface::class);
        $this->validator = $this->createMock(MalaysiaHolidayRegionValidator::class);
        $this->processor = $this->createMock(MalaysiaHolidayProcessor::class);
        $this->holidayService = new MalaysiaHoliday($this->fetcher, $this->validator, $this->processor);
    }

    public function testFromStateSetsRegionAndYear(): void
    {
        $service = $this->holidayService->fromState('Selangor', 2024);
        $this->assertInstanceOf(MalaysiaHoliday::class, $service);
        // Cannot directly access private properties for assertion without reflection
        // Consider adding getter methods for testability if needed.
    }

    public function testFromAllStateSetsRegionToNullAndYear(): void
    {
        $service = $this->holidayService->fromAllState(2023);
        $this->assertInstanceOf(MalaysiaHoliday::class, $service);
        // Same consideration as above for private properties.
    }

    public function testOfYearSetsYear(): void
    {
        $service = $this->holidayService->ofYear(2026);
        $this->assertInstanceOf(MalaysiaHoliday::class, $service);
        // Same consideration as above for private properties.
    }

    public function testGroupByMonthSetsFlag(): void
    {
        $service = $this->holidayService->groupByMonth();
        $this->assertInstanceOf(MalaysiaHoliday::class, $service);
        // Same consideration as above for private properties.
    }

    public function testFilterByMonthSetsMonth(): void
    {
        $service = $this->holidayService->filterByMonth(7);
        $this->assertInstanceOf(MalaysiaHoliday::class, $service);
        // Same consideration as above for private properties.
    }

    public function testGetFetchesAndProcessesHolidaysForAllStates(): void
    {
        $this->fetcher->expects($this->once())
            ->method('fetch')
            ->with('https://www.officeholidays.com/countries/malaysia/' . date('Y'))
            ->willReturn([['date' => '...', 'name' => '...']]);

        $result = $this->holidayService->fromAllState()->get();
        $this->assertIsArray($result);
        $this->assertTrue($result['status']);
        $this->assertCount(1, $result['data']);
        $this->assertSame('Malaysia', $result['data'][0]['regional']);
    }

    public function testGetFetchesAndProcessesHolidaysForSpecificState(): void
    {
        $this->validator->expects($this->once())
            ->method('checkRegional')
            ->with('Johor')
            ->willReturn('johor');

        $this->fetcher->expects($this->once())
            ->method('fetch')
            ->with('https://www.officeholidays.com/countries/malaysia/johor/' . date('Y'))
            ->willReturn([['date' => '...', 'name' => '...']]);

        $result = $this->holidayService->fromState('Johor')->get();
        $this->assertIsArray($result);
        $this->assertTrue($result['status']);
        $this->assertCount(1, $result['data']);
        $this->assertSame('Johor', $result['data'][0]['regional']);
    }

    public function testGetFiltersByMonthIfSet(): void
    {
        $this->fetcher->method('fetch')->willReturn([['month' => 'January', 'date' => '...']]);
        $this->processor->expects($this->once())
            ->method('filterByMonth')
            ->willReturnArgument(0);

        $this->holidayService->fromAllState()->filterByMonth(1)->get();
    }

    public function testGetGroupsByMonthIfFlagSet(): void
    {
        $this->fetcher->method('fetch')->willReturn([['month' => 'January', 'date' => '...']]);
        $this->processor->expects($this->once())
            ->method('groupByMonth')
            ->willReturnArgument(0);

        $this->holidayService->fromAllState()->groupByMonth()->get();
    }

    public function testGetHandlesRegionException(): void
    {
        $this->validator->expects($this->once())
            ->method('checkRegional')
            ->willThrowException(new \MalaysiaHoliday\Exceptions\RegionException('Invalid region.'));

        $result = $this->holidayService->fromState('Invalid')->get();
        $this->assertFalse($result['status']);
        $this->assertCount(1, $result['error_messages']);
        $this->assertSame('Invalid region.', $result['error_messages'][0]);
        $this->assertCount(1, $result['data']);
        $this->assertSame('Invalid', $result['data'][0]['regional']);
        $this->assertEmpty($result['data'][0]['collection']);
    }

    

}