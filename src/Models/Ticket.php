<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $table = 'bussystem_tickets';

    protected $fillable = [
        'order_id',
        'ticket_id',
        'transaction_id',
        'security_code',
        'passenger_name',
        'passenger_surname',
        'passenger_middlename',
        'passenger_birth_date',
        'passenger_doc_type',
        'passenger_doc_number',
        'passenger_gender',
        'seat_number',
        'price',
        'provision',
        'currency',
        'route_from',
        'route_to',
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
        'carrier',
        'status',
        'pdf_link',
        'api_response',
    ];

    protected $casts = [
        'passenger_doc_type' => 'integer',
        'price' => 'decimal:2',
        'provision' => 'decimal:2',
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'api_response' => 'array',
    ];

    protected $dates = [
        'passenger_birth_date',
        'departure_date',
        'arrival_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getPassengerFullNameAttribute(): string
    {
        $name = trim($this->passenger_name . ' ' . $this->passenger_surname);
        
        if ($this->passenger_middlename) {
            $name .= ' ' . $this->passenger_middlename;
        }

        return $name;
    }

    public function getRouteDescriptionAttribute(): string
    {
        return $this->route_from . ' → ' . $this->route_to;
    }

    public function getDepartureDateTimeAttribute(): string
    {
        if ($this->departure_date && $this->departure_time) {
            return $this->departure_date->format('Y-m-d') . ' ' . $this->departure_time;
        }

        return '';
    }

    public function getArrivalDateTimeAttribute(): string
    {
        if ($this->arrival_date && $this->arrival_time) {
            return $this->arrival_date->format('Y-m-d') . ' ' . $this->arrival_time;
        }

        return '';
    }

    public function isPaid(): bool
    {
        return $this->status === 'buy';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancel';
    }

    public function isReserved(): bool
    {
        return in_array($this->status, ['reserve', 'reserve_ok']);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancel']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'buy');
    }

    public function scopeReserved($query)
    {
        return $query->whereIn('status', ['reserve', 'reserve_ok']);
    }

    public function scopeForPassenger($query, string $name, string $surname)
    {
        return $query->where('passenger_name', $name)
                    ->where('passenger_surname', $surname);
    }

    public function scopeForRoute($query, string $from, string $to)
    {
        return $query->where('route_from', $from)
                    ->where('route_to', $to);
    }

    public function scopeDepartingAfter($query, $date)
    {
        return $query->where('departure_date', '>=', $date);
    }

    public function scopeDepartingBefore($query, $date)
    {
        return $query->where('departure_date', '<=', $date);
    }
}