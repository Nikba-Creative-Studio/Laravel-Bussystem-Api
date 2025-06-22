---
layout: default
title: cancel_ticket
description: Cancel unpaid orders, reservations, or process full/partial refunds for paid tickets
nav_order: 1
parent: Cancel Ticket
---

# cancel_ticket

Cancel unpaid orders, reservation cancellations, or process refunds for paid tickets with automatic calculation of penalties and refund amounts based on carrier policies and timing.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/cancel_ticket.php`  
**Method:** POST  
**Type:** Cancellation and refund function


## Description

This function handles all types of ticket and order cancellations, from unpaid reservation cancellations to paid ticket refunds with automatic penalty calculations. The system determines refund amounts based on carrier policies, timing rules, and current cancellation rates.

### Key Features
- Cancel entire orders or individual tickets
- Automatic refund calculation based on timing and policies
- Free cancellation periods for paid tickets
- Time-based penalty rates
- Baggage refund processing
- Commission handling for agents
- Carrier-initiated cancellation support

### Cancellation Types
- **Unpaid Orders**: Free cancellation of reservations
- **Paid Tickets**: Refunds with time-based penalties
- **Free Period**: No-penalty cancellation within specified time
- **Carrier Cancellation**: Full refunds when routes are cancelled by carrier


## Parameters

### Authentication Parameters
- **login** (string, required): Your login credentials
- **password** (string, required): Your password
- **session** (string, optional): Session identifier for additional security

### Basic Parameters
- **v** (string, required): API version (use "1.1")
- **lang** (string, required): Response language [en, ru, ua, de, pl, cz]

### Cancellation Target (Choose One)
- **order_id** (integer, optional): Order ID to cancel all tickets in the order
- **ticket_id** (integer, optional): Specific ticket ID to cancel only one ticket


## Example Requests

### Cancel Entire Order
```php
$url = 'https://test-api.bussystem.eu/server/curl/cancel_ticket.php';

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

### Cancel Individual Ticket
```php
$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "v" => "1.1",
  "ticket_id" => 4461298,
  "lang" => "en"
];
```

## Response Format

### Unpaid Order Cancellation
```json
{
  "order_id": "5397146",
  "cancel_order": "1",
  "price_total": "0",
  "money_back_total": "105.30",
  "currency": "EUR",
  "item": [
    {
      "transaction_id": "4000192282"
    },
    {
      "transaction_id": "4000192283"
    }
  ]
}
```

### Paid Order Cancellation
```json
{
  "order_id": "5397146",
  "cancel_order": "1",
  "price_total": "0",
  "money_back_total": "115.30",
  "currency": "EUR",
  "item": [
    {
      "transaction_id": "4000192282",
      "ticket_id": "4461298",
      "cancel_ticket": "1",
      "price": "0",
      "money_back": "52.65",
      "provision": "5.27",
      "currency": "EUR",
      "hours_after_buy": "16",
      "hours_before_depar": "0",
      "rate": "0",
      "baggage": [
        {
          "baggage_ticket_id": "46",
          "baggage_busowner_id": "81",
          "baggage_status": "cancel",
          "baggage_type": "small_baggage",
          "baggage_title": "Hand baggage",
          "length": "35",
          "width": "10",
          "height": "10",
          "kg": "5",
          "price": "0.00",
          "price_back": "0.00",
          "currency": "EUR",
          "reg_status": "no_registered"
        }
      ]
    },
    {
      "transaction_id": "4000192283",
      "ticket_id": "215442",
      "cancel_ticket": "1",
      "price": "0",
      "money_back": "52.65",
      "provision": "5.27",
      "currency": "EUR",
      "hours_after_buy": "16",
      "hours_before_depar": "9.4739",
      "rate": "0",
      "baggage": [
        {
          "baggage_ticket_id": "47",
          "baggage_busowner_id": "81",
          "baggage_status": "cancel",
          "baggage_type": "small_baggage",
          "baggage_title": "Hand baggage",
          "length": "35",
          "width": "10",
          "height": "10",
          "kg": "5",
          "price": "0.00",
          "price_back": "0.00",
          "currency": "EUR",
          "reg_status": "no_registered"
        },
        {
          "baggage_ticket_id": "48",
          "baggage_busowner_id": "84",
          "baggage_status": "cancel",
          "baggage_type": "small_baggage",
          "baggage_title": "Cabin baggage",
          "length": "50",
          "width": "25",
          "height": "15",
          "kg": "8",
          "price": "10",
          "price_back": "10",
          "currency": "EUR",
          "reg_status": "no_registered"
        }
      ]
    }
  ]
}
```

### Individual Ticket Cancellation
```json
{
  "transaction_id": "4000192282",
  "ticket_id": "4461298",
  "cancel_ticket": "1",
  "price": "0",
  "money_back": "52.65",
  "provision": "5.27",
  "currency": "EUR",
  "hours_after_buy": "16",
  "hours_before_depar": "9.4739",
  "rate": "0",
  "baggage": [
    {
      "baggage_ticket_id": "46",
      "baggage_busowner_id": "81",
      "baggage_status": "cancel",
      "baggage_type": "small_baggage",
      "baggage_title": "Hand baggage",
      "length": "35",
      "width": "10",
      "height": "10",
      "kg": "5",
      "price": "0.00",
      "price_back": "0.00",
      "currency": "EUR",
      "reg_status": "no_registered"
    }
  ]
}
```


## Response Structure

### Order-Level Information
- **order_id**: Order identifier being cancelled
- **cancel_order**: Cancellation success status (1 = successful, 0 = failed)
- **price_total**: Amount retained by system (penalties)
- **money_back_total**: Total refund amount across all tickets
- **currency**: Currency for all monetary amounts

### Ticket-Level Information
Each `item` represents one cancelled ticket:

#### Identification & Status
- **transaction_id**: Unique transaction identifier
- **ticket_id**: Individual ticket number
- **cancel_ticket**: Ticket cancellation success (1 = successful, 0 = failed)

#### Financial Details
- **price**: Amount retained for this ticket (penalties)
- **money_back**: Refund amount for this ticket
- **provision**: Agent commission for this ticket
- **currency**: Currency for ticket amounts
- **rate**: Penalty percentage applied (0 = no penalty, 100 = no refund)

#### Timing Information
- **hours_after_buy**: Hours elapsed since purchase
- **hours_before_depar**: Hours remaining until departure

#### Baggage Cancellation
Array of baggage items cancelled with this ticket:
- **baggage_ticket_id**: Baggage identifier in order
- **baggage_status**: Updated to "cancel"
- **price**: Original baggage fee
- **price_back**: Baggage refund amount
- **reg_status**: Check-in status at time of cancellation


## Cancellation Rules & Policies

### Free Cancellation Period
- **Duration**: Specified in `cancel_free_min` from route information
- **Application**: No penalties applied during this period
- **Start Time**: Begins from ticket purchase time
- **Full Refund**: 100% refund including taxes and fees

### Carrier-Initiated Cancellations
When carriers cancel routes:
- **Full Refund**: 100% refund regardless of timing
- **No Penalties**: All fees and taxes refunded
- **Automatic Processing**: System processes refunds automatically
- **Rate Override**: `cancel_rate = 0` indicates carrier cancellation

### Important Refund Notes
- **System Calculation**: Always use API-returned refund amounts
- **Manual Calculation**: DO NOT calculate refunds manually
- **Rate 100%**: Tickets with 100% penalty rate cannot be cancelled
- **Tax Retention**: When penalties apply, all taxes are typically retained


## Error Responses

### Authentication Errors
```json
{
  "error": "dealer_no_activ",
  "detail": "Dealer not active"
}
```

### Order Cancellation Errors

**Cancellation Error:**
```json
{
  "item": [
    {
      "error": "cancel_order"
    }
  ]
}
```

**System Error:**
```json
{
  "item": [
    {
      "error": "cancel"
    }
  ]
}
```

**100% Penalty Rate:**
```json
{
  "item": [
    {
      "error": "rate_100"
    }
  ]
}
```

### Individual Ticket Errors

**Ticket Not Found:**
```json
{
  "error": "ticket_id"
}
```

**System Cancellation Error:**
```json
{
  "error": "cancel"
}
```

**Cannot Cancel (100% Rate):**
```json
{
  "error": "rate_100"
}
```

## Important Guidelines

### Before Cancellation
1. **Check Eligibility**: Verify tickets can be cancelled (rate < 100%)
2. **Calculate Penalties**: Use API for accurate refund calculations
3. **Customer Confirmation**: Inform customers of penalty amounts
4. **Documentation**: Ensure proper reason codes for cancellations

### Processing Rules
- **API Authority**: Always use API-returned refund amounts
- **No Manual Calculation**: Don't calculate refunds independently
- **Rate 100% Block**: Cannot cancel tickets with 100% penalty rate
- **Timing Critical**: Penalties increase as departure time approaches

### Customer Communication
- **Clear Penalties**: Explain penalty amounts and rates
- **Refund Timeline**: Inform about processing times
- **Alternative Options**: Suggest date changes if available
- **Carrier Policies**: Reference specific carrier cancellation terms