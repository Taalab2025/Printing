<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use App\Models\Quote;
use App\Models\OrderProof;
use App\Models\OrderStatusHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test order creation.
     */
    public function test_can_create_order(): void
    {
        $user = User::factory()->create();
        $service = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Test Service',
            'slug' => 'test-service',
            'description' => 'Test service description',
            'base_price' => 100.00,
            'status' => 'active',
        ]);
        
        $quote = Quote::create([
            'quote_request_id' => 1,
            'vendor_id' => 1,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30),
            'status' => 'accepted',
        ]);
        
        $orderData = [
            'user_id' => $user->id,
            'service_id' => $service->id,
            'quote_id' => $quote->id,
            'order_number' => 'ORD-' . date('YmdHis'),
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'processing',
        ];

        $order = Order::create($orderData);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($service->id, $order->service_id);
        $this->assertEquals($quote->id, $order->quote_id);
        $this->assertEquals(450.00, $order->price);
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('processing', $order->status);
    }

    /**
     * Test order relationships.
     */
    public function test_order_relationships(): void
    {
        $user = User::factory()->create();
        $service = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Test Service',
            'slug' => 'test-service',
            'description' => 'Test service description',
            'base_price' => 100.00,
            'status' => 'active',
        ]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'order_number' => 'ORD-' . date('YmdHis'),
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
        
        // Test user relationship
        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
        
        // Test service relationship
        $this->assertInstanceOf(Service::class, $order->service);
        $this->assertEquals($service->id, $order->service->id);
    }

    /**
     * Test order proofs relationship.
     */
    public function test_order_has_many_proofs(): void
    {
        $order = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-' . date('YmdHis'),
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
        
        // Create order proofs
        $proof1 = OrderProof::create([
            'order_id' => $order->id,
            'file_path' => 'proofs/order-proof-1.pdf',
            'version' => 1,
            'notes' => 'Initial proof',
            'status' => 'pending',
        ]);
        
        $proof2 = OrderProof::create([
            'order_id' => $order->id,
            'file_path' => 'proofs/order-proof-2.pdf',
            'version' => 2,
            'notes' => 'Revised proof',
            'status' => 'approved',
        ]);
        
        // Test relationship
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $order->proofs);
        $this->assertEquals(2, $order->proofs->count());
        $this->assertTrue($order->proofs->contains($proof1));
        $this->assertTrue($order->proofs->contains($proof2));
    }

    /**
     * Test order status history relationship.
     */
    public function test_order_has_many_status_history(): void
    {
        $order = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-' . date('YmdHis'),
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
        
        // Create order status history
        $history1 = OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'notes' => 'Order created',
            'user_id' => 1,
        ]);
        
        $history2 = OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'processing',
            'notes' => 'Order processing started',
            'user_id' => 2,
        ]);
        
        // Test relationship
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $order->statusHistory);
        $this->assertEquals(2, $order->statusHistory->count());
        $this->assertTrue($order->statusHistory->contains($history1));
        $this->assertTrue($order->statusHistory->contains($history2));
    }

    /**
     * Test order status scopes.
     */
    public function test_order_status_scopes(): void
    {
        // Create orders with different statuses
        $pendingOrder = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-PENDING',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'pending',
        ]);
        
        $processingOrder = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-PROCESSING',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'processing',
        ]);
        
        $completedOrder = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-COMPLETED',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'completed',
        ]);
        
        $cancelledOrder = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-CANCELLED',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'cancelled',
        ]);
        
        // Test status scopes
        $this->assertEquals(1, Order::pending()->count());
        $this->assertEquals(1, Order::processing()->count());
        $this->assertEquals(1, Order::completed()->count());
        $this->assertEquals(1, Order::cancelled()->count());
        
        $this->assertTrue(Order::pending()->get()->contains($pendingOrder));
        $this->assertTrue(Order::processing()->get()->contains($processingOrder));
        $this->assertTrue(Order::completed()->get()->contains($completedOrder));
        $this->assertTrue(Order::cancelled()->get()->contains($cancelledOrder));
    }

    /**
     * Test order payment status scopes.
     */
    public function test_order_payment_status_scopes(): void
    {
        // Create orders with different payment statuses
        $pendingPaymentOrder = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-PAYMENT-PENDING',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
        
        $paidOrder = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-PAYMENT-PAID',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);
        
        $failedPaymentOrder = Order::create([
            'user_id' => 1,
            'service_id' => 1,
            'order_number' => 'ORD-PAYMENT-FAILED',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'cancelled',
            'payment_status' => 'failed',
        ]);
        
        // Test payment status scopes
        $this->assertEquals(1, Order::paymentPending()->count());
        $this->assertEquals(1, Order::paid()->count());
        $this->assertEquals(1, Order::paymentFailed()->count());
        
        $this->assertTrue(Order::paymentPending()->get()->contains($pendingPaymentOrder));
        $this->assertTrue(Order::paid()->get()->contains($paidOrder));
        $this->assertTrue(Order::paymentFailed()->get()->contains($failedPaymentOrder));
    }
}
