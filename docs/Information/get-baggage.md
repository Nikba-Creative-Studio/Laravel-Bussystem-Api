---
layout: default
title: get_baggage
description: Get detailed baggage options and pricing for specific routes
nav_order: 1
parent: Information
---

# get_baggage

Get detailed baggage list with dimensions, weight limits, and pricing for specific routes.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_baggage.php`  
**Method:** POST  
**Type:** Optional function

---

## Description

This function provides detailed baggage information for routes that support it. You can only use this request if the `get_routes` response contains `request_get_baggage = 1`.

### Alternative Baggage Information

Baggage information is also available in other endpoints:

- **`get_routes`**: The `comfort` tag may contain `1_baggage_free` parameter and the `luggage` tag provides general text information
- **`get_all_routes`**: The `baggage` tag may contain detailed baggage information similar to `get_baggage`

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
| `station_from_id` | integer | ✓ | Departure station ID |
| `station_to_id` | integer | ✓ | Arrival station ID |

### Response Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `currency` | enum | Response currency: `EUR`, `RON`, `PLN`, `MDL`, `RUB`, `UAH`, `CZK` |
| `lang` | enum | Response language: `en`, `ru`, `ua`, `de`, `pl`, `cz` |

---

## Request Example

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_baggage.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "90|gh340|d29-96",
    "station_from_id" => 1547,
    "station_to_id" => 757,
    "currency" => "EUR",
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

## Response Example

```json
[
    {
        "baggage_id": "81",
        "baggage_type_id": "1",
        "baggage_type": "small_baggage",
        "baggage_type_abbreviated": "БАГАЖ М/М",
        "baggage_title": "Hand baggage",
        "length": "35",
        "width": "10",
        "height": "10",
        "kg": "5",
        "max_in_bus": "10",
        "max_per_person": "1",
        "typ": "route",
        "price": "0",
        "currency": "EUR"
    },
    {
        "baggage_id": "82",
        "baggage_type_id": "1",
        "baggage_type": "small_baggage",
        "baggage_type_abbreviated": "БАГАЖ М/М",
        "baggage_title": "Hand baggage",
        "length": "35",
        "width": "10",
        "height": "10",
        "kg": "5",
        "max_in_bus": "20",
        "max_per_person": "2",
        "typ": "route",
        "price": "5",
        "currency": "EUR"
    },
    {
        "baggage_id": "84",
        "baggage_type_id": "2",
        "baggage_type": "medium_baggage",
        "baggage_type_abbreviated": "БАГАЖ С/М",
        "baggage_title": "Cabin baggage",
        "length": "50",
        "width": "25",
        "height": "15",
        "kg": "8",
        "max_in_bus": "15",
        "max_per_person": "2",
        "typ": "route",
        "price": "10",
        "currency": "EUR"
    },
    {
        "baggage_id": "86",
        "baggage_type_id": "3",
        "baggage_type": "large_baggage",
        "baggage_type_abbreviated": "БАГАЖ С/М",
        "baggage_title": "Large baggage",
        "length": "55",
        "width": "35",
        "height": "20",
        "kg": "25",
        "max_in_bus": "10",
        "max_per_person": "1",
        "typ": "route",
        "price": "15",
        "currency": "EUR"
    }
]
```

---

## Response Fields Reference

### Baggage Identification

| Field | Type | Description |
|-------|------|-------------|
| `baggage_id` | string | **Critical:** Baggage ID (required for `new_order`) |
| `baggage_type_id` | string | Baggage type identifier |
| `baggage_type` | string | Baggage category (small_baggage, medium_baggage, large_baggage) |
| `baggage_type_abbreviated` | string | Abbreviated baggage type name |
| `baggage_title` | string | Human-readable baggage name |

### Physical Specifications

| Field | Type | Description |
|-------|------|-------------|
| `length` | string | Maximum baggage length (centimeters) |
| `width` | string | Maximum baggage width (centimeters) |
| `height` | string | Maximum baggage height (centimeters) |
| `kg` | string | Maximum baggage weight (kilograms) |

### Availability Limits

| Field | Type | Description |
|-------|------|-------------|
| `max_in_bus` | string | Maximum pieces of this baggage type allowed on bus |
| `max_per_person` | string | Maximum pieces of this baggage type per passenger |
| `typ` | string | Baggage scope (typically "route") |

### Pricing Information

| Field | Type | Description |
|-------|------|-------------|
| `price` | string | Baggage fee (0 for free baggage) |
| `currency` | string | Price currency |

---

## Baggage Types

### Small Baggage (Hand Baggage)
- **Typical dimensions:** 35×10×10 cm
- **Weight limit:** Up to 5 kg
- **Usage:** Carry-on items, personal belongings
- **Pricing:** Often free (price = 0) or low cost

### Medium Baggage (Cabin Baggage)
- **Typical dimensions:** 50×25×15 cm
- **Weight limit:** Up to 8 kg
- **Usage:** Small suitcases, travel bags
- **Pricing:** Moderate fee

### Large Baggage
- **Typical dimensions:** 55×35×20 cm
- **Weight limit:** Up to 25 kg
- **Usage:** Full-size suitcases, large travel bags
- **Pricing:** Higher fee

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

### Route Not Found
```json
{
    "error": "interval_no_activ"
}
```

### No Baggage Available
```json
[
    {
        "error": "baggage_not_found"
    }
]
```