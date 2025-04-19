<?php

namespace MalaysiaHoliday;

use MalaysiaHoliday\Contracts\HolidayFetcherInterface;
use Symfony\Component\BrowserKit\HttpBrowser as Client;

class MalaysiaHolidayFetcher implements HolidayFetcherInterface

{
    private Client $client;

    public function __construct(?Client $client = null) // Explicitly nullable
    {
        $this->client = $client ?? new Client();
    }

    public function fetch(string $requestUrl, int $currentYear): array
    {
        try {
            $crawler = $this->client->request('GET', $requestUrl);
            return $crawler->filter('.country-table tr')->each(
                function ($node) use ($currentYear) {
                    if ($node->children()->nodeName() === 'td') {
                        $temp = [];
                        $temp['day'] = trim($node->children()->eq(0)->text());
                        $dateStrParts = strtok(trim($node->children()->eq(1)->extract(['_text', 'class'])[0][0]), "\n");
                        $dateStr = trim($dateStrParts . " " . $currentYear);

                        if (empty($dateStr)) {
                            return null;
                        }

                        $date = \DateTimeImmutable::createFromFormat('F d Y', preg_replace("/[\n\r]/", "", $dateStr));

                        if (!$date) {
                            $date = \DateTimeImmutable::createFromFormat('Y-m-d', $node->children()->eq(1)->children()->text());
                            if (!$date) {
                                return null;
                            }
                        }

                        $temp['date'] = $date->format('Y-m-d');
                        $temp['date_formatted'] = $date->format('d F Y');
                        $temp['month'] = $date->format('F');
                        $temp['name'] = trim($node->children()->eq(2)->text());
                        $temp['description'] = trim($node->children()->eq(3)->text());
                        $temp['is_holiday'] = true;
                        switch (trim($node->extract(['class'])[0])) {
                            case 'govt_holiday':
                                $temp['type'] = "Government/Public Sector Holiday";
                                $temp['type_id'] = 1;
                                break;
                            case 'nap-past':
                            case 'nap':
                                $temp['type'] = "Not a Public Holiday";
                                $temp['is_holiday'] = false;
                                $temp['type_id'] = 2;
                                break;
                            case 'country-past':
                            case 'country':
                                $temp['type'] = "National Holiday";
                                $temp['type_id'] = 3;
                                break;
                            case 'region-past':
                            case 'region':
                                $temp['type'] = "Regional Holiday";
                                $temp['type_id'] = 4;
                                break;
                            default:
                                $temp['type'] = "Unknown";
                                $temp['type_id'] = 5;
                                break;
                        }
                        return $temp;
                    }
                    return null;
                }
            );
        } catch (\Exception $e) {
            // Log the error or throw a more specific exception
            throw new \RuntimeException("Error fetching holiday data from the website.", 0, $e);
        }
    }
}
