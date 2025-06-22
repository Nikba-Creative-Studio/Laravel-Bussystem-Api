<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Nikba\LaravelBussystemApi\Data\SearchCriteria;
use Nikba\LaravelBussystemApi\Services\BusSystemClient;
use Nikba\LaravelBussystemApi\Tests\TestCase;
use ReflectionClass;

class BusSystemClientTest extends TestCase
{
    private BusSystemClient $client;
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        
        $this->client = new BusSystemClient(
            'https://test-api.bussystem.eu/server',
            'test_login',
            'test_password',
            'test_partner'
        );

        // Inject mock HTTP client
        $reflection = new ReflectionClass($this->client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        $httpClientProperty->setValue($this->client, new Client(['handler' => $handlerStack]));
    }

    public function test_ping_returns_success_response(): void
    {
        $this->mockHandler->append(
            new Response(200, [], json_encode(['status' => 'ok']))
        );

        $response = $this->client->ping();

        $this->assertEquals(['status' => 'ok'], $response);
    }

    public function test_get_points_with_autocomplete(): void
    {
        $mockResponse = [
            [
                'point_id' => '3',
                'point_name' => 'Prague',
                'country_name' => 'Czech',
                'currency' => 'CZK'
            ]
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($mockResponse))
        );

        $response = $this->client->getPoints([
            'autocomplete' => 'Prague',
            'lang' => 'en'
        ]);

        $this->assertEquals($mockResponse, $response);
    }

    public function test_get_routes_with_search_criteria(): void
    {
        $mockResponse = [
            [
                'interval_id' => 'test_interval_123',
                'route_name' => 'Prague - Vienna',
                'price' => 45.50,
                'currency' => 'EUR',
                'free_seats' => 15
            ]
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($mockResponse))
        );

        $criteria = SearchCriteria::create()
            ->date('2024-12-31')
            ->from(3) // Prague
            ->to(7)   // Vienna
            ->bus()
            ->currency('EUR')
            ->language('en');

        $response = $this->client->getRoutes($criteria);

        $this->assertEquals($mockResponse, $response);
    }

    public function test_get_free_seats_returns_seat_information(): void
    {
        $mockResponse = [
            [
                'bustype_id' => '12',
                'has_plan' => '1',
                'free_seat' => [
                    [
                        'seat_number' => '1',
                        'seat_free' => '1',
                        'seat_price' => '45.50',
                        'seat_curency' => 'EUR'
                    ],
                    [
                        'seat_number' => '2',
                        'seat_free' => '1',
                        'seat_price' => '45.50',
                        'seat_curency' => 'EUR'
                    ]
                ]
            ]
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($mockResponse))
        );

        $response = $this->client->getFreeSeats('test_interval_123', [
            'currency' => 'EUR'
        ]);

        $this->assertEquals($mockResponse, $response);
    }

    public function test_create_order_with_booking_data(): void
    {
        $mockResponse = [
            'order_id' => '12345',
            'security' => 'abc123',
            'status' => 'reserve_ok',
            'price_total' => '91.00',
            'currency' => 'EUR',
            'reservation_until' => '2024-12-31 15:30:00',
            'item' => []
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($mockResponse))
        );

        $bookingData = \Nikba\LaravelBussystemApi\Data\BookingData::create()
            ->addRoute('2024-12-31', 'test_interval_123')
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->addPassenger('Jane', 'Doe', '1992-02-02', 1, 'CD789012', 'F')
            ->addSeats(0, ['1', '2'])
            ->setContactInfo('+1234567890', 'test@example.com');

        $response = $this->client->createOrder($bookingData);

        $this->assertEquals($mockResponse, $response);
        $this->assertEquals('12345', $response['order_id']);
        $this->assertEquals('reserve_ok', $response['status']);
    }

    public function test_buy_tickets_processes_payment(): void
    {
        $mockResponse = [
            'order_id' => '12345',
            'price_total' => '91.00',
            'currency' => 'EUR',
            'link' => 'https://test-api.bussystem.eu/viev/frame/print_ticket.php?order_id=12345&security=abc123&lang=en',
            'item' => [
                [
                    'ticket_id' => '67890',
                    'security' => 'xyz789',
                    'price' => '45.50',
                    'provision' => '4.55',
                    'currency' => 'EUR'
                ]
            ]
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($mockResponse))
        );

        $response = $this->client->buyTickets(12345, 'en');

        $this->assertEquals($mockResponse, $response);
        $this->assertArrayHasKey('link', $response);
        $this->assertArrayHasKey('item', $response);
    }

    public function test_cancel_tickets_processes_cancellation(): void
    {
        $mockResponse = [
            'order_id' => '12345',
            'cancel_order' => '1',
            'price_total' => '0',
            'money_back_total' => '91.00',
            'currency' => 'EUR'
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($mockResponse))
        );

        $response = $this->client->cancelTickets([
            'order_id' => 12345,
            'security' => 'abc123',
            'lang' => 'en'
        ]);

        $this->assertEquals($mockResponse, $response);
        $this->assertEquals('1', $response['cancel_order']);
    }

    public function test_get_order_returns_order_details(): void
    {
        $mockResponse = [
            'order_id' => '12345',
            'security' => 'abc123',
            'status' => 'buy',
            'price' => '91.00',
            'currency' => 'EUR',
            'routes' => [],
            'history' => []
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($mockResponse))
        );

        $response = $this->client->getOrder(12345, 'abc123', 'en');

        $this->assertEquals($mockResponse, $response);
        $this->assertEquals('buy', $response['status']);
    }

    public function test_validate_reservation_checks_phone_eligibility(): void
    {
        $mockResponse = [
            'reserve_validation' => '1',
            'need_sms_validation' => '0'
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($mockResponse))
        );

        $response = $this->client->validateReservation('+1234567890', 'en');

        $this->assertEquals($mockResponse, $response);
        $this->assertEquals('1', $response['reserve_validation']);
    }

    public function test_api_error_throws_exception(): void
    {
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'error' => 'dealer_no_activ',
                'detail' => 'Dealer not active'
            ]))
        );

        $this->expectException(\Nikba\LaravelBussystemApi\Exceptions\BusSystemAuthenticationException::class);
        $this->expectExceptionMessage('Dealer not active: Dealer not active');

        $this->client->ping();
    }

    public function test_validation_error_throws_validation_exception(): void
    {
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                'error' => 'no_phone',
                'detail' => 'Phone number is required'
            ]))
        );

        $this->expectException(\Nikba\LaravelBussystemApi\Exceptions\BusSystemValidationException::class);
        $this->expectExceptionMessage('Validation error: no_phone - Phone number is required');

        $this->client->ping();
    }
}