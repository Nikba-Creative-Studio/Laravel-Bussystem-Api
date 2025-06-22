---
layout: default
title: get_points
description: Search and filter available cities, countries, stations, and airports
nav_order: 1
parent: Routes
---

# get_points

Search for available cities, countries, stations, and airports with dynamic filtering capabilities.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_points.php`  
**Method:** POST  
**Type:** Optional function

---

## Description

The `get_points` endpoint provides comprehensive location search functionality with multiple filtering options. It supports autocomplete search, geographic filtering, transport-specific results, and various grouping options for cities, stations, and airports.

---

## Parameters

### Authentication

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `login` | string | ✓ | Your API login |
| `password` | string | ✓ | Your API password |

### Filtering Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `lang` | enum | Response language: `en`, `ru`, `ua`, `de`, `pl`, `cz` |
| `country_id` | integer | Filter by specific country ID |
| `point_id_from` | integer | Show cities reachable from specified point |
| `point_id_to` | integer | Show cities from which you can reach specified point |
| `autocomplete` | string | Filter by matching initial characters |
| `trans` | enum | Transport type: `all`, `bus`, `train`, `air`, `travel`, `hotel` |
| `all` | integer | `0` = popular cities only, `1` = all cities including towns/villages |

### Geographic Filtering

| Parameter | Type | Description |
|-----------|------|-------------|
| `boundLatSW` | float | GPS southwest latitude |
| `boundLonSW` | float | GPS southwest longitude |
| `boundLatNE` | float | GPS northeast latitude |
| `boundLonNE` | float | GPS northeast longitude |

### Response Types

| Parameter | Type | Description |
|-----------|------|-------------|
| `viev` | enum | `get_country` = list of countries, `group_country` = cities grouped by countries |
| `group_by_point` | integer | `1` = include stations for each city |
| `group_by_iata` | integer | `1` = include airports for each city (air transport) |

---

## Request Examples

### Autocomplete Search

Filter cities by name matching:

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_points.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "autocomplete" => "Prague",
    "lang" => "en"
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $post_data,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json']
]);

$response = curl_exec($curl);
curl_close($curl);
```

### Filter by Country

Get all cities within a specific country:

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "country_id" => 1,  // Czech Republic
    "lang" => "en"
];
```

### Filter by Departure Point

Find cities reachable from a specific point:

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "point_id_from" => 3,  // Prague
    "lang" => "en"
];
```

### Filter by Transport Type

Get locations for specific transport:

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "trans" => "train",
    "lang" => "en"
];
```

### Group by Country

Get cities organized by countries:

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "viev" => "group_country",
    "lang" => "en"
];
```

### Get Countries List

Retrieve available countries:

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "viev" => "get_country",
    "lang" => "en"
];
```

### Get Stations List

Include station details for each city:

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_points.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "group_by_point" => 1,
    "lang" => "en"
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $post_data
]);

$response = curl_exec($curl);
curl_close($curl);
echo $response;
```

### Get Airports List

Include airport details for air transport:

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_points.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "trans" => "air",
    "group_by_iata" => 1,
    "lang" => "en"
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $post_data
]);

$response = curl_exec($curl);
curl_close($curl);
echo $response;
```

---

## Response Examples

### Bus Stations Response

```json
[
    {
        "point_id": "90",
        "point_latin_name": "Moskva",
        "point_ru_name": "Москва",
        "point_ua_name": "Москва",
        "point_name": "Moskva",
        "country_name": "Russia",
        "country_kod": "RUS",
        "country_kod_two": "RU",
        "country_id": "5",
        "latitude": "55.609899",
        "longitude": "37.719727",
        "population": "11500000",
        "point_name_detail": "",
        "currency": "RUB",
        "time_zone": 3
    },
    {
        "point_id": "2",
        "point_latin_name": "Minsk",
        "point_ru_name": "Минск",
        "point_ua_name": "Минск",
        "point_name": "Minsk",
        "country_name": "Belarus",
        "country_kod": "BLR",
        "country_kod_two": "BY",
        "country_id": "4",
        "latitude": "53.8911344853093",
        "longitude": "27.5510821236877",
        "population": "11000000",
        "point_name_detail": "",
        "currency": "BYN",
        "time_zone": 3
    },
    {
        "point_id": "257",
        "point_latin_name": "London",
        "point_ru_name": "Лондон",
        "point_ua_name": "Лондон",
        "point_name": "London",
        "country_name": "Great Britain",
        "country_kod": "GBR",
        "country_kod_two": "GB",
        "country_id": "24",
        "latitude": "51.4924011",
        "longitude": "-0.149627",
        "population": "8173900",
        "point_name_detail": "",
        "currency": "GBP",
        "time_zone": 0
    }
]
```

### Train Stations Response

```json
[
    {
        "train_station_id": "8000101",
        "country_id": "14",
        "latitude": "52.507018",
        "longitude": "13.279788",
        "station_name": "BERLIN",
        "country_name": "Germany",
        "currency": "EUR",
        "time_zone": "1"
    },
    {
        "train_station_id": "2200001",
        "country_id": "2",
        "latitude": "50.440058",
        "longitude": "30.488309",
        "station_name": "KYIV-PASAZHYRSKYI",
        "country_name": "Ukraine",
        "currency": "UAH",
        "time_zone": "2"
    },
    {
        "train_station_id": "8799015",
        "country_id": "13",
        "latitude": "48.880857",
        "longitude": "2.352726",
        "station_name": "PARIS",
        "country_name": "France",
        "currency": "EUR",
        "time_zone": "1"
    },
    {
        "train_station_id": "5400076",
        "country_id": "1",
        "latitude": "50.082931",
        "longitude": "14.435583",
        "station_name": "PRAHA",
        "country_name": "Czech",
        "currency": "CZK",
        "time_zone": "1"
    }
]
```

### Airports Response

```json
[
    {
        "iata_code": "BKA",
        "iso_code": "RU",
        "latitude": "55.620556",
        "longitude": "38.056389",
        "country_id": "5",
        "name_rus": "Москва Быково",
        "name_eng": "Moscow Bykovo",
        "city_rus": "Москва",
        "city_eng": "Moscow",
        "country_rus": "Россия",
        "country_eng": "Russia",
        "airport_name": "Moscow Bykovo",
        "city_name": "Moscow",
        "country_name": "Russia"
    },
    {
        "iata_code": "DME",
        "iso_code": "RU",
        "latitude": "55.4",
        "longitude": "37.8833",
        "country_id": "5",
        "name_rus": "Москва Домодедово",
        "name_eng": "Moscow Domodedovo",
        "city_rus": "Москва",
        "city_eng": "Moscow",
        "country_rus": "Россия",
        "country_eng": "Russia",
        "airport_name": "Moscow Domodedovo",
        "city_name": "Moscow",
        "country_name": "Russia"
    },
    {
        "iata_code": "OSF",
        "iso_code": "RU",
        "latitude": "55.511667",
        "longitude": "37.507222",
        "country_id": null,
        "name_rus": "Москва Остафьево",
        "name_eng": "Moscow Ostafyevo",
        "city_rus": "Москва",
        "city_eng": "Moscow",
        "country_rus": "Россия",
        "country_eng": "Russia",
        "airport_name": "Moscow Ostafyevo",
        "city_name": "Moscow",
        "country_name": "Russia"
    }
]
```

### Grouped by Country Response

```json
{
    "5": {
        "county_name": "Russia",
        "currency": "RUB",
        "time_zone": 3,
        "points": [
            {
                "point_id": "90",
                "point_name": "Moskva",
                "point_name_detail": ""
            },
            {
                "point_id": "381",
                "point_name": "Sankt-Peterburg",
                "point_name_detail": ""
            },
            {
                "point_id": "160",
                "point_name": "Rostov-na-Donu",
                "point_name_detail": ""
            }
        ]
    },
    "4": {
        "county_name": "Belarus",
        "currency": "BYN",
        "time_zone": 3,
        "points": [
            {
                "point_id": "2",
                "point_name": "Minsk",
                "point_name_detail": ""
            },
            {
                "point_id": "5775",
                "point_name": "Minsk airport",
                "point_name_detail": ""
            },
            {
                "point_id": "568",
                "point_name": "Gomel",
                "point_name_detail": ""
            }
        ]
    }
}
```

### Countries List Response

```json
[
    {
        "id": "159",
        "country_id": "159",
        "country_kod": "AFG",
        "country_kod_two": "AF",
        "country_name": "Afghanistan",
        "currency": "AFN",
        "time_zone": 4
    },
    {
        "id": "50",
        "country_id": "50",
        "country_kod": "ALB",
        "country_kod_two": "AL",
        "country_name": "Albania",
        "currency": "ALB",
        "time_zone": 2
    },
    {
        "id": "216",
        "country_id": "216",
        "country_kod": "ALG",
        "country_kod_two": "DZ",
        "country_name": "Algeria",
        "currency": "DZD",
        "time_zone": 1
    },
    {
        "id": "177",
        "country_id": "177",
        "country_kod": "AND",
        "country_kod_two": "AD",
        "country_name": "Andorra",
        "currency": "EUR",
        "time_zone": 1
    }
]
```

### Stations List Response

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <item>
        <point_id>6</point_id>
        <point_latin_name>Kyiv</point_latin_name>
        <point_ru_name>Киев</point_ru_name>
        <point_ua_name>Київ</point_ua_name>
        <point_name>Киев</point_name>
        <country_name>Украина</country_name>
        <country_kod>UKR</country_kod>
        <country_kod_two>UA</country_kod_two>
        <country_id>2</country_id>
        <latitude>50.440684</latitude>
        <longitude>30.490012</longitude>
        <population>2868702</population>
        <point_name_detail></point_name_detail>
        <currency>UAH</currency>
        <time_zone>2</time_zone>
        <stations>
            <item>
                <id>45</id>
                <name>Автовокзал "Дачная", пр.Победы 142</name>
                <latitude>50.45531</latitude>
                <longitude>30.350182</longitude>
            </item>
            <item>
                <id>46</id>
                <name>Ж/Д Вокзал "Южный", ул.Петрозаводская</name>
                <latitude>50.4390653171262</latitude>
                <longitude>30.4842871427536</longitude>
            </item>
            <item>
                <id>3933</id>
                <name>Метро "Дарница", McDonalds</name>
                <latitude>50.456521</latitude>
                <longitude>30.612588</longitude>
            </item>
        </stations>
    </item>
</root>
```

### Airports List Response

```json
[
    {
        "point_id": "90",
        "point_latin_name": "Moskva",
        "point_ru_name": "Москва",
        "point_ua_name": "Москва",
        "point_name": "Moskva",
        "country_name": "Russia",
        "country_kod": "RUS",
        "country_kod_two": "RU",
        "country_id": "5",
        "latitude": "55.609899",
        "longitude": "37.719727",
        "population": "11500000",
        "point_name_detail": "",
        "currency": "RUB",
        "time_zone": 3
    },
    {
        "point_id": "2",
        "point_latin_name": "Minsk",
        "point_ru_name": "Минск",
        "point_ua_name": "Минск",
        "point_name": "Minsk",
        "country_name": "Belarus",
        "country_kod": "BLR",
        "country_kod_two": "BY",
        "country_id": "4",
        "latitude": "53.8911344853093",
        "longitude": "27.5510821236877",
        "population": "11000000",
        "point_name_detail": "",
        "currency": "BYN",
        "time_zone": 3
    },
    {
        "point_id": "257",
        "point_latin_name": "London",
        "point_ru_name": "Лондон",
        "point_ua_name": "Лондон",
        "point_name": "London",
        "country_name": "Great Britain",
        "country_kod": "GBR",
        "country_kod_two": "GB",
        "country_id": "24",
        "latitude": "51.4924011",
        "longitude": "-0.149627",
        "population": "8173900",
        "point_name_detail": "",
        "currency": "GBP",
        "time_zone": 0
    }
]
```

---

## Use Cases

### Autocomplete Implementation

```php
// User types "Pra" - get matching cities
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "autocomplete" => "Pra",
    "lang" => "en",
    "all" => 0  // Popular cities only
];
```

### Route Planning

```php
// Find destinations from Prague
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "point_id_from" => 3,  // Prague
    "trans" => "bus",
    "lang" => "en"
];
```

### Geographic Search

```php
// Find cities in specific geographic area
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "boundLatSW" => 48.5518083,
    "boundLonSW" => 12.0905901,
    "boundLatNE" => 51.0557036,
    "boundLonNE" => 18.859216,
    "lang" => "en"
];
```

---

## Key Features

**Flexible Filtering:**
- Text-based autocomplete search
- Country and transport type filtering
- Geographic bounding box search
- Connection-based filtering (reachable destinations)

**Multiple Transport Types:**
- Bus stations with detailed location data
- Train stations with railway-specific IDs
- Airports with IATA codes
- Combined transport search

**Organized Responses:**
- Flat list of locations
- Grouped by country structure
- Stations/airports included per city
- Countries list with multilingual names

**Rich Location Data:**
- GPS coordinates for mapping
- Population data for cities
- Currency and timezone information
- Multiple language variants for names

**Important Notes:**
- Routes returned by `point_id_from`/`point_id_to` may not reflect reality if third-party systems are connected
- Use `all=0` for better performance with popular cities only
- Language parameter affects sorting (alphabetical) and display names
- Geographic filtering is useful for mobile applications with location services