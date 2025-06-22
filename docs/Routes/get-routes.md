---
layout: default
title: get_routes
description: Search for available routes and schedules across all transport types
nav_order: 2
parent: Routes
---

# get_routes

Search for all available routes on selected city intervals for specified dates across bus, train, and air transport.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_routes.php`  
**Method:** POST  
**Type:** Core function

---

## Important Warnings

**Pricing Information**
The price should be used exclusively from the `new_order` booking request, as prices obtained during route search are for informational purposes only.

**Usage Restrictions**
It's not allowed to use `get_routes` function to check intervals in request loops or to build transfer systems with high request volumes for single users. The number of requests to the server must match the number of paid orders from real users, approximately 100:1. If this rule is not respected, access can be disabled.

**Time Zone Information**
- Departure time is shown in the departure city's time zone
- Arrival time is shown in the arrival city's time zone
- Time zone information can be obtained from the `get_points` query

---

## Parameters

### Authentication

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `login` | string | ✓ | Your API login |
| `password` | string | ✓ | Your API password |
| `session` | string | | Your session (recommended for additional routes) |
| `v` | string | | Request version |

### Location Parameters

| Parameter | Type | Transport | Description |
|-----------|------|-----------|-------------|
| `id_from` | integer | Bus | Departure city ID |
| `id_to` | integer | Bus | Arrival city ID |
| `point_train_from_id` | integer | Train | Departure train station ID |
| `point_train_to_id` | integer | Train | Arrival train station ID |
| `id_iata_from` | string | Air | Departure airport IATA code |
| `id_iata_to` | string | Air | Arrival airport IATA code |
| `station_id_from` | integer | Any | Specific departure station |
| `station_id_to` | integer | Any | Specific arrival station |

### Search Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `date` | date | Departure date (yyyy-mm-dd) |
| `only_by_stations` | integer | `1` = search by stations only, don't fall back to cities |
| `period` | integer | Number of days to search from specified date [-3..14] |
| `currency` | enum | Response currency: `EUR`, `RON`, `PLN`, `MDL`, `RUB`, `UAH`, `CZK` |
| `trans` | enum | Transport type: `all`, `bus`, `train`, `air`, `travel`, `hotel` |
| `sort_type` | enum | Sort by: `time`, `price` |
| `get_all_departure` | integer | `1` = include sold out routes |
| `ws` | integer | Route sources: `0`=all, `1`=server only, `2`=third-party only |
| `lang` | enum | Response language: `en`, `ru`, `ua`, `de`, `pl`, `cz` |

### Transfer Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `change` | string | Transfer availability: `auto`, `0`=direct only, `1`=domestic, `2-25`=external |
| `direct` | integer | Air transfers: `0`=with transfers, `1`=direct only |

### Air-Specific Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `service_class` | enum | Service class: `A`=all, `E`=economy, `B`=business |
| `adt` | integer | Number of adult passengers |
| `chd` | integer | Number of children under 12 |
| `inf` | integer | Number of infants under 2 |
| `baggage_no` | integer | Baggage filter: `0`=all routes, `1`=routes without baggage |

### Advanced Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `interval_id` | string | Previous flight interval for multi-route discounts |
| `route_id` | integer | Search only specified route ID |
| `search_type` | integer | `1`=all routes, `2`=OPEN tickets to buy, `3`=OPEN tickets to register |
| `find_order_id` | integer | Order ID for OPEN ticket registration |
| `find_ticket_id` | integer | Ticket ID for OPEN ticket registration |
| `find_security` | integer | Security code for OPEN ticket registration |

---

## Request Examples

### Bus Route Search

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_routes.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "date" => "2022-12-31",
    "id_from" => 3,        // Prague
    "id_to" => 6,          // Kyiv
    "trans" => "bus",
    "change" => "auto",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
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

### Train Route Search

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "date" => "2022-12-31",
    "point_train_from_id" => 2200001,  // Kyiv train station
    "point_train_to_id" => 5400076,    // Prague train station
    "trans" => "train",
    "change" => "auto",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
];
```

### Air Route Search

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "date" => "2022-12-31",
    "id_iata_from" => "PRG",      // Prague airport
    "id_iata_to" => "MIL*",       // Milan all airports
    "trans" => "air",
    "service_class" => "E",       // Economy
    "adt" => 1,                   // 1 adult
    "chd" => 0,                   // 0 children
    "inf" => 0,                   // 0 infants
    "direct" => 0,                // Allow connections
    "baggage_no" => 0,            // Include baggage routes
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
];
```

**IATA Code Note:** Use the `*` suffix for all airports in a city (e.g., `MIL*` for all Milan airports, `PAR*` for all Paris airports).

### OPEN Tickets Search

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "date" => "2022-12-31",
    "trans" => "all",
    "search_type" => 2,           // Search for OPEN tickets to buy
    "change" => "auto",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
];
```

---

## Response Structure

### Routes with Transfers

For routes with connections, the response includes a `trips` array containing each segment:

```json
{
    "trips": [
        {
            "trans": "bus",
            "interval_id": "8669|MzEzMD...M2MzVeMXw=|",
            "route_id": "8669"
        },
        {
            "trans": "train",
            "interval_id": "3077|2000000|2004000|01.11.2022|00:43|016А",
            "route_id": "3077",
            "change_stations": 0,
            "change_typ": "manual",
            "transfer_time": {
                "d": 0,
                "h": 0,
                "m": 15
            }
        },
        {
            "trans": "bus",
            "interval_id": "7152",
            "route_id": "100593",
            "change_stations": 0,
            "change_typ": "manual",
            "transfer_time": {
                "d": 0,
                "h": 1,
                "m": 20
            }
        }
    ]
}
```

**Important:** For routes with transfers, each flight segment requires separate API calls for:
- `get_all_routes`
- `get_discount`
- `get_free_seats`
- `get_plan`

The gathered information must be sent in one combined `new_order` request.

---

## Response Fields Reference

### Common Fields

| Field | Description |
|-------|-------------|
| `interval_id` | Unique ID for a route segment |
| `route_id` | ID of the main route |
| `route_name` | Full route name |
| `from_id` | Departure city ID |
| `to_id` | Arrival city ID |
| `from_name` | Departure city name |
| `to_name` | Arrival city name |
| `from_station_name` | Full departure station name |
| `to_station_name` | Full arrival station name |
| `date_from` | Departure date |
| `time_from` | Departure time |
| `date_to` | Arrival date |
| `time_to` | Arrival time |
| `time_in_way` | Total travel time |
| `price` | Informational price (use `new_order` for final price) |
| `currency` | Currency code |
| `currency_view` | Currency symbol |
| `free_seats` | Number of available seats |
| `carrier` | Carrier name |
| `carrier_id` | Carrier ID |
| `buy` | `1`=available for purchase |
| `reserve` | `1`=available for reservation |
| `request` | `1`=available on request |
| `trans` | Transport type |
| `bustype` | Bus type and seat count info |
| `international` | `1`=international route |
| `is_transfer` | `1`=route has transfers |

### Transfer Information

| Field | Description |
|-------|-------------|
| `change_stations` | Station change required: `0`=no, `1`=yes |
| `change_typ` | Transfer type (e.g., `manual`) |
| `transfer_time.d` | Transfer duration (days) |
| `transfer_time.h` | Transfer duration (hours) |
| `transfer_time.m` | Transfer duration (minutes) |

### Additional Fields

| Field | Description |
|-------|-------------|
| `comfort` | Available services (wifi, tv, wc, etc.) |
| `rating` | Average rating from reviews |
| `reviews` | Number of reviews |
| `route_info` | Additional route information |
| `lock_order` | `1`=`new_order` blocks seats |
| `lock_min` | Minutes of seat blocking |
| `start_sale_day` | Days available for presale |
| `stop_sale_hours` | Hours before departure when sales stop |
| `cancel_free_min` | Minutes for free cancellation |
| `regulations_url` | URL to route regulations |
| `has_dynamic_price` | `1`=price may change |
| `is_ws_route` | `1`=third-party route |
| `ws_carrier` | Third-party carrier name |
| `ws_name` | Third-party source name |

### OPEN Ticket Fields

| Field | Description |
|-------|-------------|
| `is_open_ticket` | `1`=is an OPEN ticket |
| `open_ticket_info` | Information about the OPEN ticket |
``` 