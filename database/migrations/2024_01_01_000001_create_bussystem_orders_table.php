<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bussystem_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique()->comment('BusSystem order ID');
            $table->string('security_code')->comment('BusSystem security code');
            $table->string('status', 50)->default('reserve')->comment('Order status');
            $table->decimal('price_total', 10, 2)->nullable()->comment('Total order price');
            $table->string('currency', 3)->default('EUR')->comment('Currency code');
            $table->unsignedTinyInteger('passenger_count')->default(1)->comment('Number of passengers');
            $table->unsignedTinyInteger('route_count')->default(1)->comment('Number of routes');
            $table->string('phone', 20)->nullable()->comment('Contact phone number');
            $table->string('email')->nullable()->comment('Contact email');
            $table->string('promocode', 50)->nullable()->comment('Applied promocode');
            $table->timestamp('reservation_until')->nullable()->comment('Reservation expiration time');
            $table->json('api_response')->nullable()->comment('Full API response');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Associated user ID');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['user_id']);
            $table->index(['reservation_until']);
            $table->index(['created_at']);
            $table->index(['phone']);
            $table->index(['email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bussystem_orders');
    }
};