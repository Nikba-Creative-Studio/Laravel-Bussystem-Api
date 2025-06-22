<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Facades;

use Illuminate\Support\Facades\Facade;
use Nikba\LaravelBussystemApi\Contracts\BusSystemClientInterface;

/**
 * @method static array getPoints(array $parameters = [])
 * @method static array getRoutes(\Nikba\LaravelBussystemApi\Data\SearchCriteria $criteria)
 * @method static array getAllRoutes(string $timetableId, string $language = 'en')
 * @method static array getFreeSeats(string $intervalId, array $parameters = [])
 * @method static array getSeatPlan(array $parameters = [])
 * @method static array getDiscounts(string $intervalId, array $parameters = [])
 * @method static array getBaggage(string $intervalId, array $parameters = [])
 * @method static array createOrder(\Nikba\LaravelBussystemApi\Data\BookingData $bookingData)
 * @method static array buyTickets(int $orderId, string $language = 'en')
 * @method static array cancelTickets(array $parameters)
 * @method static array getOrder(int $orderId, ?string $security = null, string $language = 'en')
 * @method static array getTicket(array $parameters)
 * @method static array reserveTickets(int $orderId, array $parameters = [])
 * @method static array validateReservation(string $phoneNumber, string $language = 'en')
 * @method static array validateSms(array $parameters)
 * @method static array ping()
 *
 * @see \Nikba\LaravelBussystemApi\Contracts\BusSystemClientInterface
 */
class BusSystem extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BusSystemClientInterface::class;
    }
}