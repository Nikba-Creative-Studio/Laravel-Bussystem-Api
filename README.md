# Laravel BusSystem API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nikba/laravel-bussystem-api.svg?style=flat-square)](https://packagist.org/packages/nikba/laravel-bussystem-api)
[![Total Downloads](https://img.shields.io/packagist/dt/nikba/laravel-bussystem-api.svg?style=flat-square)](https://packagist.org/packages/nikba/laravel-bussystem-api)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/nikba-creative-studio/laravel-bussystem-api/run-tests?label=tests)](https://github.com/nikba-creative-studio/laravel-bussystem-api/actions?query=workflow%3Arun-tests+branch%3Amain)

Laravel package providing seamless integration with BusSystem transportation services. This comprehensive SDK enables easy integration with BusSystem's passenger transportation API for bus, train, and air travel booking.

## Features

- 🚌 **Multi-Transport Support** - Bus, train, and air travel integration
- 🎫 **Complete Booking Flow** - Search, book, pay, and manage tickets
- 💺 **Seat Management** - Detailed seat selection and layout support
- 💳 **Payment Processing** - Secure payment handling and order management
- 📱 **Mobile-Friendly** - Payment on boarding and SMS validation
- 🏗️ **Laravel Integration** - Native Laravel service provider and facades
- 🔄 **Caching Support** - Built-in caching for improved performance
- 📊 **Database Models** - Eloquent models for order and ticket management
- 🧪 **Testing Support** - Comprehensive test suite and mocking capabilities

## Installation

You can install the package via composer:

```bash
composer require nikba/laravel-bussystem-api
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Nikba\LaravelBussystemApi\BusSystemServiceProvider" --tag="bussystem-config"
```

Optionally, publish the database migrations:

```bash
php artisan vendor:publish --provider="Nikba\LaravelBussystemApi\BusSystemServiceProvider" --tag="bussystem-migrations"
php artisan migrate
```

## Configuration

Add your BusSystem API credentials to your `.env` file:

```env
BUSSYSTEM_API_URL=https://test-api.bussystem.eu/server
BUSSYSTEM_LOGIN=your_login
BUSSYSTEM_PASSWORD=your_password
BUSSYSTEM_PARTNER_ID=your_partner_id
BUSSYSTEM_DEFAULT_CURRENCY=EUR
BUSSYSTEM_DEFAULT_LANGUAGE=en
```

## Basic Usage

### Search for Routes

```php
use Nikba\LaravelBussystemApi\Data\SearchCriteria;
use Nikba\LaravelBussystemApi\Facades\BusSystem;

// Create search criteria
$criteria = SearchCriteria::create()
    ->date('2024-12-31')
    ->from(3) // Prague
    ->to(7)   // Vienna
    ->bus()
    ->currency('EUR')
    ->language('en');

// Search for routes
$routes = BusSystem::getRoutes($criteria);
```

### Create a Booking

```php
use Nikba\LaravelBussystemApi\Data\BookingData;

// Create booking data
$booking = BookingData::create('EUR', 'en')
    ->addRoute('2024-12-31', 'interval_id_from_search')
    ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
    ->addPassenger('Jane', 'Doe', '1992-02-02', 1, 'CD789012', 'F')
    ->addSeats(0, ['1', '2'])
    ->setContactInfo('+1234567890', 'john@example.com');

// Validate booking data
$errors = $booking->validate();
if (!empty($errors)) {
    // Handle validation errors
    foreach ($errors as $error) {
        echo $error . "\n";
    }
    return;
}

// Create order
$order = BusSystem::createOrder($booking);
echo "Order ID: " . $order['order_id'];
echo "Reservation until: " . $order['reservation_until'];
```

### Complete Payment

```php
// Buy tickets for the order
$orderId = $order['order_id'];
$tickets = BusSystem::buyTickets($orderId, 'en');

// Access ticket information
foreach ($tickets['item'] as $ticket) {
    echo "Ticket ID: " . $ticket['ticket_id'];
    echo "PDF Link: " . $ticket['link'];
}
```

### Using Dependency Injection

```php
use Nikba\LaravelBussystemApi\Contracts\BusSystemClientInterface;

class BookingController extends Controller
{
    public function __construct(
        private BusSystemClientInterface $busSystem
    ) {}

    public function searchRoutes(Request $request)
    {
        $criteria = SearchCriteria::create()
            ->date($request->date)
            ->from($request->from_city_id)
            ->to($request->to_city_id)
            ->transport($request->transport)
            ->currency($request->currency ?? 'EUR');

        return $this->busSystem->getRoutes($criteria);
    }
}
```

## Advanced Usage

### Air Travel Booking

```php
// Search for flights
$criteria = SearchCriteria::create()
    ->date('2024-12-31')
    ->airportFrom('PRG') // Prague Airport
    ->airportTo('VIE')   // Vienna Airport
    ->air()
    ->airPassengers(2, 1, 0) // 2 adults, 1 child, 0 infants
    ->airServiceClass('E')    // Economy
    ->airDirect(false);       // Allow connections

$flights = BusSystem::getRoutes($criteria);

// Create flight booking
$booking = BookingData::create()
    ->addRoute('2024-12-31', $flights[0]['interval_id'])
    ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M', null, 'US', '2030-01-01')
    ->addPassenger('Jane', 'Doe', '1992-02-02', 1, 'CD789012', 'F', null, 'US', '2030-02-02')
    ->addPassenger('Johnny', 'Doe', '2020-03-03', 3, 'EF345678', 'M') // Child
    ->addSeats(0, ['adt', 'adt', 'chd']) // Adult, Adult, Child
    ->setContactInfo('+1234567890', 'john@example.com');
```

### Train Booking with Wagon Selection

```php
// Search for train routes
$criteria = SearchCriteria::create()
    ->date('2024-12-31')
    ->trainFrom(2200001) // Kyiv train station
    ->trainTo(5400076)   // Prague train station
    ->train();

$routes = BusSystem::getRoutes($criteria);
$route = $routes[0];

// Get available wagons
$wagons = BusSystem::getFreeSeats($route['interval_id'], [
    'train_id' => $route['train_id'][0]
]);

// Get seats in selected wagon
$wagonId = $wagons[0]['vagon']['item'][0]['vagon_id'];
$seats = BusSystem::getFreeSeats($route['interval_id'], [
    'vagon_id' => $wagonId
]);

// Create booking with wagon and seat selection
$booking = BookingData::create()
    ->addRoute('2024-12-31', $route['interval_id'])
    ->addWagon(0, $wagonId)
    ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
    ->addSeats(0, ['1']) // Seat number from seats response
    ->setContactInfo('+1234567890', 'john@example.com');
```

### Payment on Boarding

```php
// Validate phone number for payment on boarding
$validation = BusSystem::validateReservation('+1234567890', 'en');

if ($validation['reserve_validation'] === '1') {
    if ($validation['need_sms_validation'] === '1') {
        // Send SMS verification
        $smsResult = BusSystem::validateSms([
            'sid_guest' => session()->getId(),
            'phone' => '+1234567890',
            'send_sms' => 1,
            'lang' => 'en'
        ]);
        
        // User enters SMS code, then verify
        $verification = BusSystem::validateSms([
            'sid_guest' => session()->getId(),
            'phone' => '+1234567890',
            'check_sms' => 1,
            'validation_code' => $request->sms_code,
            'lang' => 'en'
        ]);
    }
    
    // Reserve tickets for payment on boarding
    $reservation = BusSystem::reserveTickets($orderId, [
        'phone' => '+1234567890',
        'email' => 'john@example.com',
        'lang' => 'en'
    ]);
}
```

### Working with Seat Plans

```php
// Get seat layout for visual selection
$seatPlan = BusSystem::getSeatPlan([
    'bustype_id' => $route['bustype_id'],
    'position' => 'h', // horizontal layout
    'v' => '2.0'
]);

// Process seat plan for frontend
foreach ($seatPlan['floors'] as $floor) {
    foreach ($floor['rows'] as $row) {
        foreach ($row['seat'] as $seat) {
            if (is_string($seat) && !empty($seat)) {
                echo "Seat: " . $seat . "\n";
            } elseif (is_array($seat) && isset($seat['icon'])) {
                echo "Icon: " . $seat['icon'] . "\n";
            }
        }
    }
}
```

### Order Management

```php
// Get order details
$orderDetails = BusSystem::getOrder($orderId, $securityCode, 'en');

// Get specific ticket information
$ticketInfo = BusSystem::getTicket([
    'ticket_id' => $ticketId,
    'security' => $ticketSecurityCode,
    'lang' => 'en'
]);

// Cancel tickets
$cancellation = BusSystem::cancelTickets([
    'order_id' => $orderId,
    'security' => $securityCode,
    'lang' => 'en',
    'v' => '1.1'
]);

echo "Refund amount: " . $cancellation['money_back_total'];
```

## Database Models

The package includes Eloquent models for storing booking data:

### Order Model

```php
use Nikba\LaravelBussystemApi\Models\Order;

// Create order record
$order = Order::create([
    'order_id' => $apiResponse['order_id'],
    'security_code' => $apiResponse['security'],
    'status' => $apiResponse['status'],
    'price_total' => $apiResponse['price_total'],
    'currency' => 'EUR',
    'passenger_count' => 2,
    'route_count' => 1,
    'phone' => '+1234567890',
    'email' => 'john@example.com',
    'reservation_until' => $apiResponse['reservation_until'],
    'api_response' => $apiResponse,
    'user_id' => auth()->id(),
]);

// Query orders
$userOrders = Order::forUser(auth()->id())->active()->get();
$expiredOrders = Order::expired()->get();
$paidOrders = Order::paid()->get();
```

### Ticket Model

```php
use Nikba\LaravelBussystemApi\Models\Ticket;

// Create ticket records
foreach ($ticketsResponse['item'] as $ticketData) {
    Ticket::create([
        'order_id' => $order->id,
        'ticket_id' => $ticketData['ticket_id'],
        'transaction_id' => $ticketData['transaction_id'],
        'security_code' => $ticketData['security'],
        'passenger_name' => 'John',
        'passenger_surname' => 'Doe',
        'price' => $ticketData['price'],
        'currency' => 'EUR',
        'status' => 'buy',
        'pdf_link' => $ticketData['link'],
        'api_response' => $ticketData,
    ]);
}

// Query tickets
$activeTickets = Ticket::active()->get();
$upcomingTrips = Ticket::departingAfter(now())->get();
```

## Error Handling

The package provides specific exception types for different error scenarios:

```php
use Nikba\LaravelBussystemApi\Exceptions\BusSystemAuthenticationException;
use Nikba\LaravelBussystemApi\Exceptions\BusSystemValidationException;
use Nikba\LaravelBussystemApi\Exceptions\BusSystemApiException;

try {
    $routes = BusSystem::getRoutes($criteria);
} catch (BusSystemAuthenticationException $e) {
    // Handle authentication errors (invalid credentials)
    Log::error('BusSystem authentication failed: ' . $e->getMessage());
} catch (BusSystemValidationException $e) {
    // Handle validation errors (missing required data)
    return response()->json(['error' => $e->getMessage()], 422);
} catch (BusSystemApiException $e) {
    // Handle general API errors
    Log::error('BusSystem API error: ' . $e->getMessage());
    return response()->json(['error' => 'Service temporarily unavailable'], 503);
}
```

## Caching

The package supports automatic caching of API responses to improve performance:

```php
// Caching is configured in config/bussystem.php
'cache' => [
    'enabled' => env('BUSSYSTEM_CACHE_ENABLED', true),
    'prefix' => env('BUSSYSTEM_CACHE_PREFIX', 'bussystem'),
    'ttl' => [
        'points' => env('BUSSYSTEM_CACHE_POINTS_TTL', 3600), // 1 hour
        'routes' => env('BUSSYSTEM_CACHE_ROUTES_TTL', 300),  // 5 minutes
        'plans' => env('BUSSYSTEM_CACHE_PLANS_TTL', 86400),  // 24 hours
    ],
],
```

## Testing

Run the tests with:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nicolai Bargan](https://www.linkedin.com/in/nicolai-bargan/)
- [Nikba Creative Studio](https://github.com/nikba-creative-studio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## API Documentation

For complete API documentation, visit: [BusSystem API Documentation](https://nikba-creative-studio.github.io/Laravel-Bussystem-Api/)

## Support

- **Documentation Issues**: [GitHub Issues](https://github.com/Nikba-Creative-Studio/Laravel-Bussystem-Api/issues)
- **API Support**: Contact BusSystem support
- **Package Support**: [GitHub Discussions](https://github.com/Nikba-Creative-Studio/Laravel-Bussystem-Api/discussions)