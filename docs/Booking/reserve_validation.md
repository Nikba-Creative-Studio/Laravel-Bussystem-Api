---
layout: default
title: reserve_validation
description: Validate phone number eligibility for payment on boarding reservations
nav_order: 3
parent: Booking
---

# reserve_validation

Validate whether a phone number is eligible for booking tickets with payment on boarding and determine if SMS verification is required.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/reserve_validation.php`  
**Method:** POST  
**Type:** Validation function



## Description

This function checks if a specific phone number can be used for payment-on-boarding reservations and determines whether SMS verification is required before proceeding with ticket reservation.

### Use Case
- Check phone number eligibility before calling `reserve_ticket`
- Determine if SMS verification is needed via `sms_validation`
- Validate phone number format and carrier restrictions


## Parameters

### Authentication Parameters
- **login** (string, required): Your login credentials
- **password** (string, required): Your password

### Basic Parameters
- **v** (string, required): API version (use "1.1")
- **phone** (string, required): Phone number to validate (include country code, e.g., "+440776251258")
- **lang** (string, required): Response language [en, ru, ua, de, pl, cz]



## Example Request

```php
$url = 'https://test-api.bussystem.eu/server/curl/reserve_validation.php';

$post_data = [
  "login" => "your_login",
  "password" => "your_password",
  "v" => "1.1",
  "phone" => "+440776251258",
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

### Success Response
```json
{
  "reserve_validation": "1",
  "need_sms_validation": "1"
}
```

### Response Fields
- **reserve_validation**: Indicates if phone number can make payment-on-boarding reservations
  - `1` = Can make reservations
  - `0` = Cannot make reservations
- **need_sms_validation**: Indicates if SMS verification is required
  - `1` = SMS verification required
  - `0` = SMS verification not required

### Validation Scenarios

**Phone Approved, No SMS Required:**
```json
{
  "reserve_validation": "1",
  "need_sms_validation": "0"
}
```

**Phone Approved, SMS Required:**
```json
{
  "reserve_validation": "1",
  "need_sms_validation": "1"
}
```

**Phone Not Eligible:**
```json
{
  "reserve_validation": "0",
  "need_sms_validation": "0"
}
```


## Error Responses

### Phone Number Errors

**Missing or Invalid Phone Number:**
```json
{
  "error": "no_phone"
}
```

### Authentication Errors
```json
{
  "error": "dealer_no_activ",
  "detail": "Dealer not active"
}
```


## Workflow Integration

### Recommended Flow
1. **Before reservation**: Call `reserve_validation` with the intended phone number
2. **If SMS required**: Use `sms_validation` to verify the phone number
3. **After validation**: Proceed with `reserve_ticket` using the validated phone number

### Example Integration
```php
// Step 1: Validate phone number
$validation_response = validate_phone("+440776251258");

if ($validation_response['reserve_validation'] == '1') {
    if ($validation_response['need_sms_validation'] == '1') {
        // Step 2: SMS verification required
        $sms_result = send_sms_verification("+440776251258");
        // Wait for user to enter SMS code and verify
    }
    
    // Step 3: Proceed with reservation
    $reservation_result = reserve_ticket($order_id);
} else {
    // Phone number not eligible for payment on boarding
    echo "This phone number cannot be used for payment on boarding";
}
```


## Important Notes

### Phone Number Format
- Always include country code (e.g., "+44" for UK)
- Use international format for best compatibility
- Remove any spaces or special characters except "+" prefix

### SMS Verification Requirements
- Previously verified users may not need SMS verification
- Verification status depends on user session and agency settings
- SMS verification is typically required for new phone numbers

### Validation Timing
- Validation status may change based on carrier policies
- Check validation before each reservation attempt
- Validation does not guarantee seat availability