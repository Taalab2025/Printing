<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Quote;
use App\Models\Role;
use App\Models\OrderProof;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test customer can place order from accepted quote.
     */
    public function test_customer_can_place_order_from_accepted_quote(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create service
        $service = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        // Create quote
        $quote = Quote::create([
            'quote_request_id' => 1,
            'vendor_id' => 1,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30),
            'status' => 'accepted',
        ]);
        
        // Test customer can place order
        $response = $this->actingAs($customerUser)->post('/orders', [
            'service_id' => $service->id,
            'quote_id' => $quote->id,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7)->format('Y-m-d'),
            'payment_method' => 'credit_card',
        ]);
        
        $response->assertRedirect('/orders');
        $this->assertDatabaseHas('orders', [
            'user_id' => $customerUser->id,
            'service_id' => $service->id,
            'quote_id' => $quote->id,
            'quantity' => 500,
            'price' => 450.00,
            'payment_method' => 'credit_card',
            'status' => 'pending',
        ]);
    }

    /**
     * Test customer can view orders.
     */
    public function test_customer_can_view_orders(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create orders for customer
        $order1 = Order::create([
            'user_id' => $customerUser->id,
            'service_id' => 1,
            'order_number' => 'ORD-001',
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
        
        $order2 = Order::create([
            'user_id' => $customerUser->id,
            'service_id' => 2,
            'order_number' => 'ORD-002',
            'quantity' => 1000,
            'specifications' => 'Single-sided, black and white, 250gsm paper',
            'price' => 350.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(10),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);
        
        // Test customer can view their orders
        $response = $this->actingAs($customerUser)->get('/orders');
        $response->assertStatus(200);
        $response->assertSee('ORD-001');
        $response->assertSee('ORD-002');
    }

    /**
     * Test customer can view order details.
     */
    public function test_customer_can_view_order_details(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create order for customer
        $order = Order::create([
            'user_id' => $customerUser->id,
            'service_id' => 1,
            'order_number' => 'ORD-001',
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
        
        // Test customer can view order details
        $response = $this->actingAs($customerUser)->get('/orders/' . $order->id);
        $response->assertStatus(200);
        $response->assertSee('ORD-001');
        $response->assertSee('Double-sided, full color, 350gsm paper');
        $response->assertSee('450.00');
        $response->assertSee('processing');
    }

    /**
     * Test vendor can view and manage orders.
     */
    public function test_vendor_can_view_and_manage_orders(): void
    {
        // Create roles
        $vendorRole = Role::create(['name' => 'vendor', 'display_name' => 'Vendor']);
        
        // Create vendor user
        $vendorUser = User::factory()->create();
        $vendorUser->roles()->attach($vendorRole);
        
        // Create order for vendor's service
        $order = Order::create([
            'user_id' => 2,
            'service_id' => 1,
            'vendor_id' => 1,
            'order_number' => 'ORD-001',
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'pending',
        ]);
        
        // Test vendor can view orders
        $response = $this->actingAs($vendorUser)->get('/vendor/orders');
        $response->assertStatus(200);
        $response->assertSee('ORD-001');
        
        // Test vendor can update order status
        $response = $this->actingAs($vendorUser)->put('/vendor/orders/' . $order->id, [
            'status' => 'processing',
            'notes' => 'Order is now being processed',
        ]);
        
        $response->assertRedirect('/vendor/orders');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
        
        // Test vendor can add order proof
        $response = $this->actingAs($vendorUser)->post('/vendor/orders/' . $order->id . '/proofs', [
            'file_path' => 'proofs/order-proof-1.pdf',
            'version' => 1,
            'notes' => 'Initial proof for review',
        ]);
        
        $response->assertRedirect('/vendor/orders/' . $order->id);
        $this->assertDatabaseHas('order_proofs', [
            'order_id' => $order->id,
            'file_path' => 'proofs/order-proof-1.pdf',
            'version' => 1,
            'notes' => 'Initial proof for review',
            'status' => 'pending',
        ]);
    }

    /**
     * Test customer can approve or request changes to proof.
     */
    public function test_customer_can_approve_or_request_changes_to_proof(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create order for customer
        $order = Order::create([
            'user_id' => $customerUser->id,
            'service_id' => 1,
            'order_number' => 'ORD-001',
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
        
        // Create proof for order
        $proof = OrderProof::create([
            'order_id' => $order->id,
            'file_path' => 'proofs/order-proof-1.pdf',
            'version' => 1,
            'notes' => 'Initial proof for review',
            'status' => 'pending',
        ]);
        
        // Test customer can approve proof
        $response = $this->actingAs($customerUser)->post('/orders/' . $order->id . '/proofs/' . $proof->id . '/approve');
        
        $response->assertRedirect('/orders/' . $order->id);
        $this->assertDatabaseHas('order_proofs', [
            'id' => $proof->id,
            'status' => 'approved',
        ]);
        
        // Create another proof for testing changes request
        $proof2 = OrderProof::create([
            'order_id' => $order->id,
            'file_path' => 'proofs/order-proof-2.pdf',
            'version' => 2,
            'notes' => 'Second proof for review',
            'status' => 'pending',
        ]);
        
        // Test customer can request changes to proof
        $response = $this->actingAs($customerUser)->post('/orders/' . $order->id . '/proofs/' . $proof2->id . '/request-changes', [
            'feedback' => 'Please adjust the logo size and change the font color',
        ]);
        
        $response->assertRedirect('/orders/' . $order->id);
        $this->assertDatabaseHas('order_proofs', [
            'id' => $proof2->id,
            'status' => 'changes_requested',
        ]);
    }

    /**
     * Test customer cannot access another customer's orders.
     */
    public function test_customer_cannot_access_another_customers_orders(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer users
        $customerUser1 = User::factory()->create();
        $customerUser1->roles()->attach($customerRole);
        
        $customerUser2 = User::factory()->create();
        $customerUser2->roles()->attach($customerRole);
        
        // Create order for customer 1
        $order = Order::create([
            'user_id' => $customerUser1->id,
            'service_id' => 1,
            'order_number' => 'ORD-001',
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'price' => 450.00,
            'delivery_address' => '123 Test Street, Test City',
            'delivery_date' => now()->addDays(7),
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
        
        // Test customer 2 cannot access customer 1's order
        $response = $this->actingAs($customerUser2)->get('/orders/' . $order->id);
        $response->assertStatus(403);
    }
}
