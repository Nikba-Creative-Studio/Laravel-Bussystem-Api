<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Data;

class BookingData
{
    private array $dates = [];
    private array $intervalIds = [];
    private array $stationFromIds = [];
    private array $stationToIds = [];
    private array $seats = [];
    private array $passengers = [];
    private array $discounts = [];
    private array $baggage = [];
    private array $wagonIds = [];
    private ?string $phone = null;
    private ?string $phone2 = null;
    private ?string $email = null;
    private ?string $info = null;
    private ?string $promocode = null;
    private string $currency;
    private string $language;

    public function __construct(
        string $currency = 'EUR',
        string $language = 'en'
    ) {
        $this->currency = $currency ?: config('bussystem.default_currency', 'EUR');
        $this->language = $language ?: config('bussystem.default_language', 'en');
    }

    public static function create(string $currency = 'EUR', string $language = 'en'): self
    {
        return new self($currency, $language);
    }

    public function addRoute(
        string $date,
        string $intervalId,
        ?int $stationFromId = null,
        ?int $stationToId = null
    ): self {
        $this->dates[] = $date;
        $this->intervalIds[] = $intervalId;
        
        if ($stationFromId !== null) {
            $this->stationFromIds[] = $stationFromId;
        }
        
        if ($stationToId !== null) {
            $this->stationToIds[] = $stationToId;
        }

        return $this;
    }

    public function addPassenger(
        string $firstName,
        string $lastName,
        string $birthDate,
        int $docType = 1,
        string $docNumber = '',
        string $gender = 'M',
        ?string $middleName = null,
        ?string $citizenship = null,
        ?string $docExpireDate = null
    ): self {
        $passenger = [
            'name' => $firstName,
            'surname' => $lastName,
            'birth_date' => $birthDate,
            'doc_type' => $docType,
            'doc_number' => $docNumber,
            'gender' => $gender,
        ];

        if ($middleName !== null) {
            $passenger['middlename'] = $middleName;
        }

        if ($citizenship !== null) {
            $passenger['citizenship'] = $citizenship;
        }

        if ($docExpireDate !== null) {
            $passenger['doc_expire_date'] = $docExpireDate;
        }

        $this->passengers[] = $passenger;

        return $this;
    }

    public function addSeats(int $routeIndex, array $seats): self
    {
        $this->seats[$routeIndex] = $seats;
        return $this;
    }

    public function addSeat(int $routeIndex, string $seat): self
    {
        if (!isset($this->seats[$routeIndex])) {
            $this->seats[$routeIndex] = [];
        }
        
        $this->seats[$routeIndex][] = $seat;
        return $this;
    }

    public function addDiscount(int $routeIndex, int $passengerIndex, string $discountId): self
    {
        if (!isset($this->discounts[$routeIndex])) {
            $this->discounts[$routeIndex] = [];
        }
        
        $this->discounts[$routeIndex][$passengerIndex] = $discountId;
        return $this;
    }

    public function addBaggage(int $routeIndex, int $passengerIndex, array $baggageIds): self
    {
        if (!isset($this->baggage[$routeIndex])) {
            $this->baggage[$routeIndex] = [];
        }
        
        $this->baggage[$routeIndex][$passengerIndex] = $baggageIds;
        return $this;
    }

    public function addWagon(int $routeIndex, string $wagonId): self
    {
        $this->wagonIds[$routeIndex] = $wagonId;
        return $this;
    }

    public function setContactInfo(string $phone, ?string $email = null, ?string $phone2 = null): self
    {
        $this->phone = $phone;
        $this->email = $email;
        $this->phone2 = $phone2;
        return $this;
    }

    public function setAdditionalInfo(string $info): self
    {
        $this->info = $info;
        return $this;
    }

    public function setPromocode(string $promocode): self
    {
        $this->promocode = $promocode;
        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'v' => config('bussystem.default_api_version', '1.1'),
            'currency' => $this->currency,
            'lang' => $this->language,
        ];

        // Add routes data
        if (!empty($this->dates)) {
            $data['date'] = $this->dates;
        }

        if (!empty($this->intervalIds)) {
            $data['interval_id'] = $this->intervalIds;
        }

        if (!empty($this->stationFromIds)) {
            $data['station_from_id'] = $this->stationFromIds;
        }

        if (!empty($this->stationToIds)) {
            $data['station_to_id'] = $this->stationToIds;
        }

        // Add seats
        if (!empty($this->seats)) {
            $data['seat'] = $this->seats;
        }

        // Add wagon IDs for trains
        if (!empty($this->wagonIds)) {
            $data['vagon_id'] = $this->wagonIds;
        }

        // Add passenger data
        if (!empty($this->passengers)) {
            foreach (['name', 'surname', 'middlename', 'birth_date', 'doc_type', 'doc_number', 'doc_expire_date', 'citizenship', 'gender'] as $field) {
                $values = [];
                foreach ($this->passengers as $passenger) {
                    if (isset($passenger[$field])) {
                        $values[] = $passenger[$field];
                    }
                }
                if (!empty($values)) {
                    $data[$field] = $values;
                }
            }
        }

        // Add discounts
        if (!empty($this->discounts)) {
            $data['discount_id'] = $this->discounts;
        }

        // Add baggage
        if (!empty($this->baggage)) {
            $data['baggage'] = $this->baggage;
        }

        // Add contact information
        if ($this->phone !== null) {
            $data['phone'] = $this->phone;
        }

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->phone2 !== null) {
            $data['phone2'] = $this->phone2;
        }

        if ($this->info !== null) {
            $data['info'] = $this->info;
        }

        if ($this->promocode !== null) {
            $data['promocode_name'] = $this->promocode;
        }

        return $data;
    }

    public function getPassengerCount(): int
    {
        return count($this->passengers);
    }

    public function getRouteCount(): int
    {
        return count($this->intervalIds);
    }

    public function validate(): array
    {
        $errors = [];

        if (empty($this->intervalIds)) {
            $errors[] = 'At least one route must be specified';
        }

        if (empty($this->passengers)) {
            $errors[] = 'At least one passenger must be specified';
        }

        if (count($this->dates) !== count($this->intervalIds)) {
            $errors[] = 'Number of dates must match number of interval IDs';
        }

        foreach ($this->passengers as $index => $passenger) {
            if (empty($passenger['name'])) {
                $errors[] = "Passenger {$index}: First name is required";
            }

            if (empty($passenger['surname'])) {
                $errors[] = "Passenger {$index}: Last name is required";
            }

            if (empty($passenger['birth_date'])) {
                $errors[] = "Passenger {$index}: Birth date is required";
            }
        }

        if ($this->phone === null) {
            $errors[] = 'Phone number is required';
        }

        return $errors;
    }
}