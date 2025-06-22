<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Nikba\LaravelBussystemApi\Contracts\BusSystemClientInterface;
use Nikba\LaravelBussystemApi\Data\BookingData;
use Nikba\LaravelBussystemApi\Data\SearchCriteria;
use Nikba\LaravelBussystemApi\Exceptions\BusSystemApiException;
use Nikba\LaravelBussystemApi\Exceptions\BusSystemAuthenticationException;
use Nikba\LaravelBussystemApi\Exceptions\BusSystemValidationException;

class BusSystemClient implements BusSystemClientInterface
{
    private Client $httpClient;

    public function __construct(
        private readonly string $apiUrl,
        private readonly string $login,
        private readonly string $password,
        private readonly ?string $partnerId = null,
        private readonly int $timeout = 120
    ) {
        $this->httpClient = new Client([
            'timeout' => $this->timeout,
            'verify' => true,
        ]);
    }

    public function getPoints(array $parameters = []): array
    {
        $cacheKey = $this->getCacheKey('points', $parameters);
        
        if (config('bussystem.cache.enabled') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = $this->makeRequest('get_points.php', $parameters);
        
        if (config('bussystem.cache.enabled')) {
            Cache::put($cacheKey, $response, config('bussystem.cache.ttl.points'));
        }

        return $response;
    }

    public function getRoutes(SearchCriteria $criteria): array
    {
        $parameters = $criteria->toArray();
        $this->addAuthenticationParameters($parameters);
        $this->addPartnerIdIfAvailable($parameters);

        $cacheKey = $this->getCacheKey('routes', $parameters);
        
        if (config('bussystem.cache.enabled') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = $this->makeRequest('get_routes.php', $parameters);
        
        if (config('bussystem.cache.enabled')) {
            Cache::put($cacheKey, $response, config('bussystem.cache.ttl.routes'));
        }

        return $response;
    }

    public function getAllRoutes(string $timetableId, string $language = 'en'): array
    {
        return $this->makeRequest('get_all_routes.php', [
            'timetable_id' => $timetableId,
            'lang' => $language,
        ]);
    }

    public function getFreeSeats(string $intervalId, array $parameters = []): array
    {
        $parameters['interval_id'] = $intervalId;
        
        return $this->makeRequest('get_free_seats.php', $parameters);
    }

    public function getSeatPlan(array $parameters = []): array
    {
        $cacheKey = $this->getCacheKey('plans', $parameters);
        
        if (config('bussystem.cache.enabled') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = $this->makeRequest('get_plan.php', $parameters);
        
        if (config('bussystem.cache.enabled')) {
            Cache::put($cacheKey, $response, config('bussystem.cache.ttl.plans'));
        }

        return $response;
    }

    public function getDiscounts(string $intervalId, array $parameters = []): array
    {
        $parameters['interval_id'] = $intervalId;
        
        return $this->makeRequest('get_discount.php', $parameters);
    }

    public function getBaggage(string $intervalId, array $parameters = []): array
    {
        $parameters['interval_id'] = $intervalId;
        
        return $this->makeRequest('get_baggage.php', $parameters);
    }

    public function createOrder(BookingData $bookingData): array
    {
        $parameters = $bookingData->toArray();
        $this->addPartnerIdIfAvailable($parameters);

        return $this->makeRequest('new_order.php', $parameters);
    }

    public function buyTickets(int $orderId, string $language = 'en'): array
    {
        return $this->makeRequest('buy_ticket.php', [
            'order_id' => $orderId,
            'lang' => $language,
            'v' => config('bussystem.default_api_version'),
        ]);
    }

    public function cancelTickets(array $parameters): array
    {
        $parameters['v'] = config('bussystem.default_api_version');
        
        return $this->makeRequest('cancel_ticket.php', $parameters);
    }

    public function getOrder(int $orderId, ?string $security = null, string $language = 'en'): array
    {
        $parameters = [
            'order_id' => $orderId,
            'lang' => $language,
        ];

        if ($security !== null) {
            $parameters['security'] = $security;
        }

        return $this->makeRequest('get_order.php', $parameters);
    }

    public function getTicket(array $parameters): array
    {
        return $this->makeRequest('get_ticket.php', $parameters);
    }

    public function reserveTickets(int $orderId, array $parameters = []): array
    {
        $parameters['order_id'] = $orderId;
        $parameters['v'] = config('bussystem.default_api_version');
        
        return $this->makeRequest('reserve_ticket.php', $parameters);
    }

    public function validateReservation(string $phoneNumber, string $language = 'en'): array
    {
        return $this->makeRequest('reserve_validation.php', [
            'phone' => $phoneNumber,
            'lang' => $language,
            'v' => config('bussystem.default_api_version'),
        ]);
    }

    public function validateSms(array $parameters): array
    {
        $parameters['v'] = config('bussystem.default_api_version');
        
        return $this->makeRequest('sms_validation.php', $parameters);
    }

    public function ping(): array
    {
        return $this->makeRequest('ping.php', []);
    }

    private function makeRequest(string $endpoint, array $parameters): array
    {
        $this->addAuthenticationParameters($parameters);

        $url = $this->apiUrl . '/curl/' . $endpoint;

        try {
            $options = [
                'form_params' => $parameters,
            ];

            // Add JSON Accept header if configured
            if (config('bussystem.response_format') === 'json') {
                $options['headers'] = ['Accept' => 'application/json'];
            }

            $this->logRequest($endpoint, $parameters);

            $response = $this->httpClient->post($url, $options);
            $body = $response->getBody()->getContents();

            $data = $this->parseResponse($body);

            $this->logResponse($endpoint, $data);

            if (isset($data['error'])) {
                $this->handleApiError($data);
            }

            return $data;

        } catch (GuzzleException $e) {
            $this->logError($endpoint, $e->getMessage());
            throw new BusSystemApiException(
                "API request failed: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    private function parseResponse(string $body): array
    {
        // Try JSON first
        $json = json_decode($body, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }

        // Fallback to XML
        try {
            $xml = simplexml_load_string($body);
            if ($xml !== false) {
                return json_decode(json_encode($xml), true);
            }
        } catch (\Exception $e) {
            // XML parsing failed
        }

        throw new BusSystemApiException('Unable to parse API response');
    }

    private function handleApiError(array $data): void
    {
        $error = $data['error'];
        $detail = $data['detail'] ?? '';

        switch ($error) {
            case 'dealer_no_activ':
                throw new BusSystemAuthenticationException('Dealer not active: ' . $detail);
            
            case 'no_phone':
            case 'no_name':
            case 'no_doc':
            case 'date':
                throw new BusSystemValidationException("Validation error: {$error} - {$detail}");
            
            default:
                throw new BusSystemApiException("API error: {$error} - {$detail}");
        }
    }

    private function addAuthenticationParameters(array &$parameters): void
    {
        $parameters['login'] = $this->login;
        $parameters['password'] = $this->password;
    }

    private function addPartnerIdIfAvailable(array &$parameters): void
    {
        if ($this->partnerId !== null) {
            $parameters['partner'] = $this->partnerId;
        }
    }

    private function getCacheKey(string $type, array $parameters): string
    {
        $prefix = config('bussystem.cache.prefix');
        $hash = md5(serialize($parameters));
        
        return "{$prefix}:{$type}:{$hash}";
    }

    private function logRequest(string $endpoint, array $parameters): void
    {
        if (!config('bussystem.logging.enabled')) {
            return;
        }

        // Remove sensitive data from logs
        $logParameters = $parameters;
        unset($logParameters['login'], $logParameters['password']);

        Log::channel(config('bussystem.logging.channel'))
            ->log(config('bussystem.logging.level'), "BusSystem API Request: {$endpoint}", [
                'endpoint' => $endpoint,
                'parameters' => $logParameters,
            ]);
    }

    private function logResponse(string $endpoint, array $data): void
    {
        if (!config('bussystem.logging.enabled')) {
            return;
        }

        Log::channel(config('bussystem.logging.channel'))
            ->log(config('bussystem.logging.level'), "BusSystem API Response: {$endpoint}", [
                'endpoint' => $endpoint,
                'has_error' => isset($data['error']),
                'response_size' => count($data),
            ]);
    }

    private function logError(string $endpoint, string $message): void
    {
        if (!config('bussystem.logging.enabled')) {
            return;
        }

        Log::channel(config('bussystem.logging.channel'))
            ->error("BusSystem API Error: {$endpoint}", [
                'endpoint' => $endpoint,
                'message' => $message,
            ]);
    }
}