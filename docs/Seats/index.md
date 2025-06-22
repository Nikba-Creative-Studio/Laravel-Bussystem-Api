---
layout: default
title: Seats
nav_order: 6
has_children: true
---

# Seats

Seat availability and layout information for buses and trains, enabling detailed seat selection and planning.

## Available Endpoints

### get_free_seats
Search for available seat numbers for buses and trains, including wagon selection for train travel. Essential for routes requiring specific seat assignment.

[Read more about `get_free_seats`](get-free-seats.md)

### get_plan
Get detailed seat layout and floor plans for vehicles, providing visual seat maps for user-friendly seat selection interfaces.

[Read more about `get_plan`](get-plan.md)

---

These endpoints work together to provide comprehensive seat management:

1. **Seat Availability**: Use `get_free_seats` to find available seats and wagons
2. **Visual Layout**: Use `get_plan` to display seat maps and layouts
3. **Selection Process**: Combine both for intuitive seat selection interfaces

Both endpoints are only available when `request_get_free_seats = 1` in the `get_routes` response, indicating that the route supports detailed seat selection. 