---
layout: default
title: get_order
description: Get comprehensive information about an entire order including routes, passengers, payment status, and history
nav_order: 1
parent: Order Info
---

# get_order

Retrieve complete information about an order including all routes, passengers, payment methods, and order history.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_order.php`  
**Method:** POST  
**Type:** Optional function



## Description

This function provides comprehensive details about an order, including passenger information, route details, payment status, available payment methods, and complete order history. The response varies based on the current order status (reserve, reserve_ok, confirmation, buy, cancel).

### Key Features
- Complete order information retrieval
- Payment method listings for unpaid orders
- Historical tracking of order changes
- Passenger and route details
- Baggage information
- Security verification



## Parameters

### Authentication Parameters
- **login** (string, required): Your login credentials
- **password** (string, required): Your password

### Order Identification
- **order_id** (integer, required): Order ID from `new_order` or `buy_ticket` request
- **security** (string, optional): Security order code (not required for ticket sellers)

### Basic Parameters
- **lang** (string, required): Response language [en, ru, ua, de, pl, cz]



## Example Request

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_order.php';

$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "order_id" => 5397146,
  "security" => "133918",
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

### Complete Order Response
```json
{
  "order_id": "1026448",
  "security": "487857",
  "status": "reserve_ok",
  "automatic_cancel": "1",
  "lang": "ua",
  "url": "http://test-ws.bussystem.eu/booking?order_id=1026448&security=487857",
  "price": "56.70",
  "currency": "EUR",
  "dealer_id": "777",
  "dealer": "Dealer TEST",
  "phone_dealer": "+380776251258",
  "email_dealer": "dealer@dealer.com",
  "inspector_id": "0",
  "routes": [
    {
      "route_id": "3358",
      "interval_id": "3358|NDQ2NjQ1ZDA0OWVmNjEyMjEzMDFmZGZhNWUyY2RkMTN8...",
      "timetable_id": "",
      "trans": "bus",
      "route_name": "3358 Прага - Київ",
      "carrier": "Carrier TEST",
      "supplier_code": "",
      "point_id_from": "3",
      "point_from": "Прага",
      "point_id_to": "6",
      "point_to": "Київ",
      "station_from": "Автовокзал \"Флоренц\"",
      "station_from_lat": "50.0895953782425",
      "station_from_lon": "14.440726339817",
      "station_to": "Автовокзал \"Киев\"",
      "station_to_lat": "50.4427213824899",
      "station_to_lon": "30.4932510852814",
      "date_from": "2022-10-28",
      "date_to": "2022-10-28",
      "time_from": "01:15:00",
      "time_to": "05:00:00",
      "change_route": "0",
      "day_open": "",
      "time_in_way": "2:45",
      "route_foto": "",
      "cancel_only_order": "0",
      "route_back": "0",
      "passengers": [
        {
          "client_name": "Ivan",
          "client_surname": "Kozak",
          "client_middlename": "Ivanovich",
          "birth_date": "1983-05-05",
          "doc_type": "3",
          "doc_number": "CZRE5752575-77",
          "doc_expire_date": "",
          "citizenship": "",
          "gender": "M",
          "price": "28.35",
          "currency": "EUR",
          "TRF": "28.35",
          "currency_TRF": "EUR",
          "discount_name": "0",
          "seat": "***",
          "ticket": {
            "transaction_id": "1037006",
            "ticket_status": "request"
          }
        }
      ]
    }
  ],
  "type_pay": "",
  "platba_id": "",
  "platba_type_id": "",
  "text": "",
  "history": {
    "date": "2022-10-26 09:25:47",
    "reservation_until": "2022-10-26 09:40:47",
    "reservation_until_min": "0",
    "date_reservation_ok": "2022-10-26 09:25:47",
    "name_reservation_ok": "Server BusSystem",
    "date_buy": "",
    "name_buy": "",
    "date_cancel": "",
    "name_cancel": ""
  },
  "client": {
    "card_number": "",
    "name": "",
    "email": "",
    "phone": "+420776251251",
    "phone2": "",
    "info": ""
  },
  "pay_method": {
    "system": [
      {
        "type": "instant_payment",
        "type_pay": "card",
        "title": "Оплата банковской картой OnLine",
        "desc": "Оплата билета будет производиться в валюте BYN, остальные валюты будут сконвертированы по курсу Вашего банка.<br><br>Комиссия 0%",
        "icon": "http://test-ws.bussystem.eu/images/partners/pay/bepaid.png",
        "pay_variants": [
          {
            "url": "http://test-api.bussystem.eu/server/cs_post/post.php?order_id=1026448&security=487857&currency=BYN&system=bepaid",
            "amount": "145.11 BYN"
          }
        ]
      }
    ]
  }
}
```



## Response Structure

### Order Information
- **order_id**: Unique order identifier
- **security**: Security verification code
- **status**: Current order status [reserve, reserve_ok, confirmation, buy, cancel]
- **automatic_cancel**: Whether order auto-cancels after timeout
- **url**: Payment processing URL
- **price**: Total amount due
- **currency**: Payment currency

### Optional Order Elements

**Promotional Code Information:**
```json
"promocode_info": {
  "promocode_valid": 1,
  "promocode_name": "L7XJP8",
  "price_promocode": 5
}
```

**Bonus Information:**
```json
"bonus": 4.5,
"bonus_points_added": 105
```

**Paid Order Link:**
```json
"link": "http://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=1026448&security=487857&lang=ua"
```

**Agent Information:**
```json
"agent": "Agent TEST",
"phone_agent": "+380886251258",
"email_agent": "agent@agent.com"
```

**Payment Transaction ID:**
```json
"pay_transaction": "1061163"
```

**Pending Refunds:**
```json
"pending_refunds": [
  {
    "id": "1",
    "zakaz_id": "1026448",
    "ticket_id": "4461299"
  }
]
```

### Route Information
Each route contains:
- **Route Details**: ID, name, carrier, transport type
- **Geographic Data**: Departure/arrival points with coordinates
- **Schedule**: Dates, times, duration
- **Transfer Information**: Change points and connections
- **Visual Elements**: Route photos if available

### Passenger Information
For each passenger:
- **Personal Data**: Name, surname, middle name, birth date
- **Documentation**: Document type, number, expiration, citizenship
- **Ticket Details**: Price, seat assignment, discounts
- **Transaction Info**: Transaction ID, ticket status

### Paid Ticket Information
For confirmed tickets:
```json
"ticket": {
  "transaction_id": "1036741",
  "ticket_id": "4461298",
  "security": "525633",
  "ticket_status": "buy",
  "baggage": [
    {
      "baggage_ticket_id": "49",
      "baggage_status": "buy",
      "baggage_type": "small_baggage",
      "baggage_title": "Hand baggage",
      "length": "35",
      "width": "10",
      "height": "10",
      "kg": "5",
      "price": "5.00",
      "currency": "EUR"
    }
  ]
}
```

### Payment Methods (Unpaid Orders)
Available payment systems with:
- **System Type**: instant_payment, delayed_payment
- **Payment Type**: card, banking, emoney, cash, in_bus
- **Provider Details**: Title, description, icon
- **Currency Options**: Multiple payment variants with URLs

### Order History
Complete timeline including:
- **Reservation**: Date/time and responsible party
- **Payment**: Date/time and payment method
- **Cancellation**: Date/time and reason (if applicable)
- **Expiration**: Reservation timeout information



## Error Responses

### Authentication Errors
```json
{
  "error": "dealer_no_activ",
  "detail": "Dealer not active"
}
```

### Order Not Found
```json
{
  "error": "no_found"
}
```



## Order Status Values

- **reserve**: Initial reservation created
- **reserve_ok**: Reservation confirmed
- **confirmation**: Awaiting payment confirmation
- **buy**: Payment completed, tickets issued
- **cancel**: Order cancelled
