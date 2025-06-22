---
layout: default
title: Booking
nav_order: 7
has_children: true
permalink: /booking/
---

# Booking

Order creation and reservation management endpoints for securing tickets and managing payment options before final purchase.

The booking process allows you to create temporary reservations, validate payment methods, and secure seats across multiple transportation types (bus, train, air) with flexible payment options including payment on boarding.

---

## Booking Workflow

### Standard Online Payment Flow
1. **new_order** → Create initial order with passenger details and seat selection
2. **Payment Processing** → Complete payment through standard payment gateway
3. **Ticket Issuance** → Receive confirmed tickets

### Payment on Boarding Flow
1. **new_order** → Create initial order with passenger details
2. **reserve_validation** → Check if phone number is eligible for payment on boarding
3. **sms_validation** → Verify phone number via SMS if required
4. **reserve_ticket** → Create reservation for payment during boarding

---

## Available Endpoints

### new_order
Create a new booking order with temporary seat reservations for up to 10 routes and 10 passengers. Orders are automatically canceled after the lock time (typically 20 minutes) unless payment is completed. This is a mandatory function that must be used after `get_routes` for external flights.

**Key Features:**
- Automatic seat blocking for specified time periods
- Support for complex multi-route bookings with transfers
- Flexible passenger data requirements based on route needs
- Comprehensive baggage and discount options

### reserve_ticket
Create ticket reservations with payment on boarding for carriers and routes that support this payment method. This allows passengers to pay directly to the driver/conductor during boarding instead of online payment.

**Key Features:**
- Payment collected during boarding
- Requires phone number verification
- Not available for all routes/carriers
- Generates ticket with security code for boarding

### reserve_validation
Validate whether a phone number is eligible for booking tickets with payment on boarding. This endpoint checks carrier restrictions and determines if SMS verification is required before proceeding.

**Key Features:**
- Pre-validation before attempting reservation
- Checks phone number eligibility
- Determines SMS verification requirements
- Prevents failed reservation attempts

### sms_validation
Two-step phone number verification via SMS code for users who haven't been previously authenticated. Required for payment-on-boarding reservations when phone number verification is needed.

**Key Features:**
- Secure SMS-based verification
- Rate-limited to prevent abuse
- Session-based verification tracking
- Time-limited verification codes

---

## Common Use Cases

### Multi-Route Family Booking
Book connected journeys for multiple family members across different transportation types:
```
Route 1: Bus (Prague → Vienna)
Route 2: Train (Vienna → Budapest)  
Route 3: Flight (Budapest → London)
```

### Group Reservations with Payment on Boarding
Secure seats for group travel with payment flexibility:
1. Validate group contact phone number
2. Create reservations for entire group
3. Pay cash during boarding

### International Travel with Document Requirements
Handle complex international bookings with full passenger documentation:
- Passport details and expiration dates
- Citizenship verification
- Age-appropriate document types
- Visa and travel restriction compliance

---

## Authentication & Sessions

All booking endpoints require proper authentication:
- **Standard API Access**: Use `login` and `password` parameters
- **Guest Sessions**: Use `sid_guest` for SMS verification (anonymous users)
- **Partner Integration**: Include `partner` ID for affiliate tracking

---

## Error Handling

### Common Error Categories
- **Authentication Errors**: Invalid credentials or inactive dealer accounts
- **Validation Errors**: Missing required passenger data or invalid formats
- **Availability Errors**: Seats no longer available or routes inactive
- **Payment Errors**: Payment method not supported or verification failed

### Error Response Format
All endpoints return errors in consistent JSON format:
```json
{
  "error": "error_code",
  "detail": "Human-readable error description"
}
```

### Recommended Error Handling Strategy
1. **Validate inputs** before making API calls
2. **Check route availability** using `get_routes` before booking
3. **Handle temporary failures** with appropriate retry logic
4. **Provide clear user feedback** for validation errors

---

## Best Practices

### Performance Optimization
- Use session tokens to reduce authentication overhead
- Batch multiple passengers in single `new_order` requests
- Pre-validate phone numbers before attempting reservations

### User Experience
- Always check route requirements (`need_orderdata`, `need_doc`, etc.) before presenting booking forms
- Provide real-time seat availability updates
- Implement proper timeout handling for reservation periods

### Security
- Never store sensitive passenger data longer than necessary
- Use secure connections (HTTPS) for all API communication
- Implement proper rate limiting for SMS verification

### International Considerations
- Validate document formats for destination countries
- Handle multiple currencies and exchange rates
- Account for timezone differences in departure/arrival times
- Ensure proper character encoding for international names