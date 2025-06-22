<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Tests\Feature;

use Nikba\LaravelBussystemApi\Contracts\BusSystemClientInterface;
use Nikba\LaravelBussystemApi\Data\SearchCriteria;
use Nikba\LaravelBussystemApi\Data\BookingData;
use Nikba\LaravelBussystemApi\Facades\BusSystem;
use Nikba\LaravelBussystemApi\Tests\TestCase;

class BusSystemIntegrationTest extends TestCase
{
    public function test_service_provider_registers_client(): void
    {
        $client = $this->app->make(BusSystemClientInterface::class);
        
        $this->assertInstanceOf(BusSystemClientInterface::class, $client);
    }

    public function test_facade_resolves_correctly(): void
    {
        $this->assertTrue(class_exists(\Nikba\LaravelBussystemApi\Facades\BusSystem::class));
        
        // Test that facade accessor is properly configured
        $facadeRoot = BusSystem::getFacadeRoot();
        $this->assertInstanceOf(BusSystemClientInterface::class, $facadeRoot);
    }

    public function test_config_is_published_correctly(): void
    {
        $this->assertEquals('https://test-api.bussystem.eu/server', config('bussystem.api_url'));
        $this->assertEquals('test_login', config('bussystem.login'));
        $this->assertEquals('test_password', config('bussystem.password'));
        $this->assertEquals('test_partner', config('bussystem.partner_id'));
        $this->assertFalse(config('bussystem.cache.enabled'));
        $this->assertFalse(config('bussystem.logging.enabled'));
    }

    public function test_search_criteria_builder_works_end_to_end(): void
    {
        $criteria = SearchCriteria::create()
            ->date('2024-12-31')
            ->from(3)
            ->to(7)
            ->bus()
            ->currency('EUR')
            ->language('en')
            ->sortByPrice()
            ->period(3)
            ->includeSoldOut(true);

        $array = $criteria->toArray();

        // Verify all parameters are correctly set
        $this->assertEquals('2024-12-31', $array['date']);
        $this->assertEquals(3, $array['id_from']);
        $this->assertEquals(7, $array['id_to']);
        $this->assertEquals('bus', $array['trans']);
        $this->assertEquals('EUR', $array['currency']);
        $this->assertEquals('en', $array['lang']);
        $this->assertEquals('price', $array['sort_type']);
        $this->assertEquals(3, $array['period']);
        $this->assertEquals(1, $array['get_all_departure']);
    }

    public function test_booking_data_builder_works_end_to_end(): void
    {
        $booking = BookingData::create('EUR', 'en')
            ->addRoute('2024-12-31', 'test_interval_123')
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->addPassenger('Jane', 'Doe', '1992-02-02', 1, 'CD789012', 'F')
            ->addSeats(0, ['1', '2'])
            ->setContactInfo('+1234567890', 'test@example.com')
            ->setPromocode('SAVE20');

        // Validate booking data
        $errors = $booking->validate();
        $this->assertEmpty($errors, 'Booking validation should pass with valid data');

        // Check array output
        $array = $booking->toArray();
        $this->assertEquals(['2024-12-31'], $array['date']);
        $this->assertEquals(['test_interval_123'], $array['interval_id']);
        $this->assertEquals(['John', 'Jane'], $array['name']);
        $this->assertEquals(['Doe', 'Doe'], $array['surname']);
        $this->assertEquals('+1234567890', $array['phone']);
        $this->assertEquals('test@example.com', $array['email']);
        $this->assertEquals('SAVE20', $array['promocode_name']);
        $this->assertEquals([0 => ['1', '2']], $array['seat']);
    }

    public function test_client_can_be_resolved_from_container(): void
    {
        // Test dependency injection
        $client1 = app(BusSystemClientInterface::class);
        $client2 = app(BusSystemClientInterface::class);
        
        // Should be singleton
        $this->assertSame($client1, $client2);
    }

    public function test_configuration_values_are_accessible(): void
    {
        // Test all configuration keys exist
        $this->assertIsString(config('bussystem.api_url'));
        $this->assertIsString(config('bussystem.login'));
        $this->assertIsString(config('bussystem.password'));
        $this->assertIsInt(config('bussystem.timeout'));
        $this->assertIsString(config('bussystem.default_currency'));
        $this->assertIsString(config('bussystem.default_language'));
        $this->assertIsString(config('bussystem.default_api_version'));
        $this->assertIsArray(config('bussystem.cache'));
        $this->assertIsArray(config('bussystem.logging'));
    }

    public function test_models_can_be_instantiated(): void
    {
        // Test that models can be created (without database operations in unit tests)
        $orderClass = \Nikba\LaravelBussystemApi\Models\Order::class;
        $ticketClass = \Nikba\LaravelBussystemApi\Models\Ticket::class;
        
        $this->assertTrue(class_exists($orderClass));
        $this->assertTrue(class_exists($ticketClass));
        
        // Test that they extend Eloquent Model
        $this->assertTrue(is_subclass_of($orderClass, \Illuminate\Database\Eloquent\Model::class));
        $this->assertTrue(is_subclass_of($ticketClass, \Illuminate\Database\Eloquent\Model::class));
    }

    public function test_exceptions_can_be_instantiated(): void
    {
        $baseException = new \Nikba\LaravelBussystemApi\Exceptions\BusSystemApiException('Test message');
        $authException = new \Nikba\LaravelBussystemApi\Exceptions\BusSystemAuthenticationException('Auth failed');
        $validationException = new \Nikba\LaravelBussystemApi\Exceptions\BusSystemValidationException('Validation failed');
        
        $this->assertInstanceOf(\Exception::class, $baseException);
        $this->assertInstanceOf(\Exception::class, $authException);
        $this->assertInstanceOf(\Exception::class, $validationException);
        
        $this->assertEquals('Test message', $baseException->getMessage());
        $this->assertEquals('Auth failed', $authException->getMessage());
        $this->assertEquals('Validation failed', $validationException->getMessage());
    }

    public function test_package_version_is_accessible(): void
    {
        // Test that the package is properly loaded
        $providers = $this->app->getLoadedProviders();
        $this->assertArrayHasKey(\Nikba\LaravelBussystemApi\BusSystemServiceProvider::class, $providers);
    }
}