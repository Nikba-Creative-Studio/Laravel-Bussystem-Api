<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Data;

class SearchCriteria
{
    public function __construct(
        private ?string $date = null,
        private ?int $fromCityId = null,
        private ?int $toCityId = null,
        private ?int $trainFromId = null,
        private ?int $trainToId = null,
        private ?string $iataFromCode = null,
        private ?string $iataToCode = null,
        private ?int $stationFromId = null,
        private ?int $stationToId = null,
        private string $transport = 'all',
        private string $currency = 'EUR',
        private string $language = 'en',
        private string $change = 'auto',
        private int $period = 0,
        private string $sortType = 'time',
        private int $getAllDeparture = 0,
        private array $additionalParams = []
    ) {
        $this->date = $date ?? date('Y-m-d');
        $this->currency = $currency ?: config('bussystem.default_currency', 'EUR');
        $this->language = $language ?: config('bussystem.default_language', 'en');
    }

    public static function create(): self
    {
        return new self();
    }

    public function date(string $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function from(int $cityId): self
    {
        $this->fromCityId = $cityId;
        return $this;
    }

    public function to(int $cityId): self
    {
        $this->toCityId = $cityId;
        return $this;
    }

    public function trainFrom(int $stationId): self
    {
        $this->trainFromId = $stationId;
        return $this;
    }

    public function trainTo(int $stationId): self
    {
        $this->trainToId = $stationId;
        return $this;
    }

    public function airportFrom(string $iataCode): self
    {
        $this->iataFromCode = $iataCode;
        return $this;
    }

    public function airportTo(string $iataCode): self
    {
        $this->iataToCode = $iataCode;
        return $this;
    }

    public function stationFrom(int $stationId): self
    {
        $this->stationFromId = $stationId;
        return $this;
    }

    public function stationTo(int $stationId): self
    {
        $this->stationToId = $stationId;
        return $this;
    }

    public function transport(string $transport): self
    {
        $this->transport = $transport;
        return $this;
    }

    public function bus(): self
    {
        $this->transport = 'bus';
        return $this;
    }

    public function train(): self
    {
        $this->transport = 'train';
        return $this;
    }

    public function air(): self
    {
        $this->transport = 'air';
        return $this;
    }

    public function currency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function language(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function allowTransfers(string $change = 'auto'): self
    {
        $this->change = $change;
        return $this;
    }

    public function directOnly(): self
    {
        $this->change = '0';
        return $this;
    }

    public function period(int $days): self
    {
        $this->period = max(-3, min(14, $days));
        return $this;
    }

    public function sortBy(string $type): self
    {
        $this->sortType = in_array($type, ['time', 'price']) ? $type : 'time';
        return $this;
    }

    public function sortByTime(): self
    {
        $this->sortType = 'time';
        return $this;
    }

    public function sortByPrice(): self
    {
        $this->sortType = 'price';
        return $this;
    }

    public function includeSoldOut(bool $include = true): self
    {
        $this->getAllDeparture = $include ? 1 : 0;
        return $this;
    }

    public function addParam(string $key, mixed $value): self
    {
        $this->additionalParams[$key] = $value;
        return $this;
    }

    public function airPassengers(int $adults = 1, int $children = 0, int $infants = 0): self
    {
        $this->additionalParams['adt'] = $adults;
        $this->additionalParams['chd'] = $children;
        $this->additionalParams['inf'] = $infants;
        return $this;
    }

    public function airServiceClass(string $class = 'E'): self
    {
        $this->additionalParams['service_class'] = $class;
        return $this;
    }

    public function airDirect(bool $direct = true): self
    {
        $this->additionalParams['direct'] = $direct ? 1 : 0;
        return $this;
    }

    public function airBaggage(bool $includeBaggage = true): self
    {
        $this->additionalParams['baggage_no'] = $includeBaggage ? 0 : 1;
        return $this;
    }

    public function toArray(): array
    {
        $params = [
            'date' => $this->date,
            'trans' => $this->transport,
            'currency' => $this->currency,
            'lang' => $this->language,
            'change' => $this->change,
            'period' => $this->period,
            'sort_type' => $this->sortType,
            'get_all_departure' => $this->getAllDeparture,
            'v' => config('bussystem.default_api_version', '1.1'),
        ];

        // Add location parameters based on transport type
        if ($this->fromCityId !== null) {
            $params['id_from'] = $this->fromCityId;
        }
        if ($this->toCityId !== null) {
            $params['id_to'] = $this->toCityId;
        }
        if ($this->trainFromId !== null) {
            $params['point_train_from_id'] = $this->trainFromId;
        }
        if ($this->trainToId !== null) {
            $params['point_train_to_id'] = $this->trainToId;
        }
        if ($this->iataFromCode !== null) {
            $params['id_iata_from'] = $this->iataFromCode;
        }
        if ($this->iataToCode !== null) {
            $params['id_iata_to'] = $this->iataToCode;
        }
        if ($this->stationFromId !== null) {
            $params['station_id_from'] = $this->stationFromId;
        }
        if ($this->stationToId !== null) {
            $params['station_id_to'] = $this->stationToId;
        }

        return array_merge($params, $this->additionalParams);
    }
}