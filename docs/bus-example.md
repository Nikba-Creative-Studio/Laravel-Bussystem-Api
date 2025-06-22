---
layout: default
title: Bus Example
description: Complete bus booking workflow from search to cancellation
nav_order: 1
parent: Examples
---

# Bus Booking Example

This comprehensive example demonstrates a complete bus booking workflow from Prague to Kyiv and back, including route search, seat selection, booking, payment, and cancellation.

## Scenario Overview

**Route:** Prague ↔ Kyiv  
**From:** Prague, Florence Bus Station (ID: 123)  
**To:** Kyiv (all bus stations)  
**Departure Date:** 2023-11-30  
**Return Date:** 2023-12-14  
**Transport:** Bus  
**Transfers:** Including external routes  
**Currency:** EUR  
**Language:** English  

**Passengers:**
- **Anna Smith** (Adult, born 1992-01-01)
- **Emma Smith** (Child, born 2020-02-02)

---

## 1. Route Search

### 1.1. Search Outbound Routes (Prague → Kyiv)

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_routes.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "date" => "2023-11-30",
    "id_from" => "3",              // Prague
    "id_to" => "6",                // Kyiv
    "station_id_from" => "123",    // Florence Bus Station
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
    CURLOPT_POSTFIELDS => $post_data,
    CURLOPT_HTTPHEADER => ['Accept: application/json']
]);

$response = curl_exec($curl);
curl_close($curl);
```

**Key Response Data:**
```json
{
    "trans": "bus",
    "interval_id": "local|14916|Mjc...nw=|2023-11-30T10:34:50||d47e87b4",
    "route_name": "Prague - Kyiv",
    "has_plan": 0,
    "carrier": "Bus trans",
    "comfort": "wifi,220v,conditioner,music,tv",
    "rating": "4.6",
    "reviews": "93",
    "request_get_free_seats": 0,
    "request_get_discount": 0,
    "request_get_baggage": 0,
    "lock_min": "30",
    "max_seats": "10",
    "date_from": "2023-11-30",
    "time_from": "12:00:00",
    "point_from": "Prague",
    "station_from": "Bus Station \"Florenc\", pl.3",
    "date_to": "2023-12-01",
    "time_to": "10:30:00",
    "point_to": "Kyiv",
    "station_to": "Bus Station \"Kyiv\", 32 S. Petliury str.",
    "time_in_way": "21:30",
    "price_one_way": "90",
    "currency": "EUR",
    "free_seats": [1, 2, "3", "4", 5, 6],
    "discounts": [
        {
            "discount_id": "34172",
            "discount_name": "10% Retirees",
            "discount_price": 81
        },
        {
            "discount_id": "34835",
            "discount_name": "50% Children 0-5 years old",
            "discount_price": 45
        }
    ]
}
```

### 1.2. Search Return Routes (Kyiv → Prague)

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "date" => "2023-12-14",
    "id_from" => "6",              // Kyiv
    "id_to" => "3",                // Prague
    "interval_id" => [
        "local|14916|Mjc...nw=|2023-11-30T10:34:50||d47e87b4"
    ],
    "trans" => "bus",
    "change" => "auto",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
];
```

**Key Response Data:**
```json
{
    "trans": "bus",
    "interval_id": "local|14915|Mjc...J8|2023-11-30T10:40:07|return_route|c6934263",
    "route_name": "Kyiv - Lviv - Prague",
    "has_plan": 1,
    "carrier": "Mega bus",
    "request_get_free_seats": 1,
    "request_get_discount": 1,
    "request_get_baggage": 1,
    "lock_min": "180",
    "change_route": [
        {
            "date_from": "2023-12-14",
            "time_from": "06:30:00",
            "point_from": "Kyiv",
            "point_to": "Lviv",
            "free_seats": [99, 99, 99, 99, 99]
        },
        {
            "date_from": "2023-12-14",
            "time_from": "15:30:00",
            "point_from": "Lviv",
            "point_to": "Prague",
            "free_seats": [99, 99, 99, 99, 99],
            "change_typ": "manual",
            "transfer_time": {"d": 0, "h": 0, "m": 5}
        }
    ]
}
```

---

## 2. Available Seats

### 2.1. Outbound Seats
Since `request_get_free_seats` is 0, use seats from route search:
```json
"free_seats": [1, 2, "3", "4", 5, 6, 7, 8, 9, 10]
```

### 2.2. Return Seats
Since `request_get_free_seats` is 1, fetch detailed seat information:

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_free_seats.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "local|14915|Mjc...J8|2023-11-30T10:40:07|return_route|c6934263",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
];
```

**Response (Two buses for connection):**
```json
{
    "trips": [
        {
            "bustype_id": "105",
            "has_plan": 1,
            "free_seat": [
                {
                    "seat_number": "5",
                    "seat_free": 1,
                    "seat_price": 20,
                    "seat_curency": "EUR"
                },
                {
                    "seat_number": "6",
                    "seat_free": 1,
                    "seat_price": 20,
                    "seat_curency": "EUR"
                }
            ]
        },
        {
            "bustype_id": "217",
            "has_plan": 1,
            "free_seat": [
                {
                    "seat_number": "1",
                    "seat_free": 1
                },
                {
                    "seat_number": "2",
                    "seat_free": 1
                }
            ]
        }
    ]
}
```

---

## 3. Seat Plans

### 3.1. Outbound Seat Plan
Since `has_plan` is 0, no seat plan is required.

### 3.2. Return Seat Plans
Since `has_plan` is 1, fetch seat plans for both buses:

**Bus 1 (Kyiv → Lviv):**
```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "bustype_id" => "105",
    "position" => "h",
    "v" => "2.0"
];
```

**Bus 2 (Lviv → Prague):**
```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "bustype_id" => "217",
    "position" => "h",
    "v" => "2.0"
];
```

---

## 4. Discounts

### 4.1. Outbound Discounts
Available from route search response:
```json
"discounts": [
    {
        "discount_id": "34835",
        "discount_name": "50% Children 0-5 years old",
        "discount_price": 45
    }
]
```

### 4.2. Return Discounts
Since `request_get_discount` is 1, fetch discount details:

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_discount.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "local|14915|Mjc...J8|2023-11-30T10:40:07|return_route|c6934263",
    "currency" => "EUR",
    "lang" => "en"
];
```

**Response:**
```json
{
    "route_id": "14915",
    "discounts": [
        {
            "discount_id": "3196",
            "discount_name": "50% Children 0-4 years old",
            "discount_price": 45,
            "discount_currency": "EUR"
        }
    ]
}
```

---

## 5. Baggage Options

### 5.1. Outbound Baggage
Since `request_get_baggage` is 0, use text information:
```
"luggage": "1 bag up to 50 kg - free of charge. Every next 10 EUR"
```

### 5.2. Return Baggage
Since `request_get_baggage` is 1, fetch baggage options:

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_baggage.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "local|14915|Mjc...J8|2023-11-30T10:40:07|return_route|c6934263",
    "station_id_to" => "123",
    "currency" => "EUR",
    "lang" => "en"
];
```

**Response:**
```json
[
    {
        "baggage_id": "81",
        "baggage_title": "Hand baggage",
        "kg": "5",
        "max_per_person": "1",
        "price": 0,
        "currency": "EUR"
    },
    {
        "baggage_id": "82",
        "baggage_title": "Hand baggage",
        "kg": "5",
        "price": 5,
        "currency": "EUR"
    },
    {
        "baggage_id": "86",
        "baggage_title": "Large baggage",
        "kg": "25",
        "price": 15,
        "currency": "EUR"
    }
]
```

---

## 6. Booking

Create order with selected seats, discounts, and baggage:

```php
$url = 'https://test-api.bussystem.eu/server/curl/new_order.php';

$booking_data = [
    "login" => "your_login",
    "password" => "your_password",
    "promocode_name" => "PROMO77ENDLESS",
    "date" => [
        "2023-11-30",    // Outbound
        "2023-12-14"     // Return
    ],
    "interval_id" => [
        "local|14916|Mjc...nw=|2023-11-30T10:34:50||d47e87b4",
        "local|14915|Mjc...J8|2023-11-30T10:40:07|return_route|c6934263"
    ],
    "seat" => [
        ["3", "4"],           // Outbound: Anna=3, Emma=4
        ["5,1", "6,2"]        // Return: Anna=5,1, Emma=6,2 (bus1,bus2)
    ],
    "name" => ["Anna", "Emma"],
    "surname" => ["Smith", "Smith"],
    "birth_date" => ["1992-01-01", "2020-02-02"],
    "discount_id" => [
        {"1": "34835"},       // Outbound: Emma gets child discount
        {"1": "3196"}         // Return: Emma gets child discount
    ],
    "baggage" => {
        "1": ["82,86", "84"]  // Return baggage for both passengers
    },
    "phone" => "+375291234567",
    "email" => "info@test-mail.en",
    "currency" => "EUR",
    "lang" => "en"
];
```

**Response:**
```json
{
    "order_id": 1026944,
    "reservation_until": "2023-11-30 10:45:23",
    "reservation_until_min": "30",
    "security": "722842",
    "status": "reserve_ok",
    "price_total": 285,
    "currency": "EUR",
    "promocode_info": {
        "promocode_valid": 1,
        "promocode_name": "PROMO77ENDLESS",
        "price_promocode": 15
    }
}
```

---

## 7. Payment

Complete the booking by purchasing tickets:

```php
$url = 'https://test-api.bussystem.eu/server/curl/buy_ticket.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "order_id" => 1026944,
    "lang" => "en",
    "v" => "1.1"
];
```

**Response:**
```json
{
    "order_id": 1026944,
    "price_total": 300,
    "currency": "EUR",
    "link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=1026944&security=722842&lang=en",
    "0": {
        "ticket_id": "21011",
        "security": "761899",
        "price": 90,
        "link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?ticket_id=21011&security=761899&lang=en"
    },
    "1": {
        "ticket_id": "21012",
        "price": 45,
        "link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?ticket_id=21012&security=717836&lang=en"
    }
}
```

---

## 8. Ticket Generation

**Complete Order Tickets:**
```
https://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=1026944&security=722842&lang=en
```

**Individual Tickets:**
- Anna's outbound: `ticket_id=21011&security=761899`
- Emma's outbound: `ticket_id=21012&security=717836`
- Anna's return: `ticket_id=21013&security=464335`
- Emma's return: `ticket_id=21014&security=741539`

---

## 9. Cancellation

### 9.1. Cancel Individual Ticket

```php
$url = 'https://test-api.bussystem.eu/server/curl/cancel_ticket.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "ticket_id" => 21014,
    "security" => 741539,
    "lang" => "en",
    "v" => "1.1"
];
```

**Response:**
```json
{
    "transaction_id": "1038041",
    "ticket_id": "21014",
    "cancel_ticket": "1",
    "price": 22.50,
    "money_back": 30,
    "provision": 2.25,
    "currency": "EUR",
    "rate": 50
}
```

### 9.2. Cancel Entire Order

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "order_id" => 1026944,
    "security" => 722842,
    "lang" => "en",
    "v" => "1.1"
];
```

**Response:**
```json
{
    "order_id": 1026944,
    "cancel_order": "1",
    "price_total": 112.5,
    "money_back_total": 120,
    "currency": "EUR"
}
```

---

## Key Points

**Seat Selection:**
- Simple routes: Use `free_seats` from route search
- Routes with connections: Use comma-separated format (bus1,bus2)

**Discounts:**
- Check `request_get_discount` flag
- Apply discounts per passenger and route

**Baggage:**
- Free baggage is included automatically
- Additional baggage requires explicit selection

**Reservations:**
- Monitor `reservation_until` timestamp
- Complete payment within `lock_min` period

**Cancellations:**
- Check cancellation rates before processing
- 100% rate means non-refundable
- Use actual `money_back` amount from API response