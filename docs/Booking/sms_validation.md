---
layout: default
title: sms_validation
description: Phone number verification via SMS code for payment on boarding reservations
nav_order: 4
parent: Booking
---

# sms_validation

Verify phone numbers via SMS code verification for users who haven't been previously authenticated or verified by user session/agency.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/sms_validation.php`  
**Method:** POST  
**Type:** Verification function

---

## Description

This two-step verification process sends an SMS with a verification code to the provided phone number, then validates the code to authorize the phone number for payment-on-boarding reservations.

### Process Flow
1. **Step 1**: Send SMS with verification code (`send_sms = 1`)
2. **Step 2**: Verify the received code (`check_sms = 1`)

---

## Parameters

### Authentication Parameters
- **sid_guest** (string, required): Session identifier for guest users

### Basic Parameters
- **v** (string, required): API version (use "1.1")
- **phone** (string, required): Phone number to verify (include country code, e.g., "+440776251258")
- **lang** (string, required): Response language [en, ru, ua, de, pl, cz]

### SMS Control Parameters
- **send_sms** (integer, optional): Send SMS verification code (use `1` for first request)
- **check_sms** (integer, optional): Verify SMS code (use `1` for second request)
- **validation_code** (string, optional): 6-digit code received via SMS (required for verification step)

---

## Example Requests

### Step 1: Send SMS Verification Code

```php
$url = 'https://test-api.bussystem.eu/server/curl/sms_validation.php';

$post_data = [
  "sid_guest" => "e6ce132c4c3c2290e3a2b28a750921ba",
  "v" => "1.1",
  "phone" => "+440776251258",
  "send_sms" => 1,
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

### Step 2: Verify SMS Code

```php
$url = 'https://test-api.bussystem.eu/server/curl/sms_validation.php';

$post_data = [
  "sid_guest" => "e6ce132c4c3c2290e3a2b28a750921ba",
  "v" => "1.1",
  "phone" => "+440776251258",
  "check_sms" => 1,
  "validation_code" => "569486",
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

---

## Response Format

### Step 1: SMS Sent Successfully
```json
{
  "validation_id": "66333",
  "phone": "440776251258",
  "validation_code": "569486",
  "status_code": "send_sms",
  "status_sms": "OK"
}
```

### Step 2: Phone Number Verified
```json
{
  "validation_id": "66333",
  "phone": "440776251258",
  "validation_code": "569486",
  "status_code": "valid"
}
```

### Response Fields
- **validation_id**: Unique identifier for this verification operation
- **phone**: Phone number being verified (without country code prefix)
- **validation_code**: 6-digit verification code
- **status_code**: Current verification status
  - `send_sms` = SMS has been sent
  - `valid` = Phone number successfully verified
- **status_sms**: SMS delivery status (only present when `send_sms = 1`)
  - `OK` = SMS successfully sent

### SMS Message Format
```
Your verification code: 569486
```

---

## Error Responses

### Authentication Errors
```json
{
  "error": "dealer_no_activ"
}
```

### Phone Number Errors

**Missing Phone Number:**
```json
{
  "error": "no_phone"
}
```

**Invalid Phone Number Format:**
```json
{
  "error": "invalid_phone"
}
```

### Request Method Errors

**Missing Request Method:**
```json
{
  "error": "method"
}
```

### Verification Code Errors

**Incorrect Verification Code:**
```json
{
  "error": "validation_code"
}
```

**Code Not Generated:**
```json
{
  "error": "generate_code"
}
```

**Code Not Found:**
```json
{
  "error": "check_generate_code"
}
```

**Verification Failed:**
```json
{
  "error": "check_valid"
}
```

### SMS Delivery Errors

**SMS Not Sent:**
```json
{
  "error": "send_sms"
}
```

**SMS Rate Limit Exceeded:**
```json
{
  "error": "sends_limit",
  "detail": "Resending SMS via 35 sec."
}
```

**Verification Code Expired:**
```json
{
  "error": "expired"
}
```

---

## Complete Workflow Example

```php
function verify_phone_number($phone, $sid_guest) {
    // Step 1: Send SMS
    $sms_response = send_sms_verification($phone, $sid_guest);
    
    if (isset($sms_response['error'])) {
        return ['error' => 'Failed to send SMS: ' . $sms_response['error']];
    }
    
    if ($sms_response['status_code'] === 'send_sms' && $sms_response['status_sms'] === 'OK') {
        echo "SMS sent successfully to " . $phone . "\n";
        echo "Please enter the 6-digit code you received: ";
        
        // Wait for user input (in real implementation, this would be a form submission)
        $user_code = trim(fgets(STDIN));
        
        // Step 2: Verify code
        $verify_response = verify_sms_code($phone, $sid_guest, $user_code);
        
        if ($verify_response['status_code'] === 'valid') {
            return ['success' => true, 'message' => 'Phone number verified successfully'];
        } else {
            return ['error' => 'Verification failed'];
        }
    }
    
    return ['error' => 'SMS sending failed'];
}

function send_sms_verification($phone, $sid_guest) {
    $post_data = [
        "sid_guest" => $sid_guest,
        "v" => "1.1",
        "phone" => $phone,
        "send_sms" => 1,
        "lang" => "en"
    ];
    
    return make_api_call($post_data);
}

function verify_sms_code($phone, $sid_guest, $code) {
    $post_data = [
        "sid_guest" => $sid_guest,
        "v" => "1.1",
        "phone" => $phone,
        "check_sms" => 1,
        "validation_code" => $code,
        "lang" => "en"
    ];
    
    return make_api_call($post_data);
}
```

---

## Important Notes

### Rate Limiting
- SMS sending is rate-limited to prevent abuse
- Wait for the specified time before requesting another SMS
- Typical rate limit: one SMS per 35 seconds

### Code Expiration
- Verification codes have a limited validity period
- Request a new code if the current one has expired
- Complete verification promptly after receiving the SMS

### Session Management
- Use consistent `sid_guest` for both SMS sending and verification
- Session identifiers should be unique per verification attempt

### Phone Number Format
- Always include country code (e.g., "+44" for UK)
- Remove spaces and special characters except the "+" prefix
- Ensure phone number is valid and can receive SMS messages

### Integration with Booking Flow
This endpoint is typically used between `reserve_validation` and `reserve_ticket`:
1. `reserve_validation` → Check if SMS verification is needed
2. `sms_validation` → Verify phone number if required
3. `reserve_ticket` → Complete reservation with verified phone