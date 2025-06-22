---
layout: default
title: buy_ticket
description: Complete ticket purchase based on a generated order from new_order
nav_order: 3
parent: Order Info
---

# buy_ticket

Finalize ticket purchase for an existing order created through `new_order`, completing the payment process and generating confirmed tickets with PDF download links.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/buy_ticket.php`  
**Method:** POST  
**Type:** Required function for ticket completion


## Description

This function processes the final payment for a reserved order, converting it from reservation status to confirmed tickets. Upon successful payment, the system generates individual ticket IDs, security codes, and PDF download links for each passenger.

### Key Features
- Complete order payment processing
- Individual ticket generation for each passenger
- PDF download links for all tickets
- Baggage confirmation and tracking
- Commission/provision calculations
- Automatic ticket security code generation

### Prerequisites
- Valid order created through `new_order`
- Order must be in reservation status (not expired)
- Sufficient account balance or payment method
- All required passenger data validated


## Parameters

### Authentication Parameters
- **login** (string, required): Your login credentials
- **password** (string, required): Your password

### Basic Parameters
- **v** (string, required): API version (use "1.1")
- **order_id** (integer, required): Order ID from previous `new_order` request
- **lang** (string, required): Response language [en, ru, ua, de, pl, cz]


## Example Request

```php
$url = 'https://test-api.bussystem.eu/server/curl/buy_ticket.php';

$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "v" => "1.1",
  "order_id" => 5397146,
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


## Response Format

### Successful Purchase Response
```json
{
  "order_id": "5397146",
  "price_total": "105.30",
  "currency": "EUR",
  "link": "https://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=5397146&security=133918&lang=en",
  "item": [
    {
      "passenger_id": "0",
      "transaction_id": "4000192282",
      "ticket_id": "4461298",
      "security": "525633",
      "price": "52.65",
      "provision": "5.27",
      "currency": "EUR",
      "link": "https://test-api.bussystem.eu/viev/frame/print_ticket.php?ticket_id=4461298&security=525633&lang=en",
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
      ]
    },
    {
      "passenger_id": "1",
      "transaction_id": "4000192283",
      "ticket_id": "4461299",
      "security": "915226",
      "price": "52.65",
      "provision": "5.27",
      "currency": "EUR",
      "link": "https://test-api.bussystem.eu/viev/frame/print_ticket.php?ticket_id=4461299&security=915226&lang=en",
      "baggage": [
        {
          "baggage_ticket_id": "47",
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
        },
        {
          "baggage_ticket_id": "48",
          "baggage_busowner_id": "84",
          "baggage_status": "buy",
          "baggage_type": "small_baggage",
          "baggage_title": "Cabin baggage",
          "length": "50",
          "width": "25",
          "height": "15",
          "kg": "8",
          "price": "10",
          "currency": "EUR",
          "reg_status": "no_registered"
        }
      ]
    }
  ]
}
```


## Response Structure

### Order Summary
- **order_id**: Original order identifier
- **price_total**: Total amount charged for entire order
- **currency**: Currency used for payment
- **link**: PDF download link for all tickets in the order

### Individual Ticket Information
Each `item` in the response represents one passenger ticket:

#### Ticket Identification
- **passenger_id**: Passenger sequence number from original order
- **transaction_id**: Unique transaction identifier for this ticket
- **ticket_id**: Individual ticket number
- **security**: Ticket-specific security code for verification

#### Financial Information
- **price**: Individual ticket price in specified currency
- **provision**: Your commission/earnings for this ticket
- **currency**: Payment currency

#### Access Information
- **link**: Direct PDF download link for this specific ticket

#### Baggage Details
Array of baggage items for this passenger:
- **baggage_ticket_id**: Unique baggage identifier in order
- **baggage_busowner_id**: Carrier's baggage ID
- **baggage_status**: Confirmation status [buy = confirmed]
- **baggage_type**: Category of baggage
- **baggage_title**: Human-readable baggage description
- **length/width/height**: Dimension limits in centimeters
- **kg**: Weight limit in kilograms
- **price**: Baggage fee charged
- **price_back**: Refund amount if cancelled (may be empty)
- **currency**: Currency for baggage pricing
- **reg_status**: Check-in status [no_registered, registered, unregistered, delivered]


## Error Responses

### Authentication Errors
```json
{
  "error": "dealer_no_activ",
  "detail": "Dealer not active"
}
```

### Payment Errors

**Sales Not Available:**
```json
{
  "error": "buy_no_activ"
}
```

**Insufficient Funds:**
```json
{
  "error": "deposit"
}
```

**Invalid Order:**
```json
{
  "error": "order"
}
```

### Route Errors

**Route Not Active:**
```json
{
  "item": [
    {
      "error": "route_no_activ"
    }
  ]
}
```

### Passenger-Specific Errors

**Seat No Longer Available:**
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

**Ticket Purchase Failed:**
```json
{
  "item": [
    {
      "trip_id": "0",
      "passengers": [
        {
          "passenger_id": "0",
          "error": "buy_ticket"
        }
      ]
    }
  ]
}
```

**Transaction Error:**
```json
{
  "item": [
    {
      "trip_id": "0",
      "passengers": [
        {
          "passenger_id": "0",
          "error": "transaction"
        }
      ]
    }
  ]
}
```

**Inactive Interval:**
```json
{
  "item": [
    {
      "trip_id": "0",
      "passengers": [
        {
          "passenger_id": "0",
          "error": "interval_no_activ"
        }
      ]
    }
  ]
}
```



## Important Notes

### Timing Considerations
- **Reservation Expiration**: Orders expire after specified timeout (typically 20 minutes)
- **Seat Availability**: Seats may become unavailable between reservation and purchase
- **Price Changes**: Prices are locked during reservation period

### PDF Ticket Access
- **Individual Links**: Each passenger receives a unique download link
- **Order Link**: Single link downloads all tickets in one PDF
- **Security Codes**: Required for ticket verification and access
- **Language Support**: PDF language matches the `lang` parameter

### Commission Structure
- **Provision**: Your earnings per ticket in specified currency
- **Calculation**: Based on your agreement and ticket price
- **Currency**: Always matches the transaction currency

### Baggage Management
- **Status Tracking**: All baggage items confirmed with "buy" status
- **Check-in Process**: Initially "no_registered", updates as passengers check in
- **Pricing**: Individual pricing per baggage item
- **Refund Policy**: Some baggage may have different refund rules