---
title: General Rules
description: Important guidelines and best practices for BusSystem API integration
theme: jekyll-theme-cayman
nav_order: 2
---

# General Rules

Before integrating with the BusSystem API, please carefully review these essential guidelines to ensure proper implementation and avoid common issues.

## 1. Final Price for Payment

**Important:** Only use the price from the `new_order` request for payment processing.

- Prices shown during trip search are **approximate only**
- Coach, seat, and discount information during search may differ from final booking
- Always rely on the `new_order` response for the actual payment amount

## 2. Refund Amount Calculation

**Never calculate refund amounts manually.** Always retrieve the refund amount from the `cancel_ticket` request.

- Refund conditions shown elsewhere are for **informational purposes only**
- The actual refund amount may vary based on carrier policies and timing
- Only the `cancel_ticket` response provides the accurate refund amount

## 3. API Response Reliability

**Always rely on API responses** for critical operations:

- **Ticket payments:** Only API response confirms successful purchase
- **Ticket cancellations:** Only API response confirms successful cancellation
- **Do not assume** operations are successful without API confirmation

## 4. API Timeout

- **Timeout limit:** 120 seconds
- If no server response within 120 seconds, consider the request **unsuccessful**
- Implement proper timeout handling in your application

## 5. Handling Timeouts and Network Errors

In case of timeout or network error during payment/refund operations:

**Recommended action:** Check ticket status using the appropriate status endpoint

This helps determine if the operation was actually processed despite the timeout.

## 6. Production Environment Requirements

**Forbidden in production:**
- Using names like "test", "тест", or similar test identifiers
- Test data in passenger information
- Placeholder or dummy data

Always use real, valid information in production environment.

## 7. Testing Guidelines

- Some tickets are **non-refundable** or refunded with deductions
- **Always test** ticket purchase and refund operations through the **test API only**
- Never test with real money or production endpoints

## 8. Common Error Codes

### 403 - Unauthorized IP Address
```
HTTP 403 Forbidden
```
**Cause:** You are accessing the API from an unauthorized IP address.

**Solution:** Wait until your IP address is added to the allowlist.

### dealer_no_activ - Account Issues
```json
{
  "error": "dealer_no_activ"
}
```

**Possible causes:**
- Incorrect login or password
- API access is disabled for your account
- Data was sent using a method other than **POST**

**Solutions:**
1. Verify your credentials
2. Ensure API access is enabled
3. Confirm you're using POST method for all requests

---

## Best Practices Summary

1. Use `new_order` price for payments
2. Use `cancel_ticket` for refund amounts
3. Always verify API responses
4. Handle 120-second timeouts properly
5. Check ticket status after errors
6. Use real data in production
7. Test only with test API
8. Use POST method for all requests