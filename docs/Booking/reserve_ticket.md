---
layout: default
title: reserve_ticket
description: Create ticket reservations with payment on boarding when available
nav_order: 2
parent: Booking
---

# reserve_ticket

Create ticket reservations with payment on boarding for carriers and routes that support this payment method.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/reserve_ticket.php`  
**Method:** POST  
**Type:** Optional function

---

## Description

This function creates ticket reservations that allow payment directly on the vehicle (bus/train) during boarding. This service is only available for specific carriers and routes that support payment on boarding.

### Requirements
- Valid order ID from a previous `new_order` request
- Carrier and route must support payment on boarding
- Phone number verification may be required via SMS

---

## Parameters

### Authentication Parameters
- **login** (string, required): Your login credentials
- **password** (string, required): Your password

### Basic Parameters
- **v** (string, required): API version (use "1.1")
- **order_id** (integer, required): Order ID from previous `new_order` request
- **lang** (string, required): Response language [en, ru, ua, de, pl, cz]

### Optional Contact Information
- **phone** (string, optional): Primary contact phone number for all passengers
- **phone2** (string, optional): Secondary contact phone number
- **email** (string, optional): Contact email address for all passengers
- **info** (string, optional): Additional passenger information or special requests

---

## Example Request

```php
$url = 'https://test-api.bussystem.eu/server/curl/reserve_ticket.php';

$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "v" => "1.1",
  "order_id" => 1026665,
  "phone" => "440776251258",
  "phone2" => "380776251258",
  "email" => "info@seznam.cz",
  "info" => "I want near the window",
  "lang" => "en"
];
```

---

## Response Format

### Success Response
```json
{
  "order_id": "1026665",
  "item": [
    {
      "trip_id": "0",
      "interval_id": "2759016",
      "route_id": "14379",
      "date_from": "2023-02-20",
      "time_from": "14:00:00",
      "point_from": "Прага",
      "station_from": "Автовокзал \"Флоренц\", пл.8",
      "date_to": "2023-02-21",
      "time_to": "18:30:00",
      "point_to": "Київ",
      "station_to": "Автостанция \"Киев\", ул.С.Петлюры 32, (Ж/Д Вокзал)",
      "route_name": "Прага - Краков - Черновцы",
      "carrier": "OOO Avtocombinat-1",
      "passengers": [
        {
          "passenger_id": "0",
          "transaction_id": "1037500",
          "name": "John",
          "surname": "Doe",
          "seat": "42",
          "ticket_id": "20834",
          "security": "613464",
          "reserve_before": "2023-02-20 14:00:00"
        }
      ]
    }
  ]
}
```

### Response Fields
- **order_id**: Original order identifier
- **trip_id**: Trip identifier from the original booking
- **interval_id**: Route interval identifier
- **route_id**: Route identifier
- **date_from/date_to**: Departure and arrival dates
- **time_from/time_to**: Departure and arrival times
- **point_from/point_to**: Departure and arrival cities
- **station_from/station_to**: Specific station information
- **route_name**: Complete route description
- **carrier**: Transport carrier name
- **passenger_id**: Passenger identifier
- **transaction_id**: Unique transaction identifier
- **seat**: Assigned seat number
- **ticket_id**: Generated ticket number
- **security**: Ticket security code
- **reserve_before**: Reservation expiration timestamp

---

## Error Responses

### Authentication Errors
```json
{
  "error": "dealer_no_activ",
  "detail": "Dealer not active"
}
```

### Order Validation Errors

**Missing or Invalid Order ID:**
```json
{
  "error": "order_id"
}
```

**Order Not Found:**
```json
{
  "error": "order"
}
```

**Existing Reservations Conflict:**
```json
{
  "error": "reserve_validation",
  "detail": "For this phone number there are already reservations for payment on boarding: 7777,8888"
}
```

**SMS Verification Required:**
```json
{
  "error": "need_sms_validation",
  "detail": "For pay-as-you-go reservations, phone number confirmation via text message is required"
}
```

### Route and Service Errors

**Reservation Not Available:**
```json
{
  "error": "reserve",
  "detail": "Order cannot be booked as no reservation is available on one of the flights"
}
```

**Inactive Route Interval:**
```json
{
  "error": "interval_no_activ"
}
```

### Passenger-Specific Errors

**Missing Passenger Data:**
```json
{
  "item": [
    {
      "trip_id": "0",
      "passengers": [
        {
          "passenger_id": "0",
          "error": "data_passenger"
        }
      ]
    }
  ]
}
```

**Ticket Details Not Found:**
```json
{
  "item": [
    {
      "trip_id": "0",
      "passengers": [
        {
          "passenger_id": "0",
          "error": "reserve_ticket"
        }
      ]
    }
  ]
}
```

**Seat Unavailable:**
```json
{
  "item": [
    {
      "trip_id": "0",
      "passengers": [
        {
          "passenger_id": "0",
          "error": "free_seat"
        }
      ]
    }
  ]
}
```

---

## Important Notes

### Phone Number Verification
- Some carriers require SMS verification for payment-on-boarding reservations
- If verification is required, use the `sms_validation` endpoint before attempting reservation
- Multiple reservations for the same phone number may be restricted

### Payment Process
- Payment is collected directly by the driver/conductor during boarding
- Reservation must be confirmed before the specified `reserve_before` timestamp
- Ticket security code must be presented during boarding

### Availability
- Not all routes support payment on boarding
- Check route details from `get_routes` for payment method availability
- Some carriers may have specific restrictions or requirements