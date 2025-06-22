---
layout: default
title: new_order
description: Create a new order for bus, train, or flight tickets with automatic reservation expiration
nav_order: 1
parent: Booking
---

# new_order

Create a new order for a specified period. Orders are automatically canceled after the lock time (typically 20 minutes) unless payment is completed.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/new_order.php`  
**Method:** POST  
**Type:** Mandatory function


## Description

This function creates a new booking order with temporary seat reservations. The order remains active for a limited time period (lock_min), after which it's automatically canceled if not paid.

### Key Features
- Supports up to 10 routes and 10 passengers per order
- Only available for routes with `buy = 1` parameter
- Mandatory function for external flights (must be used after `get_routes`)
- Automatic seat blocking for specified time periods
- Payment may be possible after lock_min but doesn't guarantee seat availability


## Parameters

### Authentication Parameters
- **login** (string, required): Your login credentials
- **password** (string, required): Your password
- **session** (string, optional): Session identifier for additional security
- **partner** (string, optional): Partner site ID for affiliate sites

### Basic Parameters
- **v** (string, required): API version (use "1.1")
- **currency** (string, required): Response currency [EUR, RON, PLN, MDL, RUB, UAH, CZK]
- **lang** (string, required): Response language [en, ru, ua, de, pl, cz]

### Route Parameters
- **date** (array, required): Departure dates in YYYY-MM-DD format
  - Use "open" for open ticket reservations
- **interval_id** (array, required): Route interval identifiers
- **station_from_id** (array, required): Departure station IDs
- **station_to_id** (array, required): Arrival station IDs

### Seat Selection
- **seat** (array, required): Selected seats for each passenger on each route
  - Use "99" for automatic seat selection
  - For flights with transfers, separate seats with commas
  - For air transport, specify passenger type: "adt" (adult), "chd" (child), "inf" (infant)

### Passenger Information
Required when `need_orderdata = 1`:
- **name** (array): Passenger first names (Latin characters only)
- **surname** (array): Passenger surnames (Latin characters only)
- **phone** (string): Primary contact phone number
- **email** (string): Contact email address

### Optional Passenger Data
- **middlename** (array): Middle names (required when `need_middlename = 1`)
- **birth_date** (array): Birth dates in YYYY-MM-DD format (required when `need_birth = 1`)
- **gender** (array): Gender - "M" (male) or "F" (female) (required when `need_gender = 1`)
- **citizenship** (array): Country codes (required when `need_citizenship = 1`)

### Document Information
Required when `need_doc = 1`:
- **doc_type** (array): Document type
  - 1 = Foreign passport
  - 2 = Internal passport  
  - 3 = Birth certificate
- **doc_number** (array): Document numbers
- **doc_expire_date** (array): Document expiration dates (required when `need_doc_expire_date = 1`)

### Additional Options
- **discount_id** (array): Discount IDs for each passenger
- **baggage** (array): Baggage IDs (comma-separated for multiple items)
- **phone2** (string): Secondary contact phone
- **info** (string): Additional passenger information
- **vagon_id** (array): Train wagon IDs (trains only)
- **bedclothes** (array): Bedding service request (trains only)


## Array Structure

### 1-Level Arrays
Keys represent passenger serial numbers starting from 0:
- name, surname, middlename, birth_date, doc_type, doc_number, etc.

### 2-Level Arrays
- **Level 1**: Route serial number (starting from 0)
- **Level 2**: Passenger serial number (starting from 0)

Arrays: seat, discount_id, baggage

### Examples

**Seat Selection with Transfers:**
```json
"seat": [["11,12","12,13"],["14","15"]]
```
- 11,12: First passenger seats (before/after transfer on route 1)
- 12,13: Second passenger seats (before/after transfer on route 1)
- 14: First passenger seat on route 2
- 15: Second passenger seat on route 2

**Discount Assignment:**
```json
"discount_id": {"1": [91,93]}
```
- 93: Discount for second passenger on second route

**Baggage Selection:**
```json
"baggage": [["82,85"], ["82,84", "83"]]
```
- 82,85: Two baggage items for passenger 1 on route 1
- 82,84: Two baggage items for passenger 1 on route 2
- 83: One baggage item for passenger 2 on route 2


## Example Requests

### Minimum Parameters
For routes with simplified booking (`fast_booking = 1` or minimal requirements):

```php
$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "v" => "1.1",
  "date" => ["2023-12-30"],
  "interval_id" => ["ju34hd|30122023|30122023"],
  "currency" => "EUR",
  "lang" => "en"
];
```

### Family Booking (Bus)
```php
$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "v" => "1.1",
  "date" => ["2023-12-30"],
  "interval_id" => ["12850|RXVyb2Npd...MzIxOA=="],
  "seat" => [["5","6","9","10"]],
  "name" => ["John", "Jane", "Ben", "Zoe"],
  "surname" => ["Doe", "Doe", "Doe", "Doe"],
  "birth_date" => ["1992-01-01", "2002-02-02", "2012-03-03", "2022-04-04"],
  "doc_type" => [1,1,3,3],
  "doc_number" => ["CZRE5752475-54", "ZARE4964197-62", "45689205", "92975618"],
  "discount_id" => [[3200,3197,3196]],
  "phone" => "440776251258",
  "email" => "info@seznam.cz",
  "currency" => "EUR",
  "lang" => "en"
];
```

### Flight Booking
```php
$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "v" => "1.1",
  "date" => ["2023-12-30"],
  "interval_id" => ["1597|ef58a1392039745c69aec1afe097db91|32acab539f26d9a04adc3ba7d58bd840_2400^^0|PRG|KBP"],
  "seat" => [["adt","adt","chd","inf"]], // Adult, Adult, Child, Infant
  "name" => ["John", "Jane", "Ben", "Zoe"],
  "surname" => ["Doe", "Doe", "Doe", "Doe"],
  "birth_date" => ["1992-01-01", "2002-02-02", "2012-03-03", "2022-04-04"],
  "doc_type" => [1,1,3,3],
  "doc_number" => ["CZRE5752475-54", "ZARE4964197-62", "45689205", "92975618"],
  "doc_expire_date" => ["2037-01-01", "2027-02-02", "2028-03-03", "2038-04-04"],
  "citizenship" => ["UK","UA","UA","UA"],
  "gender" => ["M","F","M","F"],
  "phone" => "440776251258",
  "email" => "info@seznam.cz",
  "currency" => "EUR",
  "lang" => "en"
];
```

## Response Format

### Success Response
```json
{
  "order_id": "5397146",
  "reservation_until": "2023-11-30 18:17:09",
  "reservation_until_min": "30",
  "security": "133918",
  "status": "reserve_ok",
  "price_total": "131.54",
  "currency": "EUR",
  "promocode_info": {
    "promocode_valid": "0"
  },
  "item": [
    {
      "trip_id": "0",
      "interval_id": "5886",
      "bus_id": "8554",
      "route_id": "100502",
      "trans": "bus",
      "date_from": "2023-11-30",
      "time_from": "02:50:00",
      "point_id_from": "3",
      "point_id_to": "6",
      "point_from": "Lviv",
      "station_from": "Bus Station, ул. Стрийска 109",
      "date_to": "2023-11-30",
      "time_to": "10:00:00",
      "point_to": "Warsaw",
      "station_to": "Bus Station \"Zachodnia\", Al. Jerozolimskie 144",
      "route_name": "Kyiv - Prague",
      "carrier": "Oles Trans Carrier",
      "passengers": [
        {
          "passenger_id": "0",
          "transaction_id": "1036741",
          "name": "Ivan",
          "surname": "Kozak",
          "middlename": "Ivanovich",
          "doc_type": "1",
          "doc_number": "CZRE5752475-56",
          "birth_date": "2003-03-24",
          "citizenship": "UK",
          "gender": "M",
          "doc_expire_date": "2045-12-30",
          "seat": "4",
          "discount": "15% student",
          "price": "60.77",
          "provision": "0",
          "baggage": [
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
              "price": "0",
              "currency": "EUR",
              "baggage_ticket_id": "46"
            }
          ]
        }
      ]
    }
  ]
}
```

### Response Fields
- **order_id**: Generated unique order identifier
- **reservation_until**: Reservation expiration timestamp
- **reservation_until_min**: Reservation duration in minutes
- **security**: Security code for order verification
- **status**: Order status (reserve_ok for successful reservation)
- **price_total**: Total payable amount
- **currency**: Price currency
- **item**: Array of booked route segments with passenger details

## Error Responses

### Authentication Errors
```json
{
  "error": "dealer_no_activ",
  "detail": "Dealer not active"
}
```

### Route Errors
```json
{
  "error": "interval_no_found"
}
```

```json
{
  "error": "route_no_activ"
}
```

```json
{
  "error": "interval_no_activ"
}
```

### Booking Errors
```json
{
  "error": "new_order"
}
```

```json
{
  "error": "no_seat"
}
```

### Data Validation Errors
```json
{
  "error": "date"
}
```

```json
{
  "error": "no_name"
}
```

```json
{
  "error": "no_phone"
}
```

```json
{
  "error": "no_doc"
}
```
```json
{
  "error": "no_birth_date"
}
```
