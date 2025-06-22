# Changelog

All notable changes to `laravel-bussystem-api` will be documented in this file.

## [1.0.0] - 2025-01-XX

### Added
- Initial release of Laravel BusSystem API package
- Complete BusSystem API integration with all endpoints
- Multi-transport support (bus, train, air)
- Comprehensive booking workflow implementation
- Data Transfer Objects for SearchCriteria and BookingData
- Eloquent models for Order and Ticket management
- Database migrations for order and ticket tables
- Caching support for API responses
- Comprehensive error handling with custom exceptions
- Laravel service provider and facade integration
- Extensive test suite with unit and integration tests
- Configuration management with environment variables
- Logging support for API requests and responses
- Documentation with examples and use cases

### Features
- **Route Search**: Search for transportation routes across all transport types
- **Seat Management**: Detailed seat selection and layout visualization
- **Booking System**: Complete booking workflow from search to payment
- **Payment Processing**: Support for online payments and payment on boarding
- **Order Management**: Track and manage orders and tickets
- **SMS Validation**: Phone number verification for payment on boarding
- **Cancellation System**: Ticket and order cancellation with refund calculations
- **Multi-language Support**: API responses in multiple languages
- **Multi-currency Support**: Pricing in various currencies
- **Caching**: Performance optimization through response caching
- **Database Integration**: Eloquent models for data persistence
- **Error Handling**: Comprehensive exception handling
- **Testing**: Full test coverage with mocking capabilities

### Endpoints Implemented
- `get_points` - Search cities, stations, and airports
- `get_routes` - Search available routes
- `get_all_routes` - Get detailed route information
- `get_free_seats` - Get available seats and wagons
- `get_plan` - Get seat layout plans
- `get_discount` - Get available discounts
- `get_baggage` - Get baggage options
- `new_order` - Create new booking orders
- `buy_ticket` - Complete ticket purchases
- `cancel_ticket` - Cancel tickets and process refunds
- `get_order` - Get order information
- `get_ticket` - Get ticket details
- `reserve_ticket` - Reserve tickets for payment on boarding
- `reserve_validation` - Validate phone numbers for reservations
- `sms_validation` - SMS verification for phone numbers
- `ping` - Check API server status

### Configuration Options
- API URL configuration for test/production environments
- Authentication credentials management
- Default currency and language settings
- Caching configuration with TTL settings
- Logging configuration with channel selection
- Request timeout and retry settings
- Response format preferences

### Database Schema
- `bussystem_orders` table for order management
- `bussystem_tickets` table for ticket tracking
- Comprehensive indexing for performance
- Soft deletes support
- JSON columns for API response storage

### Developer Experience
- Fluent API design with method chaining
- Strong typing with PHP 8.1+ features
- PSR-12 coding standards compliance
- Comprehensive PHPDoc documentation
- IDE autocompletion support
- Easy testing with mock capabilities