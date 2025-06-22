---
layout: default
title: Route Search
nav_order: 5
has_children: true
permalink: /route-search/
---

# Route Search

Essential endpoints for discovering and searching transportation options across all transport types.

## Available Endpoints

### get_points
Search and filter available cities, countries, stations, and airports with dynamic filtering capabilities. Essential for building location search and autocomplete functionality.

### get_routes
Search for all available routes between locations with comprehensive filtering options for dates, transport types, passenger counts, and service classes.

---

These endpoints work together to provide complete location and route discovery:

1. **Location Discovery**: Use `get_points` to find cities, stations, and airports
2. **Route Search**: Use `get_routes` to find available transportation options
3. **Detail Gathering**: Use route info endpoints for additional details before booking

Both endpoints support multiple transport types (bus, train, air) and provide rich filtering options for building sophisticated search interfaces.