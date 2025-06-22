---
layout: default
title: get_free_seats
description: Search for available seat numbers and wagon information
nav_order: 1
parent: Seats
---

# get_free_seats

Search for available seat numbers for buses and trains, including wagon selection for train travel.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_free_seats.php`  
**Method:** POST  
**Type:** Conditional function

---

## Description

This function provides detailed seat availability information for routes that support it. You can only use this request if the `get_routes` response contains `request_get_free_seats = 1`.

For routes where this parameter is `0`, use the seat numbers provided directly in the `get_routes` response for booking.

---

## Parameters

### Authentication

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `login` | string | ✓ | Your API login |
| `password` | string | ✓ | Your API password |
| `session` | string | | Your session (recommended for additional routes) |

### Route Information

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `interval_id` | string | ✓ | Interval ID from `get_routes` response |

### Train-Specific Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `train_id` | string | For trains | Train number from `get_routes` response |
| `vagon_id` | string | For train seats | Wagon ID from wagon search response |

### Response Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `currency` | enum | Response currency: `EUR`, `RON`, `PLN`, `MDL`, `RUB`, `UAH`, `CZK` |
| `lang` | enum | Response language: `en`, `ru`, `ua`, `de`, `pl`, `cz` |

---

## Request Examples

### Bus Seat Search

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_free_seats.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "90|gh340|d29-96",
    "currency" => "EUR"
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

### Train Wagon Search

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "train|141Ш КИЕВ-ПАССАЖИРСКИЙ - ЛЬВОВ|2200001|2218000|19.11.2022|141Ш|12:53|2022-11-19|2022-11-20|15:45:00|04:38:00",
    "train_id" => "141Ш",
    "lang" => "en",
    "currency" => "EUR"
];
```

### Train Seat Search

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "train|141Ш КИЕВ-ПАССАЖИРСКИЙ - ЛЬВОВ|2200001|2218000|19.11.2022|141Ш|12:53|2022-11-19|2022-11-20|15:45:00|04:38:00",
    "vagon_id" => "14БЛБ",
    "currency" => "EUR"
];
```

---

## Response Examples

### Bus Seats Response

```json
[
    {
        "bustype_id": "12",
        "has_plan": "1",
        "free_seat": [
            {
                "seat_number": "31",
                "seat_free": "1",
                "seat_price": "16",
                "seat_provision": "1.6",
                "seat_curency": "EUR"
            },
            {
                "seat_number": "32",
                "seat_free": "1",
                "seat_price": "16",
                "seat_provision": "1.6",
                "seat_curency": "EUR"
            },
            {
                "seat_number": "47",
                "seat_free": "1",
                "seat_price": "15",
                "seat_provision": "1.5",
                "seat_curency": "EUR"
            }
        ]
    }
]
```

### Train Wagons Response

```json
[
    {
        "train": "141Ш KYIV-PASAZHYRSKY - LVIV",
        "date_from": "2022-11-19",
        "date_to": "2022-11-20",
        "time_from": "15:45:00",
        "time_to": "04:38:00",
        "point_from": "КИЕВ-ПАССАЖИРСКИЙ",
        "point_to": "ЛЬВОВ",
        "free_seats": "21",
        "vagon": [
            {
                "vagon_id": "14БЛБ",
                "vagon_number": "14",
                "vagon_gender": "0",
                "has_plan": "1",
                "vagon_type": "L",
                "vagon_class": "Б",
                "price": "695.12",
                "provision": "6.95",
                "currency": "UAH",
                "Up": "0",
                "Down": "14",
                "SideUp": "0",
                "SideDown": "0",
                "free_seats": "14"
            },
            {
                "vagon_id": "13БКБ",
                "vagon_number": "13",
                "vagon_gender": "0",
                "has_plan": "1",
                "vagon_type": "K",
                "vagon_class": "Б",
                "price": "298.84",
                "provision": "2.98",
                "currency": "UAH",
                "Up": "4",
                "Down": "3",
                "SideUp": "0",
                "SideDown": "0",
                "free_seats": "7"
            }
        ]
    }
]
```

### Train Seats Response

```json
[
    {
        "coupe_number": "1",
        "free_seat": [
            {
                "seat_number": "1",
                "seat_free": "1",
                "seat_type": "V",
                "seat_price": "20",
                "seat_provision": "2",
                "seat_curency": "EUR"
            },
            {
                "seat_number": "2",
                "seat_free": "1",
                "seat_type": "N",
                "seat_price": "20",
                "seat_provision": "2",
                "seat_curency": "EUR"
            },
            {
                "seat_number": "53",
                "seat_free": "0",
                "seat_type": "VB",
                "seat_price": "20",
                "seat_provision": "2",
                "seat_curency": "EUR"
            }
        ]
    }
]
```

---

## Response Fields Reference

### Bus Seat Information

| Field | Type | Description |
|-------|------|-------------|
| `bustype_id` | string | Bus type identifier for plan lookup |
| `has_plan` | string | `1`=seat plan available, `0`=no plan |

### Train Route Information

| Field | Type | Description |
|-------|------|-------------|
| `train` | string | Train name and route |
| `date_from`, `date_to` | string | Departure and arrival dates |
| `time_from`, `time_to` | string | Departure and arrival times |
| `point_from`, `point_to` | string | Departure and arrival stations |
| `free_seats` | string | Total available seats on train |

### Wagon Information

| Field | Type | Description |
|-------|------|-------------|
| `vagon_id` | string | **Critical:** Wagon ID (required for seat search) |
| `vagon_number` | string | Wagon number |
| `vagon_gender` | string | `0`=mixed, `1`=gender-separated |
| `has_plan` | string | `1`=layout available, `0`=no layout |
| `vagon_type` | string | L=Luxe, M=Soft, K=Coupe, P=Standard, S=Seat, O=General |
| `vagon_class` | string | Service class description |
| `price` | string | Price per seat in this wagon |
| `provision` | string | Agency fee |
| `currency` | string | Price currency |
| `free_seats` | string | Available seats in this wagon |

### Seat Position Counts (Train)

| Field | Type | Description |
|-------|------|-------------|
| `Up` | string | Upper berth seats available |
| `Down` | string | Lower berth seats available |
| `SideUp` | string | Upper side berth seats available |
| `SideDown` | string | Lower side berth seats available |

### Individual Seat Information

| Field | Type | Description |
|-------|------|-------------|
| `seat_number` | string | Seat identifier |
| `seat_free` | string | `1`=available, `0`=occupied |
| `seat_price` | string | Price for this specific seat |
| `seat_provision` | string | Agency fee for this seat |
| `seat_curency` | string | Price currency |

### Train Seat Types

| Field | Type | Description |
|-------|------|-------------|
| `seat_type` | string | V=Upper, N=Lower, VB=Side Upper, NB=Side Lower, S=Sitting |
| `seat_type_descr` | string | Localized seat type description |
| `seat_gender` | string | M=Male, F=Female (for gender-separated wagons) |
| `coupe_number` | string | Compartment number (for compartment cars) |

---

## Workflow Examples

### Bus Seat Selection
1. Call `get_free_seats` with `interval_id`
2. Display available seats with pricing
3. Allow user to select preferred seats
4. Use selected seat numbers in booking

### Train Seat Selection
1. Call `get_free_seats` with `interval_id` and `train_id` to get wagons
2. Display wagon options with types and pricing
3. Call `get_free_seats` with `vagon_id` to get seats in selected wagon
4. Display seat layout (optionally with `get_plan`)
5. Allow user to select specific seats
6. Use wagon and seat information in booking

---

## Error Responses

### Dealer Not Active
```json
{
    "error": "dealer_no_activ",
    "detal": "Dealer not active"
}
```

### Route Not Active
```json
{
    "error": "route_no_activ"
}
```

### Routes Not Found
```json
{
    "error": "interval_no_found"
}
```

### No Seats Available
```json
{
    "error": "no_seat",
    "detal": "No seats"
}
```

### No Seat Information
```json
{
    "error": "no_inforamtion",
    "detal": "No information about seats"
}
```

**Note:** The "no_inforamtion" error can be temporary - the request can be repeated.

---

## Important Notes

### Prerequisites
- Only available when `request_get_free_seats = 1` in `get_routes` response
- For routes where this flag is `0`, use seat numbers from `get_routes` directly

### Train Specifics
- Requires two-step process: first get wagons, then get seats
- Wagon types determine comfort level and pricing
- Gender-separated wagons available on some routes
- Compartment numbers help with seat organization

### Pricing Information
- Seat prices may vary within the same vehicle
- Prices shown are informational - use `new_order` for final pricing
- Provision fees may apply per seat

### Integration
- Use `bustype_id` or `vagon_id` with `get_plan` for visual layouts
- Combine with `get_plan` for complete seat selection interfaces
- Essential for routes requiring specific seat assignments