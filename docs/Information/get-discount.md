---
layout: default
title: get_discount
description: Get available discounts and pricing for specific routes
nav_order: 2
parent: Information
---

# get_discount

Get list of available discounts with pricing for specific routes.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_discount.php`  
**Method:** POST  
**Type:** Optional function


## Description

This function provides detailed discount information for routes that support it. You can only use this request if the `get_routes` response contains `request_get_discount = 1`.


## Parameters

### Authentication

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `login` | string | ✓ | Your API login |
| `password` | string | ✓ | Your API password |
| `session` | string | | Your session (recommended for additional routes) |

### Route Information

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `interval_id` | string | ✓ | Interval ID from `get_routes` response |

### Response Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `currency` | enum | Response currency: `EUR`, `RON`, `PLN`, `MDL`, `RUB`, `UAH`, `CZK` |
| `lang` | enum | Response language: `en`, `ru`, `ua`, `de`, `pl`, `cz` |


## Request Example

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_discount.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "interval_id" => "90|gh340|d29-96",
    "currency" => "EUR",
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


## Response Example

```json
[
    {
        "route_id": "16",
        "discounts": [
            {
                "discount_id": "3198",
                "discount_name": "10% Group 6+ people",
                "discount_price": "44.89",
                "currency": "EUR"
            },
            {
                "discount_id": "3199",
                "discount_name": "10% Seniors 60+ years",
                "discount_price": "44.89",
                "currency": "EUR"
            },
            {
                "discount_id": "3200",
                "discount_name": "15% Student",
                "discount_price": "42.4",
                "currency": "EUR"
            },
            {
                "discount_id": "3197",
                "discount_name": "20% Children 0-12 years",
                "discount_price": "39.9",
                "currency": "EUR"
            },
            {
                "discount_id": "3196",
                "discount_name": "50% Children 0-4 years",
                "discount_price": "24.94",
                "currency": "EUR"
            }
        ]
    }
]
```

## Response Fields Reference

### Route Information

| Field | Type | Description |
|-------|------|-------------|
| `route_id` | string | Route identifier |
| `discounts` | array | List of available discounts |

### Discount Details

| Field | Type | Description |
|-------|------|-------------|
| `discount_id` | string | **Critical:** Discount ID (required for `new_order`) |
| `discount_name` | string | Human-readable discount name |
| `discount_description` | string | Detailed discount description (may be missing) |
| `discount_price` | string | Ticket price with this discount applied |
| `currency` | string | Price currency |


## Common Discount Types

### Age-Based Discounts
- **Children 0-4 years:** Typically 50% discount
- **Children 5-12 years:** Typically 20% discount  
- **Students:** Typically 10-15% discount
- **Seniors 60+ years:** Typically 10% discount

### Group Discounts
- **Group bookings:** Often 10% for 6+ people
- **Family packages:** Special rates for families

### Special Categories
- **Military personnel:** Various percentage discounts
- **Disabled passengers:** Special accommodation pricing
- **Loyalty programs:** Member-specific discounts

## Error Responses

### Dealer Not Active
```json
{
    "error": "dealer_no_activ",
    "detal": "Dealer not active"
}
```

### Route Not Active
```json
{
    "error": "route_no_activ"
}
```

### Route Not Found
```json
{
    "error": "interval_no_found"
}
```

### No Discounts Available
```json
{
    "error": "no_discount",
    "detal": "No discounts"
}
```

## Important Notes

### Prerequisites
- Only call this function if `request_get_discount = 1` in `get_routes` response
- `interval_id` parameter is mandatory

### Booking Integration
- Use `discount_id` values in `new_order` requests
- Apply discounts per passenger as needed
- Prices shown are informational - final pricing comes from `new_order`

### Discount Application
- Discounts typically apply per passenger
- Some discounts may have quantity or age restrictions
- Group discounts may require minimum passenger counts