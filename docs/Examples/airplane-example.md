---
layout: default
title: Airplane Example
description: Complete airplane booking workflow with round-trip flights and connections
nav_order: 3
parent: Examples
---

# Airplane Booking Example

This comprehensive example demonstrates an airplane booking workflow from Warsaw to Milan with return flights, including route search, booking, payment, and cancellation.

## Scenario Overview

**Route:** Warsaw ↔ Milan  
**From:** Warsaw (Frederic Chopin), Poland  
**To:** Milan (Malpensa), Italy  
**Departure Date:** 2024-10-10  
**Return Date:** 2024-10-20  
**Transport:** Airplane  
**Transfers:** Including external flights  
**Baggage:** Only flights with included baggage  
**Service Class:** Economy  
**Currency:** EUR  
**Language:** English  

**Journey Details:**

**Outbound:**
- 17:00 Warsaw → Zurich (Swiss LX4503)
- Transfer: 3h 35m
- 22:40 Zurich → Milan (Swiss LX1638)

**Return:**
- 05:55 Milan → Zurich (Swiss LX1639)
- Transfer: 3h 35m  
- 10:30 Zurich → Warsaw (Swiss LX4500)

**Passengers:**
- **Anna Ivanova** (Adult, born 1992-01-01)
- **Masha Ivanova** (Child, born 2020-02-02)


## 1. Flight Search

### Search Round-Trip Flights

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_routes.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "id_iata_from" => [
        "WAW",    // Warsaw outbound
        "MXP"     // Milan return
    ],
    "id_iata_to" => [
        "MXP",    // Milan outbound
        "WAW"     // Warsaw return
    ],
    "date" => [
        "2024-10-10",    // Outbound date
        "2024-10-20"     // Return date
    ],
    "trans" => "air",
    "period" => "0",
    "direct" => "0",
    "get_all_departure" => "0",
    "adt" => "1",            // 1 adult
    "chd" => "1",            // 1 child
    "inf" => "0",            // 0 infants
    "service_class" => "E",   // Economy
    "baggage" => "1",        // Only flights with baggage
    "currency" => "EUR",
    "lang" => "en",
    "v" => "2.0"
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $post_data,
    CURLOPT_HTTPHEADER => ['Accept: application/json']
]);

$response = curl_exec($curl);
curl_close($curl);
```

**Key Response Data:**
```json
{
    "trans": "air",
    "interval_id": "1597|38d577...2608554_4449^^6|WAW|MXP|route_name:0JLQsN...0LXQvSk=|0|2024-10-01T10:27:18||af0c72bb",
    "has_plan": 0,
    "request_get_free_seats": 0,
    "request_get_discount": 0,
    "need_orderdata": "1",
    "need_birth": "1",
    "need_doc": "1",
    "need_doc_expire_date": 1,
    "need_citizenship": "1",
    "need_gender": "1",
    "max_seats": "10",
    "price": 475.22,
    "currency": "EUR",
    "routes": [
        {
            "route_index": 0,
            "route_duration": 395,
            "time_in_way": "06:35",
            "segments": [
                {
                    "segment_index": 0,
                    "departure_city_name": "Warsaw",
                    "departure_airport_name": "Warsaw (Frederic Chopin)",
                    "departure_country_name": "Poland",
                    "departure_country": "PL",
                    "departure_city": "WAW",
                    "departure_airport": "WAW",
                    "departure_time": "10.10.2024 17:00",
                    "arrival_city_name": "Zurich",
                    "arrival_airport_name": "Zurich",
                    "arrival_country_name": "Switzerland",
                    "arrival_country": "CH",
                    "arrival_city": "ZRH",
                    "arrival_airport": "ZRH",
                    "arrival_time": "10.10.2024 19:05",
                    "supplier": "Swiss",
                    "supplier_code": "LX",
                    "flight_number": "4503",
                    "aircraft_code": "295",
                    "aircraft": "295",
                    "service_class_type": "economy",
                    "service_class": "T",
                    "baggage": "1PC"
                },
                {
                    "segment_index": 1,
                    "departure_city_name": "Zurich",
                    "departure_airport_name": "Zurich",
                    "departure_time": "10.10.2024 22:40",
                    "arrival_city_name": "Milan",
                    "arrival_airport_name": "Milan Malpensa",
                    "arrival_terminal": "1",
                    "arrival_time": "10.10.2024 23:35",
                    "supplier": "Swiss",
                    "supplier_code": "LX",
                    "flight_number": "1638",
                    "aircraft_code": "E95",
                    "aircraft": "Embraer 195",
                    "service_class_type": "economy",
                    "service_class": "T",
                    "baggage": "1PC",
                    "transfer_time": {
                        "d": 0,
                        "h": 3,
                        "m": 35
                    }
                }
            ]
        },
        {
            "route_index": 1,
            "route_duration": 395,
            "time_in_way": "06:35",
            "segments": [
                {
                    "segment_index": 0,
                    "departure_city_name": "Milan",
                    "departure_airport_name": "Milan Malpensa",
                    "departure_time": "20.10.2024 05:55",
                    "arrival_city_name": "Zurich",
                    "arrival_airport_name": "Zurich",
                    "arrival_time": "20.10.2024 06:55",
                    "supplier": "Swiss",
                    "supplier_code": "LX",
                    "flight_number": "1639",
                    "aircraft_code": "E95",
                    "aircraft": "Embraer 195",
                    "service_class_type": "economy",
                    "service_class": "S",
                    "baggage": "1PC"
                },
                {
                    "segment_index": 1,
                    "departure_city_name": "Zurich",
                    "departure_airport_name": "Zurich",
                    "departure_time": "20.10.2024 10:30",
                    "arrival_city_name": "Warsaw",
                    "arrival_airport_name": "Warsaw (Frederic Chopin)",
                    "arrival_time": "20.10.2024 12:30",
                    "supplier": "Swiss",
                    "supplier_code": "LX",
                    "flight_number": "4500",
                    "aircraft_code": "7M8",
                    "aircraft": "Boeing 737 MAX 8",
                    "service_class_type": "economy",
                    "service_class": "S",
                    "baggage": "1PC",
                    "transfer_time": {
                        "d": 0,
                        "h": 3,
                        "m": 35
                    }
                }
            ]
        }
    ]
}
```


## 2-5. Simplified Flight Services

### Seat Availability
**Not available for airplanes.** Seat selection is handled by the airline.

### Seat Maps  
**Not available for airplanes.** Seat maps are not provided through the API.

### Discount Lists
**Not available for airplanes.** Discounts are automatically applied based on passenger type (child/adult).

### Baggage Lists
**Not available for airplanes.** Baggage allowance is included in flight search results (e.g., "1PC" = 1 piece).

**Note:** When searching with `"baggage": "1"`, only flights with included baggage are returned.


## 6. Booking

### Create Flight Order

```php
$url = 'https://test-api.bussystem.eu/server/curl/new_order.php';

$booking_data = [
    "login" => "your_login",
    "password" => "your_password",
    
    // No date array needed for air - determined from flight search
    
    "interval_id" => [
        "1597|38d577...2608554_4449^^6|WAW|MXP|route_name:0JLQsN...0LXQvSk=|0|2024-10-01T10:27:18||af0c72bb"
    ],
    
    // Passenger types (not specific seats)
    "seat" => [
        [
            "adt",    // Adult passenger
            "chd"     // Child passenger  
        ]
    ],
    
    // Passenger details
    "name" => ["Anna", "Masha"],
    "surname" => ["Ivanova", "Ivanova"],
    "gender" => ["F", "F"],
    "birth_date" => ["1992-01-01", "2020-02-02"],
    "citizenship" => ["UA", "UA"],
    "doc_type" => ["1", "1"],           // 1 = Passport
    "doc_number" => ["DH456234", "FM167835"],
    "doc_expire_date" => ["2030-11-11", "2030-08-08"],
    
    // Contact information
    "phone" => "+375291234567",
    "email" => "info@test-mail.en",
    "currency" => "EUR",
    "lang" => "en"
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $booking_data,
    CURLOPT_HTTPHEADER => ['Accept: application/json']
]);

$response = curl_exec($curl);
curl_close($curl);
```

**Response:**
```json
{
    "order_id": 1044455,
    "reservation_until": "2024-10-01 10:29:34",
    "reservation_until_min": "709",
    "security": "687577", 
    "status": "reserve_ok",
    "price_total": 500.62,  // 269.26 + 231.36
    "currency": "EUR",
    "promocode_info": {
        "promocode_valid": 0
    },
    "0": {
        "trip_id": 0,
        "interval_id": "1597|38d577...af0c72bb",
        "route_id": "1597",
        "trans": "air",
        "date_from": "2024-10-10",
        "time_from": "14:50",
        "point_from": "Warsaw",
        "station_from": "Warsaw (Frederic Chopin)",
        "date_to": "2024-10-10", 
        "time_to": "16:50",
        "point_to": "Zurich",
        "station_to": "Zurich",
        "route_name": "1349",
        "carrier": "LX",
        "supplier_code": "LX",
        "passengers": [
            {
                "passenger_id": 0,
                "transaction_id": 4011855958,
                "name": "Anna",
                "surname": "Ivanova",
                "doc_type": "1",
                "doc_number": "DH456234",
                "birth_date": "1990-11-11",
                "citizenship": "UA",
                "gender": "F",
                "doc_expire_date": "2030-11-11",
                "seat": "adt",
                "discount": 0,
                "price": 269.26
            },
            {
                "passenger_id": 1,
                "transaction_id": 4011855959,
                "name": "Masha",
                "surname": "Ivanova",
                "doc_type": "1",
                "doc_number": "FM167835",
                "birth_date": "2015-08-08",
                "citizenship": "UA",
                "gender": "F",
                "doc_expire_date": "2030-08-08",
                "seat": "chd",
                "discount": "child under 12 years",
                "price": 231.36
            }
        ]
    },
    "1": {
        "trip_id": 1,
        "date_from": "2024-10-10",
        "time_from": "17:30",
        "point_from": "Zurich",
        "point_to": "Milan",
        "station_to": "Milan Malpensa, terminal 1",
        "route_name": "1622",
        "carrier": "LX"
    },
    "2": {
        "trip_id": 2,
        "date_from": "2024-10-20",
        "time_from": "08:35",
        "point_from": "Milan",
        "point_to": "Brussels",
        "route_name": "3154",
        "carrier": "SN"
    },
    "3": {
        "trip_id": 3,
        "date_from": "2024-10-20",
        "time_from": "15:20",
        "point_from": "Brussels",
        "point_to": "Warsaw",
        "route_name": "2555",
        "carrier": "SN"
    }
}
```


## 7. Payment

**Important:** Flights have extended reservation time (709 minutes = ~12 hours).

```php
$url = 'https://test-api.bussystem.eu/server/curl/buy_ticket.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "order_id" => 1044455,
    "lang" => "en",
    "v" => "1.1"
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $post_data,
    CURLOPT_HTTPHEADER => ['Accept: application/json']
]);

$response = curl_exec($curl);
curl_close($curl);
```

**Response:**
```json
{
    "order_id": 1026944,
    "price_total": 500.62,
    "currency": "EUR",
    "link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=1026944&security=687577&lang=en",
    "0": {
        "passenger_id": 0,
        "transaction_id": "4011855958",
        "ticket_id": "36015",
        "security": "761499",
        "price": 500.62,
        "provision": 50.06,
        "currency": "EUR",
        "link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?ticket_id=36015&security=761499&lang=en"
    },
    "1": {
        "trip_id": 1,
        "error": "route_no_activ"  // Expected for additional flight segments
    },
    "2": {
        "trip_id": 2,
        "error": "route_no_activ"  // Expected for additional flight segments
    },
    "3": {
        "trip_id": 3,
        "error": "route_no_activ"  // Expected for additional flight segments
    }
}
```

**Important Notes:**
- **Single Ticket:** Air bookings always generate one ticket for the entire itinerary
- **Total Price:** The ticket price equals the complete order cost
- **Error Messages:** Additional flight segments show errors - this is expected behavior


## 8. Ticket Generation

**Complete Flight Itinerary:**
```
Order Link: http://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=1026944&security=687577&lang=en
Ticket Link: http://test-api.bussystem.eu/viev/frame/print_ticket.php?ticket_id=36015&security=761499&lang=en
```

**Note:** Both links provide access to the complete flight itinerary for all passengers and all flight segments.


## 9. Cancellation

### 9.1. Refund Amount Calculation

**Important:** For flights, check only the first ticket (key "0") as it represents the entire order.

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_ticket.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "ticket_id" => 36015,
    "security" => 761499
];
```

**Example Refund Information:**
```json
{
    "price": 500.62,                    // Amount paid
    "cancel_rate": 0,                   // Cancellation fee (0%)
    "money_back_if_cancel": 500.62,     // Will be refunded
    "money_noback_if_cancel": 0,        // Will be withheld
    "currency": "EUR"
}
```

### 9.2. Individual Ticket Cancellation

**Note:** For flights, ticket cancellation equals complete order cancellation.

```php
$url = 'https://test-api.bussystem.eu/server/curl/cancel_ticket.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "ticket_id" => 36015,
    "security" => 761499,
    "lang" => "en",
    "v" => "1.1"
];
```

**Response:**
```json
{
    "transaction_id": "4011855958",
    "ticket_id": "36015",
    "cancel_ticket": "1",
    "price": 0,              // Fee withheld (0%)
    "money_back": 500.62,    // Full refund
    "provision": 0,
    "currency": "EUR",
    "hours_after_buy": 1.12,
    "hours_before_depar": 219.234,
    "rate": 0               // 0% cancellation fee
}
```

### 9.3. Complete Order Cancellation

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "order_id" => 1026944,
    "security" => 687577,
    "lang" => "en",
    "v" => "1.1"
];
```

**Response:**
```json
{
    "order_id": 1026944,
    "cancel_order": "1",
    "price_total": 0,           // Total fees
    "money_back_total": 500.62, // Total refund
    "currency": "EUR",
    "0": {
        "transaction_id": "4011855958",
        "ticket_id": "36015",
        "cancel_ticket": "1",
        "price": 0,
        "money_back": 500.62,
        "provision": 0,
        "currency": "EUR",
        "hours_after_buy": 1.12,
        "hours_before_depar": 219.234,
        "rate": 0,
        "baggage": null
    }
}
```


## Key Flight-Specific Features

**Simplified Booking Process:**
- No seat maps or seat selection required
- No baggage selection - included in flight search
- No discount management - automatic based on passenger type
- Single ticket for entire multi-segment itinerary

**Passenger Requirements:**
- Full passport information required
- Citizenship and gender mandatory
- Document expiration dates required
- Birth dates for age verification

**IATA Codes:**
- Use 3-letter airport codes (WAW, MXP, ZRH)
- Specify both directions for round-trip search
- Airports determined from `get_points.php` with `"trans": "air"`

**Booking Characteristics:**
- Extended reservation time (up to 12+ hours)
- Single `interval_id` for round-trip flights
- Passenger types: `adt` (adult), `chd` (child), `inf` (infant)
- Automatic child discounts based on age

**Payment and Cancellation:**
- One ticket covers all passengers and flight segments
- Ticket cancellation equals order cancellation
- Cancellation fees vary by airline and timing
- Single refund transaction for entire booking

**Service Classes:**
- E = Economy
- B = Business  
- F = First Class

**Baggage Integration:**
- Search with `"baggage": "1"` for flights with included baggage
- Baggage allowance shown as "1PC", "2PC", etc.
- No additional baggage selection during booking