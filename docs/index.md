---
layout: default
title: Home
nav_order: 1
description: "Laravel package providing seamless integration with BusSystem transportation services"
permalink: /
---

# BusSystem Laravel API Documentation

Laravel package providing seamless integration with BusSystem transportation services. This comprehensive SDK enables seamless integration with BusSystem's passenger transportation services.

{: .fs-6 .fw-300 }

## Quick Start

Get up and running with BusSystem API in minutes.

[Get Started Now](#getting-started){: .btn .btn-primary .fs-5 .mb-4 .mb-md-0 .mr-2 }
[View on GitHub](https://github.com/Nikba-Creative-Studio/Laravel-Bussystem-Api){: .btn .fs-5 .mb-4 .mb-md-0 }

---

## Features

**Comprehensive API Coverage**
{: .label .label-green }

- Trip search and booking
- Seat selection and management
- Payment processing
- Ticket management and cancellation
- Real-time status updates

**Laravel Integration**
{: .label .label-blue }

- Service provider integration
- Configuration management  
- Eloquent model support
- Exception handling
- Comprehensive testing

**Multi-Transport Support**
{: .label .label-purple }

- Bus transportation
- Railway services
- Air travel integration
- Multi-modal trip planning

---

## Integration Types

### For Agencies
Complete API access for agencies that handle payments and passenger services.
- Full booking and payment capabilities
- Passenger management
- Commission handling

### For Affiliate Partners  
Search and redirect functionality for partners who don't handle payments.
- Trip search integration
- Deep linking support
- Conversion tracking

### For Carriers and Dispatchers
Management tools for transportation providers.
- Ticket list management
- Route administration
- Dispatcher tools

---

## Getting Started

### Installation

Install the package via Composer:

```bash
composer require nikba/laravel-bussystem-api
```

### Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Nikba\LaravelBussystemApi\BusSystemServiceProvider"
```

### Basic Usage

```php
use Nikba\LaravelBussystemApi\BusSystemClient;

$client = new BusSystemClient();
$trips = $client->searchTrips([
    'from' => 'Prague',
    'to' => 'Vienna',
    'date' => '2025-07-15'
]);
```

---

## Support

**Documentation Issues**
{: .label .label-yellow }

Found an error in the documentation? [Open an issue](https://github.com/Nikba-Creative-Studio/Laravel-Bussystem-Api/issues) on GitHub.

**API Support**
{: .label .label-red }

For API-related questions, contact BusSystem support or check the [API Status](https://test-api.bussystem.eu/server/curl/ping.php) page.

---

## About

**Developer:** Nicolai Bargan  
**Company:** Nikba Creative Studio  
**License:** MIT  
**Version:** 1.0.0