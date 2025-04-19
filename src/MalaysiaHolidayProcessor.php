<?php

namespace MalaysiaHoliday;

class MalaysiaHolidayProcessor
{
    private array $monthsArray = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    public function filterByMonth(array $data, ?int $month): array
    {
        if ($month === null || !$this->checkMonth($month)) {
            return $data;
        }

        $filteredData = [];
        $targetMonthLower = strtolower($this->getMonth($month));

        foreach ($data as $regionData) {
            $regionData['collection'] = array_map(function ($yearData) use ($targetMonthLower) {
                $yearData['data'] = array_filter($yearData['data'], function ($holiday) use ($targetMonthLower) {
                    return strtolower($holiday['month']) === $targetMonthLower;
                });
                return $yearData;
            }, $regionData['collection']);
            $filteredData[] = $regionData;
        }

        return $filteredData;
    }

    public function groupByMonth(array $data): array
    {
        $groupedData = [];
        foreach ($data as $regionData) {
            $regionData['collection'] = array_map(function ($yearData) {
                $groupedByMonth = [];
                foreach ($yearData['data'] as $holiday) {
                    $month = $holiday['month'];
                    $monthKey = array_search($month, array_column($groupedByMonth, 'month'), true);
                    if ($monthKey === false) {
                        $groupedByMonth[] = [
                            'month' => $month,
                            'data' => [$holiday],
                        ];
                    } else {
                        $groupedByMonth[$monthKey]['data'][] = $holiday;
                    }
                }
                return [
                    'year' => $yearData['year'],
                    'data' => $groupedByMonth,
                ];
            }, $regionData['collection']);
            $groupedData[] = $regionData;
        }
        return $groupedData;
    }

    private function checkMonth(int $month): bool
    {
        return isset($this->monthsArray[$month]);
    }

    private function getMonth(int $month): string
    {
        return strtolower($this->monthsArray[$month]);
    }
}
