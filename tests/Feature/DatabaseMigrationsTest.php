<?php

declare(strict_types=1);

namespace Nikba\LaravelBussystemApi\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Nikba\LaravelBussystemApi\Models\Order;
use Nikba\LaravelBussystemApi\Models\Ticket;
use Nikba\LaravelBussystemApi\Tests\TestCase;

class DatabaseMigrationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_table_exists_with_correct_columns(): void
    {
        $this->assertTrue(Schema::hasTable('bussystem_orders'));
        
        $columns = [
            'id', 'order_id', 'security_code', 'status', 'price_total',
            'currency', 'passenger_count', 'route_count', 'phone', 'email',
            'promocode', 'reservation_until', 'api_response', 'user_id',
            'created_at', 'updated_at', 'deleted_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('bussystem_orders', $column),
                "Column '{$column}' should exist in bussystem_orders table"
            );
        }
    }

    public function test_tickets_table_exists_with_correct_columns(): void
    {
        $this->assertTrue(Schema::hasTable('bussystem_tickets'));
        
        $columns = [
            'id', 'order_id', 'ticket_id', 'transaction_id', 'security_code',
            'passenger_name', 'passenger_surname', 'passenger_middlename',
            'passenger_birth_date', 'passenger_doc_type', 'passenger_doc_number',
            'passenger_gender', 'seat_number', 'price', 'provision', 'currency',
            'route_from', 'route_to', 'departure_date', 'departure_time',
            'arrival_date', 'arrival_time', 'carrier', 'status', 'pdf_link',
            'api_response', 'created_at', 'updated_at', 'deleted_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('bussystem_tickets', $column),
                "Column '{$column}' should exist in bussystem_tickets table"
            );
        }
    }

    public function test_can_create_order_record(): void
    {
        $order = Order::create([
            'order_id' => 12345,
            'security_code' => 'abc123',
            'status' => 'reserve_ok',
            'price_total' => 99.50,
            'currency' => 'EUR',
            'passenger_count' => 2,
            'route_count' => 1,
            'phone' => '+1234567890',
            'email' => 'test@example.com',
            'reservation_until' => now()->addMinutes(30),
            'api_response' => ['test' => 'data'],
        ]);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(12345, $order->order_id);
        $this->assertEquals('reserve_ok', $order->status);
        $this->assertEquals(99.50, $order->price_total);
        $this->assertTrue($order->exists);
    }

    public function test_can_create_ticket_record(): void
    {
        $order = Order::create([
            'order_id' => 12345,
            'security_code' => 'abc123',
            'status' => 'buy',
            'price_total' => 99.50,
            'currency' => 'EUR',
            'passenger_count' => 1,
            'route_count' => 1,
            'phone' => '+1234567890',
        ]);

        $ticket = Ticket::create([
            'order_id' => $order->id,
            'ticket_id' => 67890,
            'transaction_id' => 11111,
            'security_code' => 'xyz789',
            'passenger_name' => 'John',
            'passenger_surname' => 'Doe',
            'passenger_birth_date' => '1990-01-01',
            'passenger_doc_type' => 1,
            'passenger_doc_number' => 'AB123456',
            'passenger_gender' => 'M',
            'seat_number' => '1A',
            'price' => 99.50,
            'provision' => 9.95,
            'currency' => 'EUR',
            'route_from' => 'Prague',
            'route_to' => 'Vienna',
            'departure_date' => '2024-12-31',
            'departure_time' => '08:00:00',
            'arrival_date' => '2024-12-31',
            'arrival_time' => '14:00:00',
            'carrier' => 'Test Carrier',
            'status' => 'buy',
            'api_response' => ['ticket' => 'data'],
        ]);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals(67890, $ticket->ticket_id);
        $this->assertEquals('John', $ticket->passenger_name);
        $this->assertEquals('Doe', $ticket->passenger_surname);
        $this->assertTrue($ticket->exists);
    }

    public function test_order_ticket_relationship(): void
    {
        $order = Order::create([
            'order_id' => 12345,
            'security_code' => 'abc123',
            'status' => 'buy',
            'price_total' => 199.00,
            'currency' => 'EUR',
            'passenger_count' => 2,
            'route_count' => 1,
            'phone' => '+1234567890',
        ]);

        $ticket1 = Ticket::create([
            'order_id' => $order->id,
            'ticket_id' => 67890,
            'security_code' => 'xyz789',
            'passenger_name' => 'John',
            'passenger_surname' => 'Doe',
            'price' => 99.50,
            'currency' => 'EUR',
            'status' => 'buy',
        ]);

        $ticket2 = Ticket::create([
            'order_id' => $order->id,
            'ticket_id' => 67891,
            'security_code' => 'xyz790',
            'passenger_name' => 'Jane',
            'passenger_surname' => 'Doe',
            'price' => 99.50,
            'currency' => 'EUR',
            'status' => 'buy',
        ]);

        // Test relationship from order to tickets
        $this->assertCount(2, $order->tickets);
        $this->assertTrue($order->tickets->contains($ticket1));
        $this->assertTrue($order->tickets->contains($ticket2));

        // Test relationship from ticket to order
        $this->assertEquals($order->id, $ticket1->order->id);
        $this->assertEquals($order->id, $ticket2->order->id);
    }

    public function test_order_model_scopes(): void
    {
        Order::create(['order_id' => 1, 'security_code' => 'a', 'status' => 'reserve', 'currency' => 'EUR', 'passenger_count' => 1, 'route_count' => 1]);
        Order::create(['order_id' => 2, 'security_code' => 'b', 'status' => 'buy', 'currency' => 'EUR', 'passenger_count' => 1, 'route_count' => 1]);
        Order::create(['order_id' => 3, 'security_code' => 'c', 'status' => 'cancel', 'currency' => 'EUR', 'passenger_count' => 1, 'route_count' => 1]);

        $this->assertCount(2, Order::active()->get());
        $this->assertCount(1, Order::reserved()->get());
        $this->assertCount(1, Order::paid()->get());
    }

    public function test_ticket_model_scopes(): void
    {
        $order = Order::create(['order_id' => 1, 'security_code' => 'a', 'status' => 'buy', 'currency' => 'EUR', 'passenger_count' => 2, 'route_count' => 1]);

        Ticket::create(['order_id' => $order->id, 'ticket_id' => 1, 'security_code' => 'a', 'passenger_name' => 'John', 'passenger_surname' => 'Doe', 'status' => 'reserve', 'currency' => 'EUR']);
        Ticket::create(['order_id' => $order->id, 'ticket_id' => 2, 'security_code' => 'b', 'passenger_name' => 'Jane', 'passenger_surname' => 'Doe', 'status' => 'buy', 'currency' => 'EUR']);
        Ticket::create(['order_id' => $order->id, 'ticket_id' => 3, 'security_code' => 'c', 'passenger_name' => 'Bob', 'passenger_surname' => 'Smith', 'status' => 'cancel', 'currency' => 'EUR']);

        $this->assertCount(2, Ticket::active()->get());
        $this->assertCount(1, Ticket::reserved()->get());
        $this->assertCount(1, Ticket::paid()->get());
    }

    public function test_order_model_helper_methods(): void
    {
        $reservedOrder = Order::create(['order_id' => 1, 'security_code' => 'a', 'status' => 'reserve', 'currency' => 'EUR', 'passenger_count' => 1, 'route_count' => 1]);
        $paidOrder = Order::create(['order_id' => 2, 'security_code' => 'b', 'status' => 'buy', 'currency' => 'EUR', 'passenger_count' => 1, 'route_count' => 1]);
        $cancelledOrder = Order::create(['order_id' => 3, 'security_code' => 'c', 'status' => 'cancel', 'currency' => 'EUR', 'passenger_count' => 1, 'route_count' => 1]);

        $this->assertTrue($reservedOrder->isReserved());
        $this->assertFalse($reservedOrder->isPaid());
        $this->assertFalse($reservedOrder->isCancelled());

        $this->assertFalse($paidOrder->isReserved());
        $this->assertTrue($paidOrder->isPaid());
        $this->assertFalse($paidOrder->isCancelled());

        $this->assertFalse($cancelledOrder->isReserved());
        $this->assertFalse($cancelledOrder->isPaid());
        $this->assertTrue($cancelledOrder->isCancelled());
    }

    public function test_ticket_model_helper_methods(): void
    {
        $order = Order::create(['order_id' => 1, 'security_code' => 'a', 'status' => 'buy', 'currency' => 'EUR', 'passenger_count' => 1, 'route_count' => 1]);

        $reservedTicket = Ticket::create(['order_id' => $order->id, 'ticket_id' => 1, 'security_code' => 'a', 'passenger_name' => 'John', 'passenger_surname' => 'Doe', 'status' => 'reserve', 'currency' => 'EUR']);
        $paidTicket = Ticket::create(['order_id' => $order->id, 'ticket_id' => 2, 'security_code' => 'b', 'passenger_name' => 'Jane', 'passenger_surname' => 'Doe', 'status' => 'buy', 'currency' => 'EUR']);

        $this->assertTrue($reservedTicket->isReserved());
        $this->assertFalse($reservedTicket->isPaid());
        $this->assertFalse($reservedTicket->isCancelled());

        $this->assertFalse($paidTicket->isReserved());
        $this->assertTrue($paidTicket->isPaid());
        $this->assertFalse($paidTicket->isCancelled());
    }

    public function test_soft_deletes_work(): void
    {
        $order = Order::create(['order_id' => 1, 'security_code' => 'a', 'status' => 'buy', 'currency' => 'EUR', 'passenger_count' => 1, 'route_count' => 1]);
        $ticket = Ticket::create(['order_id' => $order->id, 'ticket_id' => 1, 'security_code' => 'a', 'passenger_name' => 'John', 'passenger_surname' => 'Doe', 'status' => 'buy', 'currency' => 'EUR']);

        // Soft delete
        $order->delete();
        $ticket->delete();

        // Should not appear in regular queries
        $this->assertCount(0, Order::all());
        $this->assertCount(0, Ticket::all());

        // Should appear in withTrashed queries
        $this->assertCount(1, Order::withTrashed()->get());
        $this->assertCount(1, Ticket::withTrashed()->get());

        // Test trashed methods
        $this->assertTrue($order->fresh()->trashed());
        $this->assertTrue($ticket->fresh()->trashed());
    }
}