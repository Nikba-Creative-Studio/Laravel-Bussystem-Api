---
layout: default
title: get_ticket
description: Get comprehensive information about individual tickets or all tickets in an order
nav_order: 2
parent: Order Info
---

# get_ticket

Retrieve detailed information about specific tickets or all tickets within an order, including passenger details, route information, pricing, and cancellation policies.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_ticket.php`  
**Method:** POST  
**Type:** Optional function



## Description

This function provides comprehensive ticket information including passenger data, route details, pricing breakdown, cancellation policies, baggage information, and PDF download links. You can retrieve either a specific ticket or all tickets within an order.

### Key Features
- Individual ticket or full order ticket retrieval
- Complete pricing and currency conversion details
- Cancellation policies with time-based rates
- Baggage information and status
- PDF ticket download links
- Barcode information for carrier identification



## Parameters

### Authentication Parameters
- **login** (string, required): Your login credentials
- **password** (string, required): Your password

### Ticket Identification (Choose One)
- **order_id** (integer, optional): Order ID to display all tickets from `new_order` or `buy_ticket` request
- **ticket_id** (integer, optional): Specific ticket ID to display only one ticket from `buy_ticket` request

### Security & Language
- **security** (string, optional): Order or ticket security code (not required for ticket sellers)
- **lang** (string, required): Response language [en, ru, ua, de, pl, cz]



## Example Request

### Get Specific Ticket
```php
$url = 'https://test-api.bussystem.eu/server/curl/get_ticket.php';

$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "ticket_id" => 4461298,
  "security" => "525633",
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

### Get All Order Tickets
```php
$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "order_id" => 5397146,
  "security" => "133918",
  "lang" => "en"
];
```



## Response Format

### Ticket Information Array
```json
[
  {
    "transaction_id": "1037219",
    "dealer": "Dealer TEST",
    "dealer_inn": "",
    "agent": "Agent TEST",
    "address_dealer": "",
    "phone_dealer": "+380776251258",
    "dealer_id": "100751",
    "ticket_id": "20686",
    "carrier_ticket_id": "0",
    "security": "853157",
    "ticket_status": "buy",
    "eticket": "1",
    "inland": "0",
    "speed_type": "0",
    "only_original": "0",
    "seat": "***",
    "ticket_full_price": "1900",
    "discount": "0",
    "ticket_price": "1900",
    "TAX_CAR": "211.11",
    "TAX_DEAL": "0",
    "ticket_currency": "UAH",
    "price": "52.65",
    "price_cancel": "0",
    "money_back_if_cancel": "52.65",
    "money_noback_if_cancel": "0",
    "provision_rate": "0",
    "provision": "0",
    "provision_cancel": "0",
    "currency": "EUR",
    "exchange_rate": "0.024",
    "exchange_type": "2",
    "date_from": "2022-11-19",
    "time_from": "22:30:00",
    "point_from": "Прага",
    "station_from": "Автовокзал \"Флоренц\"",
    "station_from_lat": "50.0895953782425",
    "station_from_lon": "14.440726339817",
    "change_route": "0",
    "date_to": "2022-11-20",
    "time_to": "21:30:00",
    "point_to": "Київ",
    "station_to": "Автостанция \"Киев\"",
    "station_to_lat": "50.4427213824899",
    "station_to_lon": "30.4932510852814",
    "client_id": "945202",
    "name": "Ivan",
    "surname": "Kozak",
    "middlename": "Ivanovich",
    "birth_date": "2003-03-24",
    "gender": "M",
    "citizenship": "",
    "doc_type": "3",
    "doc_number": "CZRE5752575-77",
    "phone": "+420776251251",
    "phone_second": "",
    "email": "",
    "info": "",
    "buy_timedate": "2022-11-17 20:07:27",
    "reserve_timedate": "0000-00-00 00:00:00",
    "reserve_before": "",
    "reg_open_timedate": "0000-00-00 00:00:00",
    "refuse_timedate": "0000-00-00 00:00:00",
    "buy_name": "Agent TEST (Dealer TEST)",
    "reserve_name": "",
    "reg_open_name": "",
    "refuse_name": "",
    "free_min": "30",
    "hours_after_buy": "0",
    "hours_before_depar": "50.3547",
    "cancel_rate": "0",
    "route_name": "Прага - Киев",
    "route_id": "100233",
    "carrier": "Client TEST",
    "carrier_id": "100094",
    "carrier_logo": "",
    "carrier_ic": "",
    "driver_phone": [
      "+420776251251"
    ],
    "luggage": "1 единица до 25кг + ручна кладь до 5 кг - бесплатно , каждая следующая - 20 UAH",
    "reg_open": "",
    "day_open_max": "180",
    "day_open": "-",
    "day_open_id": "",
    "route_info": "Возможна посадка по електронному билету.",
    "dispatcher_phone": "+420 790 889 535, FREE LINE 0800 60 32 14 (UKR)",
    "cancel_hours_info": [
      {
        "hours_after_depar": "10001",
        "hours_before_depar": "24",
        "cancel_rate": "30",
        "money_back": "41.12"
      },
      {
        "hours_after_depar": "24",
        "hours_before_depar": "1.5",
        "cancel_rate": "50",
        "money_back": "29.37"
      },
      {
        "hours_after_depar": "1.5",
        "hours_before_depar": "0",
        "cancel_rate": "100",
        "money_back": "0"
      }
    ],
    "country_from_id": "1",
    "country_to_id": "2",
    "cancel_server": "0",
    "route_type": "bus",
    "route_buy": "1",
    "cancel_only_order": "0",
    "carrier_ticket": "0",
    "ws": "1",
    "link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?ticket_id=4461298&security=525633&lang=ua",
    "baggage": [
      {
        "baggage_ticket_id": "46",
        "baggage_busowner_id": "81",
        "baggage_status": "buy",
        "baggage_type": "small_baggage",
        "baggage_title": "Hand baggage",
        "length": "35",
        "width": "10",
        "height": "10",
        "kg": "5",
        "price": "0.00",
        "price_back": "",
        "currency": "EUR",
        "reg_status": "no_registered"
      }
    ],
    "log_id": "3609"
  }
]
```



## Response Structure

### Ticket Identification
- **transaction_id**: Unique transaction identifier
- **ticket_id**: Ticket number
- **carrier_ticket_id**: Carrier's internal ticket ID (if different)
- **security**: Ticket security verification code
- **ticket_status**: Current status [request, reserve, buy, cancel]

### Ticket Type & Requirements
- **eticket**: Whether ticket is electronic (1) or requires exchange form (0)
- **inland**: Domestic city interval request availability (0/1)
- **speed_type**: High-speed bus indicator (0/1)
- **only_original**: Requires original PDF for boarding (0/1)
- **seat**: Seat assignment or "***" for free seating

### Pricing Information
- **ticket_full_price**: Full price in carrier's currency
- **discount**: Discount name or "0" if none
- **ticket_price**: Price after discount in carrier's currency
- **TAX_CAR**: Carrier surcharge
- **TAX_DEAL**: Dealer surcharge
- **ticket_currency**: Carrier's currency
- **price**: Final price in selected currency
- **currency**: Selected/available currency
- **exchange_rate**: Currency conversion rate
- **exchange_type**: Exchange calculation method (0=not used, 1=price/rate, 2=price*rate)

### Cancellation & Refund
- **price_cancel**: Current refund amount if cancelled
- **money_back_if_cancel**: Amount system will refund
- **money_noback_if_cancel**: Non-refundable amount
- **cancel_rate**: Current cancellation fee percentage
- **free_min**: Minutes for free cancellation after purchase
- **cancel_hours_info**: Time-based cancellation policy array

### Route Information
- **date_from/date_to**: Departure and arrival dates
- **time_from/time_to**: Departure and arrival times
- **point_from/point_to**: Departure and arrival cities
- **station_from/station_to**: Station names and coordinates
- **change_route**: Number of transfers (0 = direct)
- **route_name**: Complete route description
- **carrier**: Transport company name

### Passenger Details
- **name/surname/middlename**: Passenger names
- **birth_date**: Date of birth
- **gender**: M/F
- **citizenship**: Country code
- **doc_type**: Document type [1=foreign passport, 2=internal passport, 3=birth certificate]
- **doc_number**: Document number
- **phone/phone_second**: Contact phone numbers
- **email**: Contact email

### Timing & History
- **buy_timedate**: Purchase date/time
- **reserve_timedate**: Reservation date/time
- **reserve_before**: Reservation expiration
- **hours_after_buy**: Hours since purchase
- **hours_before_depar**: Hours until departure
- **buy_name/reserve_name**: Who processed the transaction

### Service Information
- **carrier_logo**: Carrier logo filename
- **driver_phone**: Array of driver contact numbers
- **luggage**: Baggage policy description
- **route_info**: Special boarding instructions
- **dispatcher_phone**: Emergency contact numbers

### Baggage Details
Each baggage item includes:
- **baggage_ticket_id**: Baggage ID in order
- **baggage_status**: Status [reserve, buy, cancel]
- **baggage_type/baggage_title**: Type and description
- **length/width/height**: Dimensions in centimeters
- **kg**: Weight limit in kilograms
- **price**: Cost in specified currency
- **reg_status**: Check-in status [no_registered, registered, unregistered, delivered]

### Additional Features
- **link**: PDF ticket download URL
- **barcode_type**: Barcode encoding [C128A, C128B, EAN13, etc.]
- **barcode_number**: Encoded ticket identifier
- **carrier_booking_number**: Carrier's booking reference



## Cancellation Policy Structure

The `cancel_hours_info` array provides time-based cancellation rates:

```json
"cancel_hours_info": [
  {
    "hours_after_depar": "10001",
    "hours_before_depar": "24", 
    "cancel_rate": "30",
    "money_back": "41.12"
  }
]
```

### Policy Interpretation
- **hours_after_depar**: Valid from X hours after departure (10001 = unlimited)
- **hours_before_depar**: Valid until X hours before departure
- **cancel_rate**: Percentage fee for cancellation in this period
- **money_back**: Actual refund amount (if calculated)



## Error Responses

### Authentication Errors
```json
{
  "error": "dealer_no_activ",
  "detail": "Dealer not active"
}
```

### Ticket Not Found
```json
{
  "error": "ticket_id"
}
```

### Payment Not Confirmed
```json
{
  "error": "payment_process"
}
```



## Important Notes

### PDF Requirements
- **only_original = 1**: Must have original A4 PDF for boarding
- **only_original = 0**: Electronic ticket sufficient if `eticket = 1`

### Barcode Information
- **barcode_type**: Standard encoding formats for carrier scanning
- **barcode_number**: Encoded identifier for ticket verification
- Used by carriers for automated ticket validation

### Baggage Status Tracking
- **no_registered**: Not checked in
- **registered**: Checked in and accepted
- **unregistered**: Checked out/removed
- **delivered**: Successfully delivered