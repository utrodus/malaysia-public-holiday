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
}
