<?php

namespace MalaysiaHoliday;

use MalaysiaHoliday\Contracts\HolidayFetcherInterface;
use MalaysiaHoliday\Exceptions\RegionException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MalaysiaHoliday
{
    private string $baseUrl = "https://www.officeholidays.com/countries/malaysia";
    private ?int $year = null;
    private string|array|null $region = null;
    private ?int $month = null;
    private bool $groupByMonth = false;
    private HolidayFetcherInterface $holidayFetcher;
    private MalaysiaHolidayRegionValidator $regionValidator;
    private MalaysiaHolidayProcessor $holidayProcessor;

    public function __construct(
        ?HolidayFetcherInterface $holidayFetcher = null,
        ?MalaysiaHolidayRegionValidator $regionValidator = null,
        ?MalaysiaHolidayProcessor $holidayProcessor = null
    ) {
        $this->holidayFetcher = $holidayFetcher ?? new MalaysiaHolidayFetcher();
        $this->regionValidator = $regionValidator ?? new MalaysiaHolidayRegionValidator();
        $this->holidayProcessor = $holidayProcessor ?? new MalaysiaHolidayProcessor();
    }

    public static function make(?HttpClientInterface $client = null): self
    {
        $fetcher = $client ? new MalaysiaHolidayFetcher(new \Symfony\Component\BrowserKit\HttpBrowser($client)) : new MalaysiaHolidayFetcher();
        return new self($fetcher);
    }

    public function fromAllState(?int $year = null): static
    {
        $this->region = null;
        $this->year = $year;
        return $this;
    }

    public function fromState(string|array $region, ?int $year = null): static
    {
        $this->region = $region;
        $this->year = $year;
        return $this;
    }

    public function ofYear(int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function groupByMonth(): static
    {
        $this->groupByMonth = true;
        return $this;
    }

    public function filterByMonth(int $month): static
    {
        $this->month = $month;
        return $this;
    }

    
    /**
     * The function retrieves holiday data based on specified regions and years, handling errors and
     * providing developer information.
     * 
     * @return array The `get()` function returns an array containing the following keys and values:
     */
    public function get(): array
    {
        $regions = is_array($this->region) ? $this->region : ($this->region === null ? [null] : [$this->region]);
        $years = $this->year === null ? [date('Y')] : [$this->year];
        $final = [];
        $errorMessages = [];
        $hasError = false; // Flag to track if any errors occurred

        foreach ($regions as $region) {
            $data = [];
            try {
                $processedRegion = $region ? $this->regionValidator->checkRegional($region) : null;
                foreach ($years as $selectedYear) {
                    $requestUrl = $processedRegion ? "{$this->baseUrl}/{$processedRegion}/{$selectedYear}" : "{$this->baseUrl}/{$selectedYear}";
                    $holidays = $this->holidayFetcher->fetch($requestUrl, $selectedYear);
                    $data[] = [
                        'year' => (int)$selectedYear,
                        'data' => array_values(array_filter($holidays)),
                    ];
                }

                $final[] = [
                    'regional' => $region ?? "Malaysia",
                    'collection' => $data,
                ];
            } catch (RegionException $e) {
                $errorMessages[] = $e->getMessage();
                $final[] = [
                    'regional' => $region ?? "Malaysia",
                    'collection' => [],
                ];
                $hasError = true;
            } catch (\RuntimeException $e) {
                $errorMessages[] = "Error fetching data for region '" . ($region ?? 'Malaysia') . "': " . $e->getMessage();
                $final[] = [
                    'regional' => $region ?? "Malaysia",
                    'collection' => [],
                ];
                $hasError = true;
            }
        }

        $resultData = [
            'status' => !$hasError, // Status is false if any error occurred
            'data' => $final,
            'error_messages' => $errorMessages,
            'developer' => [
                "name" => "Utrodus Said Al Baqi",
                "email" => "contact.utrodus@gmail.com",
                "github" => "https://github.com/utrodus",
            ],
        ];

        if ($this->month !== null) {
            $resultData['data'] = $this->holidayProcessor->filterByMonth($resultData['data'], $this->month);
        }

        if ($this->groupByMonth) {
            $resultData['data'] = $this->holidayProcessor->groupByMonth($resultData['data']);
        }

        return $resultData;
    }


    /**
     * Get formatted holiday data for easier reading and access.
     * @param array $response
     * @return string
     */
    function formatHolidayData(array $response): string
    {
        $formatted = [];

        if (!isset($response['data']) || !is_array($response['data'])) {
            return json_encode(['error' => 'Invalid data structure'], JSON_PRETTY_PRINT);
        }

        foreach ($response['data'] as $regionData) {
            $regional = $regionData['regional'] ?? 'Unknown Region';
            $collections = $regionData['collection'] ?? [];

            foreach ($collections as $collection) {
                $year = $collection['year'] ?? 'Unknown Year';
                $holidays = $collection['data'] ?? [];


                foreach ($holidays as $holiday) {
                    $formatted[] = [
                        'date' => $holiday['date'] ?? '',
                        'day' => $holiday['day'] ?? '',
                        'date_formatted' => $holiday['date_formatted'] ?? '',
                        'month' => $holiday['month'] ?? '',
                        'name' => $holiday['name'] ?? '',
                        'description' => $holiday['description'] ?? '',
                        'is_holiday' => !empty($holiday['is_holiday']),
                        'type' => $holiday['type'] ?? '',
                        'type_id' => $holiday['type_id'] ?? null,
                    ];
                }
            }
        }
        $results[] = [
            'year' => "$year",
            'region' => $regional,
            'data' => $formatted,
        ];

        return json_encode($results, JSON_PRETTY_PRINT);
    }
}
