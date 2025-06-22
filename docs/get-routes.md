---
layout: default
title: get_routes
description: Search for available routes and schedules across all transport types
nav_order: 2
parent: Route Search
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
| `interval_id` | **Critical:** Interval ID for booking (required for `new_order`) |
| `route_id` | Route identifier |
| `route_name` | Full route name |
| `buy` | `1`=available for purchase, `0`=unavailable |
| `reserve` | `1`=available for reservation, `0`=unavailable |
| `eticket` | `1`=e-ticket, `0`=exchange form, `2`=unknown |
| `only_original` | `1`=requires original PDF ticket for boarding |

### Booking Requirements

| Field | Description |
|-------|-------------|
| `need_orderdata` | `1`=passenger data required in `new_order` |
| `need_birth` | `1`=birth date required |
| `need_doc` | `1`=document type/number required |
| `need_doc_expire_date` | `1`=document expiry date required |
| `need_citizenship` | `1`=citizenship required |
| `need_gender` | `1`=gender required |
| `need_middlename` | `1`=middle name required |
| `fast_booking` | `1`=no passenger data required |

### Timing and Availability

| Field | Description |
|-------|-------------|
| `lock_order` | `1`=`new_order` blocks seats for `lock_min` minutes |
| `lock_min` | Minutes of seat blocking |
| `reserve_min` | Minutes of long-term reservation |
| `max_seats` | Maximum passengers per booking |
| `start_sale_day` | Days available for presale |
| `stop_sale_hours` | Hours before departure when sales stop |
| `cancel_free_min` | Minutes for free cancellation |

### Schedule Information

| Field | Description |
|-------|-------------|
| `date_from` | Departure date (YYYY-mm-dd) |
| `time_from` | Departure time (HH:MM:SS) in departure city timezone |
| `mktime_utc_from` | UNIX timestamp of departure in UTC |
| `date_to` | Arrival date (YYYY-mm-dd) |
| `time_to` | Arrival time (HH:MM:SS) in arrival city timezone |
| `mktime_utc_to` | UNIX timestamp of arrival in UTC |
| `time_in_way` | Travel duration (HH:MM) |

### Location Details

| Field | Description |
|-------|-------------|
| `point_from_id`, `point_from` | Departure city ID and name |
| `station_from_id`, `station_from` | Departure station ID and name |
| `station_from_lat`, `station_from_lon` | Departure station coordinates |
| `point_to_id`, `point_to` | Arrival city ID and name |
| `station_to_id`, `station_to` | Arrival station ID and name |
| `station_to_lat`, `station_to_lon` | Arrival station coordinates |

### Pricing Information

| Field | Description |
|-------|-------------|
| `price_one_way` | One-way price (informational only) |
| `price_one_way_max` | Maximum price when seat prices vary |
| `price_two_way` | Recommended round-trip price |
| `price_tax` | Service fee |
| `provision` | Agency fee |
| `currency` | Price currency |

### Seat and Plan Information

| Field | Description |
|-------|-------------|
| `has_plan` | `0`=no plan, `1`=plan available, `2`=check in `get_free_seats` |
| `free_seats` | Array of available seat numbers |
| `request_get_free_seats` | `1`=can use `get_free_seats` API |
| `request_get_discount` | `1`=can use `get_discount` API |
| `request_get_baggage` | `1`=can use `get_baggage` API |

### Transfer Information

| Field | Description |
|-------|-------------|
| `change_stations` | `0`=same station, `1`=different stations |
| `change_typ` | `manual`=carrier responsibility, `auto`=passenger responsibility |
| `transfer_time` | Transfer duration (`d`=days, `h`=hours, `m`=minutes) |

### Carrier and Service Information

| Field | Description |
|-------|-------------|
| `carrier` | Carrier name |
| `carrier_id` | Carrier ID |
| `logo_url` | URL to carrier logo |
| `comfort` | Available services (wifi, tv, wc, etc.) |
| `luggage` | Baggage conditions |
| `route_info` | Additional route information |

### Cancellation Information

| Field | Description |
|-------|-------------|
| `cancel_only_order` | `0`=individual tickets, `1`=whole order only |
| `cancel_hours_info` | Array of cancellation fee information by timing |

### Bus-Specific Fields

| Field | Description |
|-------|-------------|
| `bustype_id` | Bus type ID |
| `bustype` | Bus name and seat count |
| `free_seats_info` | Detailed seat availability information |

### Train-Specific Fields

| Field | Description |
|-------|-------------|
| `train_id` | Train ID for car selection |
| `Speed` | Train speed (`0`=unknown) |
| `Class` | Train class (`0`=unknown) |
| `type` | Train type |
| `brand` | Train brand |
| `firm_name` | Train company |
| `bedclothes` | Bedding reservation available |
| `BaggageOver` | Extra baggage tickets available |
| `BaggageAnimal` | Animal tickets available |
| `L`, `K`, `M`, `P`, `S`, `O` | Seat counts by class |
| `price_L`, `price_K`, `price_M`, `price_P`, `price_S`, `price_O` | Minimum prices by class |

### Air-Specific Fields

| Field | Description |
|-------|-------------|
| `supplier` | Airline name |
| `supplier_code` | Airline code |
| `flight_number` | Flight number |
| `aircraft_code` | Aircraft code |
| `aircraft` | Aircraft name |
| `service_class_type` | Service class type |
| `service_class` | Service class code |
| `baggage` | Baggage allowance (e.g., "1PC") |

---

## Error Responses

### Dealer Not Active
```xml
<root>
    <error>dealer_no_activ</error>
    <detal>Dealer not active</detal>
</root>
```

### Route Not Active
```xml
<root>
    <error>route_no_activ</error>
</root>
```

### Currency Not Active
```xml
<root>
    <error>currency_no_activ</error>
</root>
```

### Routes Not Found
```xml
<root>
    <error>interval_no_found</error>
</root>
```

### Date Errors
```xml
<root>
    <error>date</error>
</root>
```

---

## Advanced Usage

### Multi-Route Booking
For round-trip or multi-city bookings, use the `interval_id` parameter from previous searches to calculate combined pricing with potential discounts.

### Transfer Search Parameters

| Value | Description |
|-------|-------------|
| `"auto"` | Search routes with external transfers |
| `"0"` | Direct routes only |
| `"1"` | Routes with domestic transfers |
| `"2-25"` | External transfers with up to 3 connections |

**Note:** Values above 15 may cause significant delays. Always verify each flight segment separately with `route_id` parameter.

### Period Search
Use negative period values to search both directions from the specified date (e.g., `period: -3` searches 3 days before and 3 days after, total 6 days).

### OPEN Tickets
- `search_type: 2` - Search for OPEN tickets available for purchase
- `search_type: 3` - Search for flights to register existing OPEN tickets (requires `find_order_id` or `find_ticket_id` and `find_security`)

---

## Best Practices

1. **Always use prices from `new_order`** - Route search prices are informational only
2. **Respect rate limits** - Maintain ~100:1 ratio of requests to actual bookings
3. **Handle transfers properly** - Each segment requires separate API calls for details
4. **Verify availability** - Check `buy`, `reserve`, and seat availability before booking
5. **Include session parameter** - Recommended for routes requiring session tracking
6. **Use appropriate timeouts** - Third-party system searches may take longer
7. **Check response fields** - Use `request_get_*` flags to determine which additional APIs are available