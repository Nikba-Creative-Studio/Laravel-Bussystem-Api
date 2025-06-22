---
title: About
description: Overview of BusSystem API usage options and integration types
theme: jekyll-theme-cayman
nav_order: 1
---

# About BusSystem API

The BusSystem API provides comprehensive integration options for different types of partners in the passenger transportation industry. Choose the integration type that best fits your business model.

## API Usage Options

### For Agencies

Agencies that plan trips, collect payments for purchased tickets, and independently service passengers can utilize all API features.

**Requirements:**
- Refundable deposit
- Signed paper agreement
- Agency income depends on agreement terms

**Special Note for Carrier Websites:**
If you book tickets through the API yourself, you must include your `dispatcher_id=XXX` parameter to manage your accounts or apply alternative commissions when BusSystem handles payments.

### For Affiliate Partners

Affiliate partners who only perform trip searches without collecting payments for bookings.

**Requirements:**
- Always include your `partner=XXXX` parameter in requests before booking
- Partner ID provided after registration at https://website.bussystem.eu/
- Electronic agreement acceptance is sufficient

**Available Functions:**
- All API functions except `buy_ticket`

**Important:** Without the `partner` parameter, bookings will not be identified as your conversions.

#### Integration Examples

**Trip Search - Website Integration:**
```
https://infobus.eu/Kyiv/Lviv?partner=XXXX
https://infobus.eu/Kyiv/Lviv/24.01/7?partner=XXXX
https://infobus.eu/Kyiv/Lviv/24.01/7/12882?partner=XXXX
https://infobus.eu/en/bus/6/7?&partner=XXXX&currency=EUR
https://infobus.eu/train/2200001/2218000/24.01.2024?partner=XXXX
https://infobus.eu/air/PRG/IEV/24.01.2024?partner=XXXX
```

**Frame v1 Integration:**
```
https://iframe.bussystem.eu/booking?partner=XXXX&point_from_id=6&point_to_id=7&date=24.01.2024&currency=UAH&only=bus&lang=ru
```

**Frame v2 Integration:**
```
https://booking.bussystem.eu/Kyiv/Lviv?partner=XXXX
https://booking.bussystem.eu/Kyiv/Lviv/24.01/7?partner=XXXX
https://booking.bussystem.eu/Kyiv/Lviv/24.01/7/12882?partner=XXXX
https://booking.bussystem.eu/ru/bus/6/7?&partner=XXXX&currency=EUR
https://booking.bussystem.eu/train/2200001/2218000/24.01.2024?partner=XXXX
https://infobus.eu/air/PRG/IEV/24.01.2024?partner=XXXX
```

#### Deep Linking Options

**Seat Selection - Website:**
```
https://infobus.eu/deeplink?city_from=6&city_to=7&date_from=24.01.2024&route_id=10649&time_from=15:00&time_to=23:00&partner=XXXX
```

**Seat Selection - Frame v2:**
```
https://booking.bussystem.eu/deeplink?city_from=6&city_to=7&date_from=24.01.2024&route_id=10649&time_from=15:00&time_to=23:00&partner=XXXX
```

**Railway Wagon Selection - Website:**
```
https://infobus.eu/train/deeplink?point_train_from_id=2000000&point_train_to_id=2004000&date_from=24.01.2024&route_id=11618&time_from=00:15&time_to=10:13&partner=XXXX
```

**Railway Wagon Selection - Frame v2:**
```
https://booking.bussystem.eu/train/deeplink?point_train_from_id=2000000&point_train_to_id=2004000&date_from=24.01.2024&route_id=11618&time_from=00:15&time_to=10:13&partner=XXXX
```

**Passenger Data Entry:**
```
https://infobus.eu/deeplink?city_from=6&city_to=7&date_from=24.01.2024&route_id=10649&time_from=15:00&time_to=23:00&passengers=2&partner=XXXX
```

**Order Information and Payment:**
```
Website: https://infobus.eu/payment?id=4267510&code=249343
Frame v1: https://iframe.bussystem.eu/booking?lang=ru&order_id=4267510&security=249343
Frame v2: https://booking.bussystem.eu/payment?id=4267510&code=249343
```

Where `order_id` and `security` are from the `new_order` request and `partner` is your partner ID.

### For Carriers and Dispatchers

Access ticket lists and management functions:
```
https://test-api.bussystem.eu/server/curl_dispatcher/get_tickets.php
```

Examples are available in the Order List → Dispatcher section.

## API URLs

### Test Environment
```
https://test-api.bussystem.eu/server
```
**Note:** You can perform all operations on the test server.

### Production Environment
```
https://api.bussystem.eu/server
```
**Requirements:** Login, password, and URL provided for real flight operations.

## Authentication and Security

- All requests require login and password in POST body
- Data must be sent via HTTP POST method (HTTPS for production)
- Requests accepted only from whitelisted IP addresses
- XML response format by default

### Error Response
If dealer is not found, not active, or credentials are incorrect:

```xml
HTTP/1.1 200 OK
content-type: application/xml

<?xml version="1.0" encoding="UTF-8"?>
<root>
    <error>dealer_no_activ</error>
</root>
```

## Request Formats

### Using JSON

**Requirements:**
- Include header: `Content-type: application/json`

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_points.php';

$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "autocomplete" => "Prague",
  "lang" => "en"
];

$json_data = json_encode($post_data);

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => $json_data,
  CURLOPT_HTTPHEADER => ['Content-Type: application/json']
]);

$response = curl_exec($curl);
curl_close($curl);
```

### Using POST Parameters

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_points.php';

$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "autocomplete" => "Prague",
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
```

## Response Formats

### JSON Format

**Requirements:**
- Include header: `Accept: application/json`
- Or pass parameter: `json=1`

**Note:** JSON format excludes `item` tags compared to XML.

```json
[
  {
    "point_id": "3",
    "point_ru_name": "Прага",
    "point_ua_name": "Прага",
    "point_latin_name": "Praha",
    "point_name": "Prague",
    "country_name": "Czech",
    "country_kod": "CZE",
    "country_id": "1",
    "point_name_detail": "Prague",
    "country_alternative_name": null,
    "priority": "20"
  }
]
```

### XML Format

Default format when JSON conditions are not met:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <item>
    <point_id>3</point_id>
    <point_ru_name>Прага</point_ru_name>
    <point_ua_name>Прага</point_ua_name>
    <point_latin_name>Praha</point_latin_name>
    <point_name>Prague</point_name>
    <country_name>Czech</country_name>
    <country_kod>CZE</country_kod>
    <country_id>1</country_id>
    <point_name_detail>Prague</point_name_detail>
    <country_alternative_name/>
    <priority>20</priority>
  </item>
</root>
```