<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Tests\Unit;

use Nikba\LaravelBussystemApi\Data\BookingData;
use Nikba\LaravelBussystemApi\Tests\TestCase;

class BookingDataTest extends TestCase
{
    public function test_can_create_basic_booking_data(): void
    {
        $booking = BookingData::create('EUR', 'en');

        $this->assertInstanceOf(BookingData::class, $booking);
    }

    public function test_can_add_routes(): void
    {
        $booking = BookingData::create()
            ->addRoute('2024-12-31', 'interval_123', 456, 789);

        $array = $booking->toArray();

        $this->assertEquals(['2024-12-31'], $array['date']);
        $this->assertEquals(['interval_123'], $array['interval_id']);
        $this->assertEquals([456], $array['station_from_id']);
        $this->assertEquals([789], $array['station_to_id']);
    }

    public function test_can_add_multiple_routes(): void
    {
        $booking = BookingData::create()
            ->addRoute('2024-12-31', 'interval_123')
            ->addRoute('2025-01-01', 'interval_456');

        $array = $booking->toArray();

        $this->assertEquals(['2024-12-31', '2025-01-01'], $array['date']);
        $this->assertEquals(['interval_123', 'interval_456'], $array['interval_id']);
    }

    public function test_can_add_passengers(): void
    {
        $booking = BookingData::create()
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->addPassenger('Jane', 'Smith', '1992-02-02', 1, 'CD789012', 'F', 'Marie');

        $array = $booking->toArray();

        $this->assertEquals(['John', 'Jane'], $array['name']);
        $this->assertEquals(['Doe', 'Smith'], $array['surname']);
        $this->assertEquals(['1990-01-01', '1992-02-02'], $array['birth_date']);
        $this->assertEquals([1, 1], $array['doc_type']);
        $this->assertEquals(['AB123456', 'CD789012'], $array['doc_number']);
        $this->assertEquals(['M', 'F'], $array['gender']);
        $this->assertEquals(['Marie'], $array['middlename']);
    }

    public function test_can_add_seats(): void
    {
        $booking = BookingData::create()
            ->addSeats(0, ['1', '2'])
            ->addSeats(1, ['3', '4']);

        $array = $booking->toArray();

        $this->assertEquals([
            0 => ['1', '2'],
            1 => ['3', '4']
        ], $array['seat']);
    }

    public function test_can_add_individual_seat(): void
    {
        $booking = BookingData::create()
            ->addSeat(0, '1')
            ->addSeat(0, '2')
            ->addSeat(1, '3');

        $array = $booking->toArray();

        $this->assertEquals([
            0 => ['1', '2'],
            1 => ['3']
        ], $array['seat']);
    }

    public function test_can_add_discounts(): void
    {
        $booking = BookingData::create()
            ->addDiscount(0, 1, 'discount_123')
            ->addDiscount(1, 0, 'discount_456');

        $array = $booking->toArray();

        $this->assertEquals([
            0 => [1 => 'discount_123'],
            1 => [0 => 'discount_456']
        ], $array['discount_id']);
    }

    public function test_can_add_baggage(): void
    {
        $booking = BookingData::create()
            ->addBaggage(0, 0, ['baggage_1', 'baggage_2'])
            ->addBaggage(0, 1, ['baggage_3']);

        $array = $booking->toArray();

        $this->assertEquals([
            0 => [
                0 => ['baggage_1', 'baggage_2'],
                1 => ['baggage_3']
            ]
        ], $array['baggage']);
    }

    public function test_can_add_wagon_ids(): void
    {
        $booking = BookingData::create()
            ->addWagon(0, 'wagon_123')
            ->addWagon(1, 'wagon_456');

        $array = $booking->toArray();

        $this->assertEquals([
            0 => 'wagon_123',
            1 => 'wagon_456'
        ], $array['vagon_id']);
    }

    public function test_can_set_contact_info(): void
    {
        $booking = BookingData::create()
            ->setContactInfo('+1234567890', 'test@example.com', '+0987654321');

        $array = $booking->toArray();

        $this->assertEquals('+1234567890', $array['phone']);
        $this->assertEquals('test@example.com', $array['email']);
        $this->assertEquals('+0987654321', $array['phone2']);
    }

    public function test_can_set_additional_info(): void
    {
        $booking = BookingData::create()
            ->setAdditionalInfo('Special request: window seat');

        $array = $booking->toArray();

        $this->assertEquals('Special request: window seat', $array['info']);
    }

    public function test_can_set_promocode(): void
    {
        $booking = BookingData::create()
            ->setPromocode('SAVE20');

        $array = $booking->toArray();

        $this->assertEquals('SAVE20', $array['promocode_name']);
    }

    public function test_can_set_currency_and_language(): void
    {
        $booking = BookingData::create()
            ->setCurrency('USD')
            ->setLanguage('de');

        $array = $booking->toArray();

        $this->assertEquals('USD', $array['currency']);
        $this->assertEquals('de', $array['lang']);
    }

    public function test_get_counts(): void
    {
        $booking = BookingData::create()
            ->addRoute('2024-12-31', 'interval_123')
            ->addRoute('2025-01-01', 'interval_456')
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->addPassenger('Jane', 'Smith', '1992-02-02', 1, 'CD789012', 'F');

        $this->assertEquals(2, $booking->getPassengerCount());
        $this->assertEquals(2, $booking->getRouteCount());
    }

    public function test_validation_passes_with_valid_data(): void
    {
        $booking = BookingData::create()
            ->addRoute('2024-12-31', 'interval_123')
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->setContactInfo('+1234567890');

        $errors = $booking->validate();

        $this->assertEmpty($errors);
    }

    public function test_validation_fails_with_missing_routes(): void
    {
        $booking = BookingData::create()
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->setContactInfo('+1234567890');

        $errors = $booking->validate();

        $this->assertContains('At least one route must be specified', $errors);
    }

    public function test_validation_fails_with_missing_passengers(): void
    {
        $booking = BookingData::create()
            ->addRoute('2024-12-31', 'interval_123')
            ->setContactInfo('+1234567890');

        $errors = $booking->validate();

        $this->assertContains('At least one passenger must be specified', $errors);
    }

    public function test_validation_fails_with_missing_passenger_data(): void
    {
        $booking = BookingData::create()
            ->addRoute('2024-12-31', 'interval_123')
            ->addPassenger('', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->setContactInfo('+1234567890');

        $errors = $booking->validate();

        $this->assertContains('Passenger 0: First name is required', $errors);
    }

    public function test_validation_fails_with_missing_phone(): void
    {
        $booking = BookingData::create()
            ->addRoute('2024-12-31', 'interval_123')
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M');

        $errors = $booking->validate();

        $this->assertContains('Phone number is required', $errors);
    }

    public function test_validation_fails_with_mismatched_dates_and_intervals(): void
    {
        $booking = BookingData::create()
            ->addRoute('2024-12-31', 'interval_123')
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->setContactInfo('+1234567890');

        // Manually add an extra interval without date
        $reflection = new \ReflectionClass($booking);
        $intervalIds = $reflection->getProperty('intervalIds');
        $intervalIds->setAccessible(true);
        $currentIds = $intervalIds->getValue($booking);
        $currentIds[] = 'interval_456';
        $intervalIds->setValue($booking, $currentIds);

        $errors = $booking->validate();

        $this->assertContains('Number of dates must match number of interval IDs', $errors);
    }

    public function test_method_chaining_works(): void
    {
        $booking = BookingData::create('USD', 'de')
            ->addRoute('2024-12-31', 'interval_123')
            ->addPassenger('John', 'Doe', '1990-01-01', 1, 'AB123456', 'M')
            ->addSeats(0, ['1', '2'])
            ->setContactInfo('+1234567890', 'test@example.com')
            ->setPromocode('SAVE20');

        $this->assertInstanceOf(BookingData::class, $booking);
        
        $array = $booking->toArray();
        $this->assertEquals('USD', $array['currency']);
        $this->assertEquals('de', $array['lang']);
        $this->assertEquals('SAVE20', $array['promocode_name']);
    }
}