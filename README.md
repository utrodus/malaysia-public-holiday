
# 🇲🇾 Malaysia Public Holiday
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.2-blue)
[![Build Status](https://github.com/utrodus/malaysia-public-holiday/actions/workflows/tests.yaml/badge.svg?label=Build+Status)](https://github.com/utrodus/malaysia-public-holiday/actions/workflows/tests.yaml)
[![Packagist Stable Version](https://img.shields.io/packagist/v/utrodus/malaysia-public-holiday.svg)](https://packagist.org/packages/utrodus/malaysia-public-holiday)

**Malaysia Public Holiday** is a PHP library to fetch **official public holiday data** for all Malaysian states by scraping [Office Holidays](https://www.officeholidays.com/countries/malaysia).  

It’s best for HR applications, attendance systems, logistics planning, calendars, and any system that needs accurate holiday information.

---

## ✨ Features

- 📆 Get national and regional holidays in Malaysia
- 🌍 Support for all Malaysian states and federal territories
- 📅 Filter holidays by **year**, **month**, or **state**
- 🔁 Supports alias names for states (e.g., `KL`, `Johore`, `Malacca`)
- ⚙️ Easily integrable with any PHP project

---

## 💼 Use Cases

- Employee attendance and HR systems
- Delivery scheduling & logistics
- Custom calendar generation
- Event planning & automation
- Working-day calculations


---


---

## 🚀 Requirements

- PHP >= **8.2**
- Composer

---

## 🛠️ Tech Stack

| Tool                          | Purpose                         |
|-------------------------------|----------------------------------|
| **PHP >= 8.2**                | Core programming language        |
| [Guzzle](https://github.com/guzzle/guzzle) | HTTP requests                   |
| [Symfony BrowserKit](https://symfony.com/doc/current/components/browser_kit.html) | Web scraping (HTML crawling) |
| [PHPUnit](https://phpunit.de/)            | Unit testing                    |
| [GitHub Actions](https://github.com/features/actions) | Continuous integration         |


---

## 📦 Installation

```bash
composer require utrodus/malaysia-public-holiday
```

---

## ⚡ Usage

```php
<?php

use MalaysiaHoliday\MalaysiaHoliday;

$holiday = new MalaysiaHoliday();

// Get holidays for all states in the current year
$result = $holiday->fromAllState()->get();


header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;

/**
 *  Get formatted holiday data 
 *  with formatHoildayData method you can easier read and access
 *  */
echo "------------------------------------------------" . PHP_EOL;
echo "Formatted Holiday Data:" . PHP_EOL;
$formatted = $holiday->formatHolidayData($result);
echo $formatted . PHP_EOL;

```

<br>

> I have included code examples to try this library in each file within the examples folder. Please see below:


| Use Case                                            | Example File                                                                | Description                                                                 |
|-----------------------------------------------------|-----------------------------------------------------------------------------|-----------------------------------------------------------------------------|
| Get holidays for a specific state                   | [`examples/get_holidays_for_specific_state.php`](examples/get_holidays_for_specific_state.php) | Retrieves holidays for a particular state in the current year.              |
| Get holidays for a specific state and year          | [`examples/get_holidays_for_specific_state_and_year.php`](examples/get_holidays_for_specific_state_and_year.php) | Fetches holidays for a state in a specific year.                            |
| Get holidays for all states                         | [`examples/get_holidays_for_all_states.php`](examples/get_holidays_for_all_states.php)         | Retrieves holidays for all Malaysian states in the current year.           |
| Get holidays for all states and a specific year    | [`examples/get_holidays_for_all_states_and_year.php`](examples/get_holidays_for_all_states_and_year.php) | Fetches holidays for all states in a given year.                          |
| Filter holidays by month                            | [`examples/get_holidays_for_state_filtered_by_month.php`](examples/get_holidays_for_state_filtered_by_month.php) | Filters holidays for a state in a specific month.                         |
| Group holidays by month                             | [`examples/get_holidays_for_state_grouped_by_month.php`](examples/get_holidays_for_state_grouped_by_month.php) | Groups holidays for a state by month.                                     |
| Handling invalid regions                            | [`examples/handling_invalid_region.php`](examples/handling_invalid_region.php)                 | Demonstrates how the library handles invalid region inputs.                 |
| Using alternative region names                      | [`examples/using_alternative_region_name.php`](examples/using_alternative_region_name.php)     | Shows support for alias state names.                                      |

---
---

## 🧪 Running Tests

```bash
vendor/bin/phpunit tests
```



---

## 📬 Contributing

Pull requests and suggestions are welcome! If you find bugs or have a feature request, feel free to [open an issue](https://github.com/utrodus/malaysia-public-holiday/issues).

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---
