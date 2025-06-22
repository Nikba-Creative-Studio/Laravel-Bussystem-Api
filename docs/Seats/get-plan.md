---
layout: default
title: get_plan
description: Get detailed seat layouts and floor plans for buses and trains
nav_order: 2
parent: Seats
---

# get_plan

Get detailed seat layouts and floor plans for buses and trains to display visual seat maps.

**Endpoint:** `https://test-api.bussystem.eu/server/curl/get_plan.php`  
**Method:** POST  
**Type:** Optional function

---

## Description

This function provides detailed seat layout information for creating visual seat selection interfaces. The layout data includes seat positions, floor arrangements, and special icons for amenities.

---

## Parameters

### Authentication

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `login` | string | ✓ | Your API login |
| `password` | string | ✓ | Your API password |
| `session` | string | | Your session (recommended for additional routes) |

### Vehicle Information

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `bustype_id` | string | For buses | Bus type ID from `get_routes` or `get_free_seats` (must be > 1) |
| `vagon_type` | enum | For trains | Wagon type: `L`, `M`, `K`, `P`, `S`, `O` from `get_free_seats` |

### Layout Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `position` | enum | Seat layout orientation: `h`=horizontal, `v`=vertical |
| `v` | float | API version: `1.1` or `2.0` |

### Train Wagon Types

| Code | Type | Description |
|------|------|-------------|
| `L` | Luxe | Premium sleeper compartments |
| `M` | Soft | Soft sleeper compartments |
| `K` | Coupe | Standard compartments |
| `P` | Standard | Standard seating |
| `S` | Seat | Economy seating |
| `O` | General | General admission |

---

## Request Examples

### Bus Seat Plan

```php
$url = 'https://test-api.bussystem.eu/server/curl/get_plan.php';

$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "bustype_id" => "22",
    "position" => "h",
    "v" => 2.0
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

### Train Wagon Plan

```php
$post_data = [
    "login" => "your_login",
    "password" => "your_password",
    "vagon_type" => "K",  // Coupe wagon
    "position" => "h",
    "v" => 2.0
];
```

---

## Response Examples

### Version 2.0 Response (Recommended)

```json
{
    "plan_type": "ws15218|WyIyNzEyYzUx...OjU1fX1d",
    "floors": [
        {
            "number": 1,
            "rows": [
                {
                    "seat": ["1", "2", "", "4", "3"]
                },
                {
                    "seat": ["5", "6", "", "7", "8"]
                },
                {
                    "seat": ["29", "30", "", "", ""]
                },
                {
                    "seat": ["61", "62", "", "56", "55"]
                }
            ]
        },
        {
            "number": 2,
            "rows": [
                {
                    "seat": ["63", "64", "", "66", "65"]
                },
                {
                    "seat": ["67", "68", "", "70", "69"]
                },
                {
                    "seat": ["75", "76", "", "78", "77"]
                }
            ]
        }
    ]
}
```

### Version 1.1 Response (Legacy)

```json
{
    "plan_type": "22",
    "rows": [
        {
            "seat": ["1", "2", {"icon": "http://test.infobus/images/icon_bus/tv.png"}, "3", "4"]
        },
        {
            "seat": ["5", "6", "", "7", "8"]
        },
        {
            "seat": ["37", "38", "", "39", "40"]
        },
        {
            "seat": ["", "", "", "", ""]
        },
        {
            "seat": ["67", "68", "", "70", "69"]
        },
        {
            "seat": ["75", "76", "", "78", "77"]
        }
    ]
}
```

---

## Response Fields Reference

### Version 2.0 Structure

| Field | Type | Description |
|-------|------|-------------|
| `plan_type` | string | Plan identifier for caching |
| `floors` | array | Array of vehicle floors |
| `floors[].number` | integer | Floor number (1=lower, 2=upper) |
| `floors[].rows` | array | Array of seat rows on this floor |
| `floors[].rows[].seat` | array | Array of seats/cells in this row |

### Version 1.1 Structure

| Field | Type | Description |
|-------|------|-------------|
| `plan_type` | string | Plan identifier |
| `rows` | array | Array of all seat rows |
| `rows[].seat` | array | Array of seats/cells in this row |

### Seat Cell Types

| Content | Description |
|---------|-------------|
| `"1"`, `"2"`, etc. | Seat number |
| `""` (empty string) | Empty space (aisle, gap, etc.) |
| `{"icon": "url"}` | Special feature with icon (TV, door, driver, etc.) |

### Common Icons

| Icon | Description |
|------|-------------|
| `tv.png` | Television/entertainment |
| `door.png` | Vehicle door |
| `driver.png` | Driver area |
| `toilet.png` | Restroom |
| `stairs.png` | Stairs to upper level |

---

## Layout Interpretation

### Floor Organization
- **Single-level vehicles:** One floor in v2.0, or direct rows in v1.1
- **Double-decker buses:** Two floors in v2.0, separated by empty row in v1.1
- **Floor numbering:** 1=lower/main floor, 2=upper floor

### Row Structure
- Each row represents a cross-section of the vehicle
- Seats are positioned left to right as viewed from front
- Empty strings represent aisles or non-seat spaces
- Icons mark special features or amenities

### Seat Numbering
- Seat numbers correspond to those from `get_free_seats`
- Numbers may not be sequential (e.g., 1,2,4,3 pattern)
- Missing numbers indicate seats don't exist or are not bookable

---

## Implementation Examples

### Visual Layout Rendering

```php
// Render a floor layout
function renderFloor($floor) {
    echo "<div class='floor floor-{$floor['number']}'>";
    echo "<h3>Floor {$floor['number']}</h3>";
    
    foreach ($floor['rows'] as $rowIndex => $row) {
        echo "<div class='row row-{$rowIndex}'>";
        
        foreach ($row['seat'] as $seatIndex => $seat) {
            if (empty($seat)) {
                echo "<div class='empty-space'></div>";
            } elseif (is_array($seat) && isset($seat['icon'])) {
                echo "<div class='icon' data-icon='{$seat['icon']}'></div>";
            } else {
                echo "<div class='seat' data-seat='{$seat}'>{$seat}</div>";
            }
        }
        
        echo "</div>";
    }
    
    echo "</div>";
}
```

### Seat Availability Integration

```php
// Combine plan with availability data
function markAvailableSeats($plan, $availableSeats) {
    $availabilityMap = [];
    foreach ($availableSeats as $seat) {
        $availabilityMap[$seat['seat_number']] = $seat['seat_free'] === '1';
    }
    
    // Mark seats as available/occupied based on availabilityMap
    return $availabilityMap;
}
```

---

## Error Responses

### Dealer Not Active
```json
{
    "error": "dealer_no_activ",
    "detal": "Dealer not active"
}
```

### Seats Not Found
```json
{
    "error": "no_found"
}
```

---

## Integration Notes

### Version Differences
- **v2.0 (Recommended):** Structured floors for better multi-level support
- **v1.1 (Legacy):** Simple row structure with empty rows separating floors

### Caching Strategy
- Use `plan_type` as cache key for layout data
- Plans are typically static and can be cached long-term
- Combine cached plans with dynamic availability data

### Responsive Design
- `position` parameter affects layout orientation
- Choose `h` (horizontal) or `v` (vertical) based on display constraints
- Consider device orientation and screen size

### Accessibility
- Provide alternative text for seat selection
- Ensure keyboard navigation support
- Include seat type information from `get_free_seats`

This endpoint enables creation of interactive, visual seat selection interfaces that significantly improve the user booking experience.