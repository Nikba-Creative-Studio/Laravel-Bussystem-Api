<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bussystem_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('ticket_id')->unique()->comment('BusSystem ticket ID');
            $table->unsignedBigInteger('transaction_id')->nullable()->comment('BusSystem transaction ID');
            $table->string('security_code')->comment('Ticket security code');
            
            // Passenger information
            $table->string('passenger_name', 100)->comment('Passenger first name');
            $table->string('passenger_surname', 100)->comment('Passenger last name');
            $table->string('passenger_middlename', 100)->nullable()->comment('Passenger middle name');
            $table->date('passenger_birth_date')->nullable()->comment('Passenger birth date');
            $table->unsignedTinyInteger('passenger_doc_type')->nullable()->comment('Document type');
            $table->string('passenger_doc_number', 50)->nullable()->comment('Document number');
            $table->char('passenger_gender', 1)->nullable()->comment('Passenger gender');
            
            // Ticket details
            $table->string('seat_number', 20)->nullable()->comment('Seat assignment');
            $table->decimal('price', 10, 2)->nullable()->comment('Ticket price');
            $table->decimal('provision', 10, 2)->nullable()->comment('Agent provision');
            $table->string('currency', 3)->default('EUR')->comment('Currency code');
            
            // Route information
            $table->string('route_from', 100)->nullable()->comment('Departure city');
            $table->string('route_to', 100)->nullable()->comment('Arrival city');
            $table->date('departure_date')->nullable()->comment('Departure date');
            $table->time('departure_time')->nullable()->comment('Departure time');
            $table->date('arrival_date')->nullable()->comment('Arrival date');
            $table->time('arrival_time')->nullable()->comment('Arrival time');
            $table->string('carrier', 100)->nullable()->comment('Transport carrier');
            
            // Status and links
            $table->string('status', 50)->default('reserve')->comment('Ticket status');
            $table->text('pdf_link')->nullable()->comment('PDF download link');
            $table->json('api_response')->nullable()->comment('Full API response');
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['passenger_name', 'passenger_surname']);
            $table->index(['departure_date']);
            $table->index(['route_from', 'route_to']);
            $table->index(['created_at']);
            $table->index(['order_id']);
            
            $table->foreign('order_id')->references('id')->on('bussystem_orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bussystem_tickets');
    }
};