---
layout: default
title: get_all_routes
description: Get detailed route schedule and station information
nav_order: 3
parent: Routes
---

# get_all_routes

Get detailed route schedule information including stations, timetables, baggage options, and cancellation policies.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_all_routes.php`  
**Method:** POST  
**Type:** Optional function

---

## Description

This function provides comprehensive route information only if the `timetable_id` from the `get_routes` request is not empty. It returns detailed schedule data, station stops, baggage options, discounts, and route policies.

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
| `timetable_id` | string | ✓ | Schedule ID from `get_routes` response |
| `lang` | enum | | Response language: `en`, `ru`, `ua`, `de`, `pl`, `cz` |

---

## Request Example

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_all_routes.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "timetable_id" => "90|gh340|d29-96",
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
        "route_id": "100441",
        "route_back_id": null,
        "buy": "1",
        "reserve": "0",
        "request": "1",
        "international": "1",
        "inland": "0",
        "lock_order": "1",
        "lock_min": "15",
        "reserve_min": "0",
        "start_sale_day": "180",
        "stop_sale_hours": "0",
        "cancel_free_min": "0",
        "route_name": "Praha-Kyiv",
        "carrier": "544578754",
        "comfort": null,
        "rating": "0",
        "reviews": "0",
        "bustype": "no_plan",
        "from": {
            "point_id": "3",
            "point_name": "Praha"
        },
        "to": {
            "point_id": "6",
            "point_name": "Kiev"
        },
        "schledules": {
            "days": "1,2,3,4,5,6,7",
            "regularity": "bus_days",
            "departure": "00:00:00",
            "time_in_way": "6:00"
        },
        "stations": [
            {
                "point_id": "3",
                "point_name": "Praha",
                "station_name": "Hlavní nádraží, platform 1",
                "platform": "1",
                "adp_id": "2386",
                "station_lat": "50.082894",
                "station_lon": "14.435624",
                "date_arrival": "2025-06-24",
                "arrival": "00:00:00",
                "date_departure": "2025-06-24",
                "departure": "00:00:00",
                "day_in_way": "0",
                "distance": "0"
            },
            {
                "point_id": "44",
                "point_name": "Brno",
                "station_name": "Benesova str, Bus Stop, platform 5",
                "platform": "5",
                "adp_id": "4595",
                "station_lat": "49.1935105",
                "station_lon": "16.6117738",
                "date_arrival": "2025-06-24",
                "arrival": "12:00:00",
                "date_departure": "2025-06-24",
                "departure": "12:05:00",
                "day_in_way": "0",
                "distance": "400"
            },
            {
                "point_id": "6",
                "point_name": "Kiev",
                "station_name": "Bus Station \"Pivdenna\", metro \"Ippodrom\", Ak.Glushlova str, 3, platform 7",
                "platform": "7",
                "adp_id": "4145",
                "station_lat": "50.379221",
                "station_lon": "30.4730799",
                "date_arrival": "2025-06-24",
                "arrival": "07:00:00",
                "date_departure": "2025-06-24",
                "departure": "07:00:00",
                "day_in_way": "0",
                "distance": "1000"
            }
        ],
        "intervals": [
            {
                "from_id": 3,
                "to_id": [
                    "44",
                    "6"
                ]
            },
            {
                "from_id": 44,
                "to_id": [
                    "6"
                ]
            }
        ],
        "discounts": null,
        "baggage": null,
        "luggage": "1 багаж",
        "route_info": "1 багаж",
        "driver_contacts_info": [],
        "cancel_hours_info": null,
        "route_foto": null,
        "regulations_url": "0",
        "trf": [
            {
                "price": 4144.2,
                "title": "Basic fare (seats available - 1000)",
                "currency": "MDL"
            }
        ]
    }
]
```

## Response Fields Reference

### Route Information

| Field | Type | Description |
|-------|------|-------------|
| `route_id` | string | Route identifier |
| `route_back_id` | string | Return route ID (may be null) |
| `route_name` | string | Full route name |
| `carrier` | string | Carrier name or ID |
| `bustype` | string | Bus type and seat count info |

### Availability and Booking

| Field | Type | Description |
|-------|------|-------------|
| `buy` | string | `1`=available for purchase, `0`=unavailable |
| `reserve` | string | `1`=available for reservation, `0`=unavailable |
| `request` | string | `1`=available for request, `0`=unavailable |
| `international` | string | `1`=international route, `0`=domestic |
| `inland` | string | `1`=inland route, `0`=not inland |

### Booking Policies

| Field | Type | Description |
|-------|------|-------------|
| `lock_order` | string | `1`=`new_order` blocks seats for `lock_min` minutes |
| `lock_min` | string | Minutes of seat blocking |
| `reserve_min` | string | Minutes of long-term reservation |
| `start_sale_day` | string | Days available for presale |
| `stop_sale_hours` | string | Hours before departure when sales stop |
| `cancel_free_min` | string | Minutes for free cancellation |

### Service Information

| Field | Type | Description |
|-------|------|-------------|
| `comfort` | string | Available services (wifi, tv, wc, etc.) |
| `rating` | string | Average rating from reviews |
| `reviews` | string | Number of reviews |
| `luggage` | string | Baggage transportation conditions |
| `route_info` | string | Additional route information |

### Route Endpoints

| Field | Type | Description |
|-------|------|-------------|
| `from` | object | Departure city information |
| `from.point_id` | string | Departure city ID |
| `from.point_name` | string | Departure city name |
| `to` | object | Arrival city information |
| `to.point_id` | string | Arrival city ID |
| `to.point_name` | string | Arrival city name |

### Schedule Information

| Field | Type | Description |
|-------|------|-------------|
| `schledules` | object | Trip timing information |
| `schledules.days` | string | Days of the week (e.g., "1,2,3,4,5,6,7") |
| `schledules.regularity` | string | Regularity of trips |
| `schledules.departure` | string | Departure time |
| `schledules.time_in_way` | string | Total travel time |

### Stations

| Field | Type | Description |
|-------|------|-------------|
| `stations` | array | List of stations on the route |
| `stations.point_id` | string | Station's city ID |
| `stations.point_name` | string | Station's city name |
| `stations.station_name` | string | Full station name |
| `stations.platform` | string | Platform number |
| `stations.adp_id` | string | Additional station ID |
| `stations.station_lat` | string | Station latitude |
| `stations.station_lon` | string | Station longitude |
| `stations.date_arrival` | string | Arrival date at station |
| `stations.arrival` | string | Arrival time at station |
| `stations.date_departure` | string | Departure date from station |
| `stations.departure` | string | Departure time from station |
| `stations.day_in_way` | string | Days from departure |
| `stations.distance` | string | Distance from origin (in km) |

### Route Segments

| Field | Type | Description |
|-------|------|-------------|
| `intervals` | array | Allowed travel segments |
| `intervals.from_id` | integer | Starting point ID for a segment |
| `intervals.to_id` | array | Destination point IDs from `from_id` |

### Tariffs

| Field | Type | Description |
|-------|------|-------------|
| `trf` | array | List of available fares |
| `trf.price` | float | Price of the fare |
| `trf.title` | string | Fare title/description |
| `trf.currency` | string | Currency code |