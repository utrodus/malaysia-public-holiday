
# 🇲🇾 Malaysia Public Holiday
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.2-blue)
![License](https://img.shields.io/github/license/utrodus/malaysia-public-holiday)
![Build](https://img.shields.io/github/actions/workflow/status/utrodus/malaysia-public-holiday/tests.yml)


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

## 🛠️ Tech Stack

| Tool                          | Purpose                         |
|-------------------------------|----------------------------------|
| **PHP >= 8.2**                | Core programming language        |
| [Guzzle](https://github.com/guzzle/guzzle) | HTTP requests                   |
| [Symfony BrowserKit](https://symfony.com/doc/current/components/browser_kit.html) | Web scraping (HTML crawling) |
| [PHPUnit](https://phpunit.de/)            | Unit testing                    |
| [GitHub Actions](https://github.com/features/actions) | Continuous integration         |

---

## 🚀 Requirements

- PHP >= **8.2**
- Composer

---

## 📦 Installation

```bash
composer require your-vendor/malaysia-public-holiday
```

---

## ⚡ Usage

```php
use MalaysiaPublicHoliday\MalaysiaPublicHoliday;

$holiday = new MalaysiaPublicHoliday();

// Get all holidays for all states in 2025
$data = $holiday->fromAllState(2025)->get();

// Get holidays for Selangor only
$data = $holiday->fromState('Selangor')->get();

// Group holidays by month
$data = $holiday->fromState('Penang')->groupByMonth()->get();

// Filter only holidays in March
$data = $holiday->fromState('Kuala Lumpur')->filterByMonth(3)->get();
```

---

## 🧪 Running Tests

```bash
./vendor/bin/phpunit
```

---

## 📬 Contributing

Pull requests and suggestions are welcome! If you find bugs or have a feature request, feel free to [open an issue](https://github.com/utrodus/malaysia-public-holiday/issues).

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---