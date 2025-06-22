---
layout: default
title: Train Example
description: Complete train booking workflow with multiple connections and coach selection
nav_order: 2
parent: Examples
---

# Train Booking Example

This comprehensive example demonstrates a complex train booking workflow from Munich to Kyiv with multiple connections, including route search, coach/wagon selection, seat assignment, booking, payment, and cancellation.

## Scenario Overview

**Route:** Munich → Vienna → Ostrava → Przemyśl → Kyiv  
**From:** MUNICH (Munich, Germany)  
**To:** KYIV-PASSENGER (Kyiv, Ukraine)  
**Date:** 2024-10-10  
**Transport:** Train  
**Transfers:** Including external routes  
**Currency:** EUR  
**Language:** English  

**Journey Details:**
- 07:23 Munich → Vienna (4h 09m)
- Transfer: 2h 38m
- 14:10 Vienna → Ostrava (2h 53m) 
- Transfer: 8h 03m (Change of station)
- 01:06 Ostrava → Przemyśl (6h 17m)
- Transfer: 2h 12m
- 09:35 Przemyśl → Kyiv (10h 22m)

**Passengers:**
- **Anna Ivanova** (Adult, born 1992-01-01)
- **Masha Ivanova** (Child, born 2020-02-02)

---

## 1. Route Search

### Search Multi-Connection Train Route

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_routes.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "date" => "2024-10-10",
    "point_train_from_id" => "8000600",  // Munich
    "point_train_to_id" => "2200001",    // Kyiv
    "trans" => "train",
    "change" => "auto",
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

**Key Response Data (3 Route Segments):**

**Route 1: Munich → Vienna**
```json
{
    "trans": "train",
    "interval_id": "17684|MTg3fDE3OH...0xBU1NFXzI=|1720690320|2024-10-01T08:28:19||38bad0a8",
    "route_name": "RJX 61 München Hbf Gl.5-10 - Wien Hbf",
    "has_plan": 2,
    "carrier": "DB Fernverkehr AG: RJX 61 (railjet xpress)",
    "request_get_free_seats": 1,
    "request_get_discount": 1,
    "train_id": ["train|ODQ3MmUxMDB8NjF8MA=="],
    "date_from": "2024-10-10",
    "time_from": "07:23:00",
    "date_to": "2024-10-10", 
    "time_to": "11:32:00",
    "price_S": 38.88,
    "max_seats": 10
}
```

**Route 2: Vienna → Ostrava**
```json
{
    "trans": "train",
    "interval_id": "12850|RXVyb2NpdHkgKFd...wMHwzfDQxNDEw||1728572580|2024-10-01T08:28:19||b856ddbb",
    "route_name": "Eurocity (Wien Hbf - Ostrava hl.n.)",
    "has_plan": 2,
    "carrier": "Czech Railways",
    "request_get_free_seats": 1,
    "request_get_discount": 1,
    "train_id": ["43200126|78734749|3"],
    "date_from": "2024-10-10",
    "time_from": "14:10:00",
    "date_to": "2024-10-10",
    "time_to": "17:03:00",
    "price_S": 12.24
}
```

**Route 3: Ostrava → Przemyśl → Kyiv**
```json
{
    "trans": "train",
    "interval_id": "9260|MjAyNC0xMC0x...zMzE2NzAwNA==|1728669420|2024-10-01T08:28:19||3268570e",
    "route_name": "(RJ 1023) Prague - Přemyšl, (IC 706) Přemyšl - Kyiv",
    "has_plan": 2,
    "carrier": "RegioJet",
    "request_get_free_seats": 1,
    "request_get_discount": 1,
    "change_route": [
        {
            "point_from": "Ostrava",
            "point_to": "Przemyśl",
            "station_from": "Ostrava, Svinov",
            "station_to": "Přemyšl, Hlavní nádraží",
            "date_from": "2024-10-11",
            "time_from": "01:06:00",
            "time_to": "07:23:00"
        },
        {
            "point_from": "Przemyśl", 
            "point_to": "Kyiv",
            "station_from": "Přemyšl, Hlavní nádraží",
            "station_to": "Kyiv, Nádraží",
            "date_from": "2024-10-11",
            "time_from": "09:35:00",
            "time_to": "19:57:00",
            "change_typ": "manual"
        }
    ],
    "train_id": [
        "train|MHw3MzU5Mzc5NTI2fFRSQUlO",
        "train|MXw3MzU5Mzc5NzQ5fFRSQUlO"
    ],
    "price_S": 64.54,
    "max_seats": 6
}
```

---

## 2. Coach and Seat Selection

### 2.1. Route 1: Munich → Vienna

#### 2.1.1. Coach Selection

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_free_seats.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "17684|MTg3fDE3OH...0xBU1NFXzI=|1720690320|2024-10-01T08:28:19||38bad0a8",
    "train_id" => "train|ODQ3MmUxMDB8NjF8MA==",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
];
```

**Available Coaches:**
```json
{
    "vagon": {
        "item": [
            {
                "vagon_id": "NjF8U3VwZXIgU3BhcnByZWlzIEV1cm9wYXwwfC18MHwwfDB8MzQuOTl8NS4",
                "vagon_class": "2, Super Sparpreis Europa",
                "price": 38.88,
                "free_seats": 10,
                "has_plan": 0
            },
            {
                "vagon_id": "NjF8U3BhcnByZWlzIEV1cm9wYXwwfC18MHwwfDB8MzkuOTl8NS4y",
                "vagon_class": "2, Sparpreis Europa", 
                "price": 44.43,
                "free_seats": 10,
                "has_plan": 0
            },
            {
                "vagon_id": "NjF8RmxleHByZWlzIEV1cm9wYXwwfC18MHwwfDB8MTA5Ljc1fDUuMg==",
                "vagon_class": "2, Flexpreis Europa",
                "price": 121.94,
                "free_seats": 10,
                "has_plan": 0
            }
        ]
    }
}
```

#### 2.1.2. Seat Selection

**Selected Coach:** Sparpreis Europa (€44.43)

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "17684|MTg3fDE3OH...0xBU1NFXzI=|1720690320|2024-10-01T08:28:19||38bad0a8",
    "vagon_id" => "NjF8U3BhcnByZWlzIEV1cm9wYXwwfC18MHwwfDB8MzkuOTl8NS4y",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
];
```

**Available Seats:**
```json
{
    "free_seat": {
        "item": [
            {
                "seat_number": 99,
                "seat_free": 1,
                "seat_type": "S"
            }
        ]
    }
}
```

### 2.2. Route 2: Vienna → Ostrava

#### 2.2.1. Coach Selection

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "12850|RXVyb2NpdHkgKFd...wMHwzfDQxNDEw||1728572580|2024-10-01T08:28:19||b856ddbb",
    "train_id" => "43200126|78734749|3",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "1.1"
];
```

**Available Coaches:**
```json
{
    "vagon": {
        "item": [
            {
                "vagon_id": "Nzg3MzQ3NDl8NDMyMDAxMjZ8MXwxfDQ4NjAwfDIzLjIyfDIuMzJ8RVVSfDB8MHwxMHwxfFcyNTFiR3hk",
                "vagon_number": 1,
                "vagon_class": "1",
                "price": 23.22,
                "free_seats": 10,
                "has_plan": 0
            },
            {
                "vagon_id": "Nzg3MzQ3NDl8NDMyMDAxMjZ8MnwyfDI1NjAwfDEyLjIzfDEuMjJ8RVVSfDB8MHwxMHwxfFcyNTFiR3hk",
                "vagon_number": 2,
                "vagon_class": "2",
                "price": 12.23,
                "free_seats": 10,
                "has_plan": 0
            }
        ]
    }
}
```

#### 2.2.2. Seat Selection

**Selected Coach:** Class 1 (€23.22)

```php
$post_data = [
    "vagon_id" => "Nzg3MzQ3NDl8NDMyMDAxMjZ8MXwxfDQ4NjAwfDIzLjIyfDIuMzJ8RVVSfDB8MHwxMHwxfFcyNTFiR3hk"
];
```

### 2.3. Route 3: Ostrava → Przemyśl → Kyiv

#### 2.3.1. Segment 1: Ostrava → Przemyśl Coach Selection

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "9260|MjAyNC0xMC0x...zMzE2NzAwNA==|1728669420|2024-10-01T08:28:19||3268570e",
    "train_id" => "train|MHw3MzU5Mzc5NTI2fFRSQUlO",
    "currency" => "EUR",
    "lang" => "en",
    "v" => "2.0"
];
```

**Available Coaches:**
```json
{
    "vagon": {
        "item": [
            {
                "vagon_id": "MHxDMHw5fDE=",
                "vagon_number": 9,
                "vagon_class": "Standard (2nd cl.)",
                "price": 64.54,
                "free_seats": 46,
                "has_plan": 1
            },
            {
                "vagon_id": "MHxUUkFJTl9DT1VDSEVUVEVfUkVMQVh8Nnwx",
                "vagon_number": 6,
                "vagon_group_type": "compartment",
                "vagon_class": "Sleeping Place",
                "price": 97.03,
                "free_seats": 31,
                "has_plan": 1
            },
            {
                "vagon_id": "MHxUUkFJTl9DT1VDSEVUVEVfQlVTSU5FU1NfNHw1fDE=",
                "vagon_number": 5,
                "vagon_class": "Sleeping Place – Private Compartment",
                "price": 87.28,
                "free_seats": 24,
                "has_plan": 1
            }
        ]
    }
}
```

#### 2.3.2. Segment 1: Seat Selection

**Selected Coach:** Sleeping Place (€97.03)

```php
$post_data = [
    "vagon_id" => "MHxUUkFJTl9DT1VDSEVUVEVfUkVMQVh8Nnwx"
];
```

**Available Seats:**
```json
{
    "free_seat": {
        "item": [
            {
                "seat_number": 21,
                "seat_free": 1,
                "seat_type": "K"
            },
            {
                "seat_number": 22,
                "seat_free": 1,
                "seat_type": "K"
            }
        ]
    },
    "price_type": "full"  // Total price for entire route
}
```

#### 2.3.3. Segment 2: Przemyśl → Kyiv Coach Selection

**Important:** Pass `vagon_id` from previous segment to get compatible coaches.

```php
$post_data = [
    "train_id" => "train|MXw3MzU5Mzc5NzQ5fFRSQUlO",
    "vagon_id" => "MHxUUkFJTl9DT1VDSEVUVEVfUkVMQVh8Nnwx"  // From previous segment
];
```

**Compatible Coach:**
```json
{
    "vagon": {
        "item": [
            {
                "vagon_id": "MXxUUkFJTl9DT1VDSEVUVEVfUkVMQVh8MHww",
                "vagon_number": 0,
                "vagon_class": "Sleeping Place",
                "price": 96.86,
                "free_seats": 203,
                "has_plan": 0
            }
        ]
    }
}
```

---

## 3. Seat Plans

### 3.1. Route 1 & 2: No Seat Plans
Since `has_plan` is 0 for selected coaches, no seat plans are required.

### 3.2. Route 3 Segment 1: Sleeping Car Plan

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_plan.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "vagon_type" => "SA2-vlak_vuz-bc_jadran81_83_vozik_luzka",
    "position" => "h",
    "v" => "1.1"
];
```

**Seat Plan Response:**
```json
{
    "plan_type": "749",
    "rows": {
        "row": [
            {
                "seat": [
                    {
                        "@attributes": {
                            "icon": "https://ws.bussystem.eu/images/icon_bus/dveri.png"
                        }
                    },
                    "3", "2", "13", "12", "23", "22", "33"
                ]
            },
            {
                "seat": [
                    {},
                    "1", "4", "11", "14", "21", "22"
                ]
            }
        ]
    }
}
```

---

## 4. Discount Lists

### 4.1. Route 1: Munich → Vienna Discounts

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_discount.php';

$post_data = [
    "interval_id" => "17684|MTg3fDE3OH...0xBU1NFXzI=|1720690320|2024-10-01T08:28:19||38bad0a8"
];
```

**Available Discounts:**
```json
{
    "discounts": {
        "item": [
            {
                "discount_id": "BC25",
                "discount_name": "BahnCard 25"
            },
            {
                "discount_id": "BC50",
                "discount_name": "BahnCard 50"
            }
        ]
    }
}
```

### 4.2. Route 2: Vienna → Ostrava Discounts

**Important:** Include `vagon_id` to avoid "interval_no_found" error.

```php
$post_data = [
    "interval_id" => "12850|RXVyb2NpdHkgKFd...wMHwzfDQxNDEw||1728572580|2024-10-01T08:28:19||b856ddbb",
    "vagon_id" => "Nzg3MzQ3NDl8NDMyMDAxMjZ8MXwxfDQ4NjAwfDIzLjIyfDIuMzJ8RVVSfDB8MHwxMHwxfFcyNTFiR3hk"
];
```

**Available Discounts:**
```json
{
    "discounts": [
        {
            "discount_id": 6,
            "discount_name": "Adult 65 years and older",
            "price": 18.58,
            "currency": "EUR"
        }
    ]
}
```

### 4.3. Route 3: Ostrava → Kyiv Discounts

```php
$post_data = [
    "interval_id" => "9260|MjAyNC0xMC0x...zMzE2NzAwNA==|1728669420|2024-10-01T08:28:19||3268570e"
];
```

**Available Discounts:**
```json
{
    "discounts": {
        "item": [
            {
                "discount_id": "CHILD_UNDER_12",
                "discount_name": "Child 6-12 years"
            },
            {
                "discount_id": "ATTENDED_CHILD",
                "discount_name": "Child accompanied by an adult 0-6 years"
            }
        ]
    }
}
```

---

## 5. Booking

**Note:** Baggage selection is not available for trains.

### Create Multi-Route Train Order

```php
$url = 'https://test-api.bussystem.eu/server/curl/new_order.php';

$booking_data = [
    "login" => "your_login",
    "password" => "your_password",
    "date" => [
        "2024-10-10",    // Route 1
        "2024-10-10",    // Route 2  
        "2024-10-11"     // Route 3
    ],
    "interval_id" => [
        "17684|MTg3fDE3OH...0xBU1NFXzI=|1720690320|2024-10-01T08:28:19||38bad0a8",
        "12850|RXVyb2NpdHkgKFd...wMHwzfDQxNDEw||1728572580|2024-10-01T08:28:19||b856ddbb",
        "9260|MjAyNC0xMC0x...zMzE2NzAwNA==|1728669420|2024-10-01T08:28:19||3268570e"
    ],
    "vagon_id" => [
        "NjF8U3BhcnByZWlzIEV1cm9wYXwwfC18MHwwfDB8MzkuOTl8NS4y",
        "Nzg3MzQ3NDl8NDMyMDAxMjZ8MXwxfDQ4NjAwfDIzLjIyfDIuMzJ8RVVSfDB8MHwxMHwxfFcyNTFiR3hk",
        "MHxUUkFJTl9DT1VDSEVUVEVfUkVMQVh8Nnwx,MXxUUkFJTl9DT1VDSEVUVEVfUkVMQVh8MHww"  // Comma-separated for connections
    ],
    "seat" => [
        ["99", "99"],           // Route 1: Auto-assigned
        ["99", "99"],           // Route 2: Auto-assigned  
        ["6/21,N", "6/22,N"]    // Route 3: Coach 6, seats 21,22 + connection seats
    ],
    "name" => ["Anna", "Masha"],
    "surname" => ["Ivanova", "Ivanova"],
    "birth_date" => ["1992-01-01", "2020-02-02"],
    "discount_id" => [
        {
            "2": {  // Route 3 (0-indexed)
                "1": "ATTENDED_CHILD"  // Child discount for passenger 2 (0-indexed)
            }
        }
    ],
    "phone" => "+375291234567",
    "email" => "info@test-mail.ru",
    "currency" => "EUR",
    "lang" => "en"
];
```

**Response:**
```json
{
    "order_id": 1044444,
    "reservation_until": "2024-10-01 08:35:23",
    "reservation_until_min": "15",
    "security": "564132",
    "status": "reserve_ok",
    "price_total": 259.77,  // (42.24 * 2) + (23.22 * 2) + (96.86 + 31.99)
    "currency": "EUR",
    "0": {
        "route_name": "RJX 61 München Hbf Gl.5-10 - Wien Hbf",
        "carrier": "DB Fernverkehr AG",
        "passengers": [
            {
                "seat": "*",  // Auto-assigned
                "price": 42.24
            },
            {
                "seat": "*",  // Auto-assigned
                "price": 42.24
            }
        ]
    },
    "1": {
        "route_name": "Eurocity (Wien Hbf - Ostrava hl.n.)",
        "carrier": "Czech Railways",
        "passengers": [
            {
                "seat": "352/55",  // Carrier-assigned coach/seat
                "price": 23.22
            },
            {
                "seat": "352/56",
                "price": 23.22
            }
        ]
    },
    "2": {
        "route_name": "(RJ 1023) Prague - Přemyšl, (IC 706) Přemyšl - Kyiv",
        "carrier": "RegioJet",
        "passengers": [
            {
                "seat": "6/21,N",  // Coach 6, seat 21, connection seat N
                "price": 96.86
            },
            {
                "seat": "6/22,N",
                "discount": "Child accompanied by an adult 0-6 years",
                "price": 31.99
            }
        ]
    }
}
```

---

## 6. Payment

```php
$url = 'https://test-api.bussystem.eu/server/curl/buy_ticket.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "order_id" => 1044444,
    "lang" => "en",
    "v" => "1.1"
];
```

**Response:**
```json
{
    "order_id": 1044444,
    "price_total": 259.77,
    "currency": "EUR",
    "link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=1044444&security=564132&lang=en",
    "0": {
        "ticket_id": "36000",
        "price": 42.24,
        "link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?ticket_id=36000&security=804398&lang=en"
    },
    "1": {
        "ticket_id": "36001", 
        "price": 42.24
    },
    "2": {
        "ticket_id": "36002",
        "price": 23.22
    },
    "3": {
        "ticket_id": "36003",
        "price": 23.22
    },
    "4": {
        "ticket_id": "36004",
        "price": 96.86
    },
    "5": {
        "ticket_id": "36005",
        "price": 31.99
    }
}
```

---

## 7. Cancellation

### 7.1. Individual Ticket Cancellation

```php
$url = 'https://test-api.bussystem.eu/server/curl/cancel_ticket.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "ticket_id" => 36005,
    "security" => 299329,
    "lang" => "en",
    "v" => "1.1"
];
```

**Response:**
```json
{
    "transaction_id": "4011855952",
    "ticket_id": "36005",
    "cancel_ticket": "1",
    "price": 3.20,        // Fee withheld
    "money_back": 28.79,  // Amount refunded
    "currency": "EUR",
    "rate": 10            // 10% cancellation fee
}
```

### 7.2. Complete Order Cancellation

```php
$post_data = [
    "order_id" => 1044444,
    "security" => 564132
];
```

**Response:**
```json
{
    "order_id": 1044444,
    "cancel_order": "1",
    "price_total": 12.89,      // Total fees
    "money_back_total": 246.88, // Total refund
    "currency": "EUR",
    "0": {
        "ticket_id": "36000",
        "money_back": 42.24,
        "rate": 0  // No cancellation fee
    },
    "1": {
        "ticket_id": "36001", 
        "money_back": 42.24,
        "rate": 0
    },
    "2": {
        "ticket_id": "36002",
        "money_back": 23.22,
        "rate": 0
    },
    "3": {
        "ticket_id": "36003",
        "money_back": 23.22,
        "rate": 0
    },
    "4": {
        "ticket_id": "36004",
        "price": 9.69,        // Fee
        "money_back": 87.17,  // Refund
        "rate": 10
    },
    "5": {
        "ticket_id": "36005",
        "price": 3.20,        // Fee
        "money_back": 28.79,  // Refund
        "rate": 10
    }
}
```

---

## Key Train-Specific Features

**Coach/Wagon Selection:**
- Must select coach type and class before seat selection
- Use `vagon_id` for filtering compatible coaches in connections
- Some coaches require purchasing all seats in compartment

**Seat Assignment:**
- Format: `{COACH_NUMBER}/{SEAT_NUMBER}` when coach numbers available
- Connections use comma separation: `6/21,N` (coach/seat, connection seat)
- Auto-assignment (*) when no specific seats required

**Pricing Logic:**
- `price_type: "full"` - Total route price shown in each segment
- `price_type: "partial"` - Segment prices need to be summed

**Multi-Connection Handling:**
- Each connection requires separate coach and seat selection
- Pass previous `vagon_id` to get compatible options
- Connection transfers may require station changes

**Discounts:**
- Some routes require `vagon_id` to retrieve discount list
- Child discounts commonly available for accompanied minors
- Senior and student discounts vary by carrier