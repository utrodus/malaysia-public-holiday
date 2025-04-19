<?php

namespace MalaysiaHoliday\Contracts;

interface HolidayFetcherInterface
{
    /**
     * Fetches holiday data for a given URL and year.
     *
     * @param string $url
     * @param int $year
     * @return array
     */
    public function fetch(string $url, int $year): array;
}
