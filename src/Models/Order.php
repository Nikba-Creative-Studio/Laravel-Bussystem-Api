<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'bussystem_orders';

    protected $fillable = [
        'order_id',
        'security_code',
        'status',
        'price_total',
        'currency',
        'passenger_count',
        'route_count',
        'phone',
        'email',
        'promocode',
        'reservation_until',
        'api_response',
        'user_id',
    ];

    protected $casts = [
        'price_total' => 'decimal:2',
        'passenger_count' => 'integer',
        'route_count' => 'integer',
        'reservation_until' => 'datetime',
        'api_response' => 'array',
        'user_id' => 'integer',
    ];

    protected $dates = [
        'reservation_until',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function isReserved(): bool
    {
        return in_array($this->status, ['reserve', 'reserve_ok']);
    }

    public function isPaid(): bool
    {
        return $this->status === 'buy';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancel';
    }

    public function isExpired(): bool
    {
        return $this->reservation_until && $this->reservation_until->isPast();
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancel']);
    }

    public function scopeReserved($query)
    {
        return $query->whereIn('status', ['reserve', 'reserve_ok']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'buy');
    }

    public function scopeExpired($query)
    {
        return $query->where('reservation_until', '<', now());
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}