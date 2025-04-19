<?php

namespace Tests\MalaysiaHoliday;

use MalaysiaHoliday\MalaysiaHolidayProcessor;
use PHPUnit\Framework\TestCase;

class MalaysiaHolidayProcessorTest extends TestCase
{
    private MalaysiaHolidayProcessor $processor;
    private array $testData;

    protected function setUp(): void
    {
        $this->processor = new MalaysiaHolidayProcessor();
        $this->testData = [
            [
                'regional' => 'Johor',
                'collection' => [
                    [
                        'year' => 2024,
                        'data' => [
                            ['month' => 'January', 'date' => '2024-01-01', 'name' => 'New Year'],
                            ['month' => 'February', 'date' => '2024-02-10', 'name' => 'Holiday 1'],
                        ],
                    ],
                    [
                        'year' => 2025,
                        'data' => [
                            ['month' => 'January', 'date' => '2025-01-01', 'name' => 'New Year 2'],
                            ['month' => 'March', 'date' => '2025-03-15', 'name' => 'Holiday 2'],
                        ],
                    ],
                ],
            ],
            [
                'regional' => 'Selangor',
                'collection' => [
                    [
                        'year' => 2024,
                        'data' => [
                            ['month' => 'January', 'date' => '2024-01-01', 'name' => 'New Year'],
                            ['month' => 'April', 'date' => '2024-04-05', 'name' => 'Holiday 3'],
                        ],
                    ],
                ],
            ],
        ];
    }

    // public function testFilterByMonth(): void
    // {
    //     $filteredData = $this->processor->filterByMonth($this->testData, 1);
    //     $this->assertCount(2, $filteredData);
    //     $this->assertCount(1, $filteredData[0]['collection'][0]['data']);
    //     $this->assertSame('January', $filteredData[0]['collection'][0]['data'][0]['month']);
    //     $this->assertCount(1, $filteredData[0]['collection'][1]['data']);
    //     $this->assertSame('January', $filteredData[0]['collection'][1]['data'][0]['month']);
    //     $this->assertCount(1, $filteredData[1]['collection'][0]['data']);
    //     $this->assertSame('January', $filteredData[1]['collection'][0]['data'][0]['month']);

    //     $filteredDataFebruary = $this->processor->filterByMonth($this->testData, 2); 
    //     $this->assertCount(1, $filteredDataFebruary[0]['collection'][0]['data']);
    //     $this->assertSame('February', $filteredDataFebruary[0]['collection'][0]['data'][0]['month']);
    // }

    public function testFilterByInvalidMonthReturnsOriginalData(): void
    {
        $filteredData = $this->processor->filterByMonth($this->testData, 13);
        $this->assertSame($this->testData, $filteredData);
    }

    public function testFilterByNullMonthReturnsOriginalData(): void
    {
        $filteredData = $this->processor->filterByMonth($this->testData, null);
        $this->assertSame($this->testData, $filteredData);
    }

    public function testGroupByMonth(): void
    {
        $groupedData = $this->processor->groupByMonth($this->testData);

        $this->assertCount(2, $groupedData);

        // Johor 2024
        $johor2024 = $groupedData[0]['collection'][0];
        $this->assertSame(2024, $johor2024['year']);
        $this->assertCount(2, $johor2024['data']); // January and February

        $january2024 = $johor2024['data'][0];
        $this->assertSame('January', $january2024['month']);
        $this->assertCount(1, $january2024['data']);

        $february2024 = $johor2024['data'][1];
        $this->assertSame('February', $february2024['month']);
        $this->assertCount(1, $february2024['data']);

        // Johor 2025
        $johor2025 = $groupedData[0]['collection'][1];
        $this->assertSame(2025, $johor2025['year']);
        $this->assertCount(2, $johor2025['data']); // January and March

        // Selangor 2024
        $selangor2024 = $groupedData[1]['collection'][0];
        $this->assertSame(2024, $selangor2024['year']);
        $this->assertCount(2, $selangor2024['data']); // January and April
    }
}
