---
layout: default
title: Route Info
nav_order: 6
has_children: true
permalink: /route-info/
---

# Route Info

Detailed information endpoints for specific routes, providing comprehensive data needed before booking.

## Available Endpoints

### get_all_routes
Get detailed route schedule information including stations, timetables, baggage options, and cancellation policies. Provides comprehensive route details when `timetable_id` is available.

### get_baggage
Get detailed baggage options with dimensions, weight limits, and pricing for routes that support advanced baggage selection. Only available when `request_get_baggage = 1`.

### get_discount
Get available discounts and pricing for specific routes. Provides discount options for different passenger categories when `request_get_discount = 1`.

---

These endpoints provide additional route details that enhance the booking experience:

1. **Route Schedules**: Complete station information and timing details
2. **Baggage Planning**: Detailed baggage options with specifications and pricing  
3. **Discount Discovery**: Available discounts for different passenger types

Use these endpoints when the corresponding request flags are set to `1` in the `get_routes` response to provide passengers with comprehensive route information before booking.