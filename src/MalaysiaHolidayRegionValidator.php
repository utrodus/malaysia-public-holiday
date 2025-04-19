<?php

namespace MalaysiaHoliday;
use MalaysiaHoliday\Exceptions\RegionException;

class MalaysiaHolidayRegionValidator
{
    private array $relatedRegion = [
        'Johore' => 'Johor',
        'KL' => 'Kuala Lumpur',
        'Malacca' => 'Melaka',
        'Pulau Pinang' => 'Penang',
    ];

    public static array $regionArray = [
        'Johor',
        'Kedah',
        'Kelantan',
        'Kuala Lumpur',
        'Labuan',
        'Melaka',
        'Negeri Sembilan',
        'Pahang',
        'Penang',
        'Perak',
        'Perlis',
        'Putrajaya',
        'Sarawak',
        'Sabah',
        'Selangor',
        'Terengganu',
    ];

    /**
     * @throws RegionException
     */
    public function checkRegional(string $regional): string
    {
        $regionalLower = strtolower($regional);
        if (isset($this->relatedRegion[$regional])) {
            $regional = $this->relatedRegion[$regional];
            $regionalLower = strtolower($regional);
        }

        if (in_array($regionalLower, array_map('strtolower', self::$regionArray), true)) {
            return str_replace(" ", "-", $regional);
        }

        throw new RegionException("Region '{$regional}' is not valid.");
    }
}
