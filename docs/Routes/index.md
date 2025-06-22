---
layout: default
title: Routes
nav_order: 2
has_children: true
---

# Routes

Essential endpoints for discovering, searching, and getting detailed information about transportation options across all transport types.

These endpoints work together to provide complete location and route discovery:

1.  **Location Discovery**: Use `get_points` to find cities, stations, and airports.
2.  **Route Search**: Use `get_routes` to find available transportation options.
3.  **Detail Gathering**: Use `get_all_routes` for additional details before booking.

## Available Endpoints

### get_points
Search and filter available cities, countries, stations, and airports with dynamic filtering capabilities. Essential for building location search and autocomplete functionality.

### get_routes
Search for all available routes between locations with comprehensive filtering options for dates, transport types, passenger counts, and service classes.

### get_all_routes
Get detailed route schedule information including stations, timetables, baggage options, and cancellation policies. Provides comprehensive route details when `timetable_id` is available. 