---
layout: default
title: API Functions
description: Complete overview of BusSystem API operations and endpoints
nav_order: 3
parent: Getting Started
---

# API Functions

The BusSystem API provides comprehensive functionality for passenger transportation management. This section covers all available operations and their typical usage patterns.

## Possible Operations

### Blocking and Cancellation
Free blocking and cancellation workflow (available for some flights only):
```
get_routes → new_order → cancel_ticket
```

### Reservation and Cancellation  
Free reservation and cancellation workflow:
```
get_routes → new_order → cancel_ticket
```

### Sell and Return
Complete sales and refund workflow (refunds may have fees based on flight conditions):
```
get_routes → new_order → buy_ticket → cancel_ticket
```

**Note:** All other queries serve auxiliary purposes to support these main workflows.


## Flight Search

### get_points
**Purpose:** Search available cities and countries with dynamic linking  
**URL:** `https://test-api.bussystem.eu/server/curl/get_points.php`  
**Type:** Optional function

### get_routes
**Purpose:** Information about ALL available routes between selected cities on specified date  
**URL:** `https://test-api.bussystem.eu/server/curl/get_routes.php`  
**Type:** Core function

**Important Warning:**
- Do not use `get_routes` in query loops or for building transfer systems with high request volumes
- Request volume should match paid orders from real users (approximately 100:1 ratio)
- Violation of this rule may result in access suspension

### point_connect_success
**Purpose:** Successful connections between cities with days of the week (all flights)  
**Type:** Optional function

**Available Formats:**
- **JSON:** `https://test-api.bussystem.eu/files/point_white_list/point_connect_success.json`
- **CSV:** `https://test-api.bussystem.eu/files/point_white_list/point_connect_success.txt`


## Flight Information

### get_all_routes
**Purpose:** Route schedule information (available for some flights only)  
**URL:** `https://test-api.bussystem.eu/server/curl/get_all_routes.php`  
**Type:** Optional function

### get_discount
**Purpose:** List of available discounts for flights (available for some flights only)  
**URL:** `https://test-api.bussystem.eu/server/curl/get_discount.php`  
**Type:** Optional function


## Search for Places

### get_free_seats
**Purpose:** Search for seat numbers (bus and rail) + search for carriages (rail only)  
**URL:** `https://test-api.bussystem.eu/server/curl/get_free_seats.php`  
**Type:** Required for Railways

### get_plan
**Purpose:** Seat layout information (all flights)  
**URL:** `https://test-api.bussystem.eu/server/curl/get_plan.php`  
**Type:** Optional function


## Reservation

### new_order
**Purpose:** Creates a new order with time-limited reservation  
**URL:** `https://test-api.bussystem.eu/server/curl/new_order.php`  
**Type:** Core function

**Important Details:**
- Order is valid for period defined by `lock_min` (typically 20 minutes)
- After `lock_min`, order is automatically cancelled
- For some flights, seats are blocked for the specified time
- Some flights allow payment after `lock_min`, but success and seat assignment are not guaranteed


## Order Information

### get_order
**Purpose:** Get complete information about entire order  
**URL:** `https://test-api.bussystem.eu/server/curl/get_order.php`  
**Type:** Optional function

### get_ticket
**Purpose:** Get complete information about specific ticket  
**URL:** `https://test-api.bussystem.eu/server/curl/get_ticket.php`  
**Type:** Optional function


## Order Payment

### buy_ticket
**Purpose:** Complete ticket purchase based on generated order (new_order)  
**URL:** `https://test-api.bussystem.eu/server/curl/buy_ticket.php`  
**Type:** Core function


## Cancel Order

### cancel_ticket
**Purpose:** Cancel and refund ticket  
**URL:** `https://test-api.bussystem.eu/server/curl/cancel_ticket.php`  
**Type:** Core function


## Retrieve Ticket Form

### print_ticket
**Purpose:** Generate e-ticket form for passenger  
**URL:** `https://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=XXXX&security=XXXX&lang=XX`  
**Type:** Core function

**Important Notes:**
- Ticket design varies depending on selected flight
- Passengers must present this e-ticket on the bus
- Bank receipts or terminal confirmations are not acceptable substitutes
- Failure to present proper e-ticket may result in journey cancellation


## Order Lists

### For Dealers

#### get_orders
**Purpose:** List of orders (if payment functionality is available)  
**URL:** `https://test-api.bussystem.eu/server/curl/get_orders.php`

#### get_tickets  
**Purpose:** List of tickets (if payment functionality is available)  
**URL:** `https://test-api.bussystem.eu/server/curl/get_tickets.php`

#### get_cash
**Purpose:** Cash register functionality (if payment functionality is available)  
**URL:** `https://test-api.bussystem.eu/server/curl/get_cash.php`

### For Affiliates

#### get_orders
**Purpose:** List of orders for affiliate sites  
**URL:** `https://test-api.bussystem.eu/server/curl_partner/get_orders.php`

### For Dispatchers

#### get_tickets
**Purpose:** Ticket list for carriers and dispatchers  
**URL:** `https://test-api.bussystem.eu/server/curl_dispatcher/get_tickets.php`


## Session Management

**Recommendation:** Include `session=xxxx` parameter in all requests where user session tracking is important. This enables access to flights that require session identification.


## Server Status

### ping
**Purpose:** Check server response status  
**URL:** `https://test-api.bussystem.eu/server/curl/ping.php`  
**Type:** Utility function


## Production Environment

**Important:** To work with real flights, you must:
- Update request URLs to production endpoints
- Configure authorized IP addresses for non-test server access
- Obtain production credentials (login and password)