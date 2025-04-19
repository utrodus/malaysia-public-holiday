<?php

namespace Tests\MalaysiaHoliday;

use MalaysiaHoliday\MalaysiaHolidayFetcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\HttpBrowser as Client;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class MalaysiaHolidayFetcherTest extends TestCase
{
    public function testFetchReturnsArrayOfHolidays(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse('
                <table class="country-table">
                    <tr>
                        <td>Mon</td>
                        <td>Jan 1</td>
                        <td>New Year\'s Day</td>
                        <td></td>
                    </tr>
                    <tr class="govt_holiday">
                        <td>Tue</td>
                        <td>Jan 28</td>
                        <td>Thaipusam</td>
                        <td></td>
                    </tr>
                </table>
            '),
        ]);
        $client = new Client($mockHttpClient);
        $fetcher = new MalaysiaHolidayFetcher($client);
        $holidays = $fetcher->fetch('https://example.com/malaysia/2025', 2025);

        $this->assertIsArray($holidays);
        $this->assertCount(2, $holidays);
        $this->assertArrayHasKey('date', $holidays[0]);
        $this->assertSame('2025-01-01', $holidays[0]['date']);
        $this->assertSame('New Year\'s Day', $holidays[0]['name']);
        $this->assertSame('Government/Public Sector Holiday', $holidays[1]['type']);
    }

    public function testFetchHandlesEmptyTable(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse('<table class="country-table"></table>'),
        ]);
        $client = new Client($mockHttpClient);
        $fetcher = new MalaysiaHolidayFetcher($client);
        $holidays = $fetcher->fetch('https://example.com/malaysia/2025', 2025);

        $this->assertIsArray($holidays);
        $this->assertEmpty($holidays);
    }

}