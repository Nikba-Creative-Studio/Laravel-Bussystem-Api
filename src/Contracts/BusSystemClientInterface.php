<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Contracts;

use Nikba\LaravelBussystemApi\Data\BookingData;
use Nikba\LaravelBussystemApi\Data\SearchCriteria;

interface BusSystemClientInterface
{
    /**
     * Search for points (cities, stations, airports).
     */
    public function getPoints(array $parameters = []): array;

    /**
     * Search for available routes.
     */
    public function getRoutes(SearchCriteria $criteria): array;

    /**
     * Get detailed route information.
     */
    public function getAllRoutes(string $timetableId, string $language = 'en'): array;

    /**
     * Get available seats for a route.
     */
    public function getFreeSeats(string $intervalId, array $parameters = []): array;

    /**
     * Get seat plan layout.
     */
    public function getSeatPlan(array $parameters = []): array;

    /**
     * Get available discounts for a route.
     */
    public function getDiscounts(string $intervalId, array $parameters = []): array;

    /**
     * Get baggage options for a route.
     */
    public function getBaggage(string $intervalId, array $parameters = []): array;

    /**
     * Create a new order.
     */
    public function createOrder(BookingData $bookingData): array;

    /**
     * Purchase tickets for an order.
     */
    public function buyTickets(int $orderId, string $language = 'en'): array;

    /**
     * Cancel tickets or order.
     */
    public function cancelTickets(array $parameters): array;

    /**
     * Get order information.
     */
    public function getOrder(int $orderId, ?string $security = null, string $language = 'en'): array;

    /**
     * Get ticket information.
     */
    public function getTicket(array $parameters): array;

    /**
     * Reserve tickets with payment on boarding.
     */
    public function reserveTickets(int $orderId, array $parameters = []): array;

    /**
     * Validate phone number for payment on boarding.
     */
    public function validateReservation(string $phoneNumber, string $language = 'en'): array;

    /**
     * SMS validation for payment on boarding.
     */
    public function validateSms(array $parameters): array;

    /**
     * Check API server status.
     */
    public function ping(): array;
}