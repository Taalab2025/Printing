<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\QuoteRequest;
use App\Models\Quote;
use App\Models\Role;

class QuoteManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test customer can submit quote request.
     */
    public function test_customer_can_submit_quote_request(): void
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
        
        // Test customer can submit quote request
        $response = $this->actingAs($customerUser)->post('/quote-requests', [
            'service_id' => $service->id,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14)->format('Y-m-d'),
            'delivery_address' => '123 Test Street, Test City',
            'additional_notes' => 'Please include a proof before printing',
        ]);
        
        $response->assertRedirect('/quote-requests');
        $this->assertDatabaseHas('quote_requests', [
            'user_id' => $customerUser->id,
            'service_id' => $service->id,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'status' => 'pending',
        ]);
    }

    /**
     * Test vendor can respond to quote request.
     */
    public function test_vendor_can_respond_to_quote_request(): void
    {
        // Create roles
        $vendorRole = Role::create(['name' => 'vendor', 'display_name' => 'Vendor']);
        
        // Create vendor user
        $vendorUser = User::factory()->create();
        $vendorUser->roles()->attach($vendorRole);
        
        // Create quote request
        $quoteRequest = QuoteRequest::create([
            'user_id' => 2,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        // Test vendor can respond to quote request
        $response = $this->actingAs($vendorUser)->post('/vendor/quotes', [
            'quote_request_id' => $quoteRequest->id,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30)->format('Y-m-d'),
            'notes' => 'Price includes delivery',
        ]);
        
        $response->assertRedirect('/vendor/quotes');
        $this->assertDatabaseHas('quotes', [
            'quote_request_id' => $quoteRequest->id,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'notes' => 'Price includes delivery',
            'status' => 'pending',
        ]);
    }

    /**
     * Test customer can view quote requests.
     */
    public function test_customer_can_view_quote_requests(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create quote requests for customer
        $quoteRequest1 = QuoteRequest::create([
            'user_id' => $customerUser->id,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        $quoteRequest2 = QuoteRequest::create([
            'user_id' => $customerUser->id,
            'service_id' => 2,
            'quantity' => 1000,
            'specifications' => 'Single-sided, black and white, 250gsm paper',
            'delivery_date' => now()->addDays(10),
            'status' => 'approved',
        ]);
        
        // Test customer can view their quote requests
        $response = $this->actingAs($customerUser)->get('/quote-requests');
        $response->assertStatus(200);
        $response->assertSee('Double-sided, full color, 350gsm paper');
        $response->assertSee('Single-sided, black and white, 250gsm paper');
    }

    /**
     * Test customer can accept quote.
     */
    public function test_customer_can_accept_quote(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create quote request
        $quoteRequest = QuoteRequest::create([
            'user_id' => $customerUser->id,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        // Create quote
        $quote = Quote::create([
            'quote_request_id' => $quoteRequest->id,
            'vendor_id' => 1,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30),
            'status' => 'pending',
        ]);
        
        // Test customer can accept quote
        $response = $this->actingAs($customerUser)->post('/quotes/' . $quote->id . '/accept');
        
        $response->assertRedirect('/orders/create?quote_id=' . $quote->id);
        $this->assertDatabaseHas('quotes', [
            'id' => $quote->id,
            'status' => 'accepted',
        ]);
    }

    /**
     * Test customer can reject quote.
     */
    public function test_customer_can_reject_quote(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create quote request
        $quoteRequest = QuoteRequest::create([
            'user_id' => $customerUser->id,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        // Create quote
        $quote = Quote::create([
            'quote_request_id' => $quoteRequest->id,
            'vendor_id' => 1,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30),
            'status' => 'pending',
        ]);
        
        // Test customer can reject quote
        $response = $this->actingAs($customerUser)->post('/quotes/' . $quote->id . '/reject', [
            'rejection_reason' => 'Price is too high',
        ]);
        
        $response->assertRedirect('/quote-requests');
        $this->assertDatabaseHas('quotes', [
            'id' => $quote->id,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test customer cannot access another customer's quotes.
     */
    public function test_customer_cannot_access_another_customers_quotes(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer users
        $customerUser1 = User::factory()->create();
        $customerUser1->roles()->attach($customerRole);
        
        $customerUser2 = User::factory()->create();
        $customerUser2->roles()->attach($customerRole);
        
        // Create quote request for customer 1
        $quoteRequest = QuoteRequest::create([
            'user_id' => $customerUser1->id,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        // Create quote
        $quote = Quote::create([
            'quote_request_id' => $quoteRequest->id,
            'vendor_id' => 1,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30),
            'status' => 'pending',
        ]);
        
        // Test customer 2 cannot access customer 1's quote
        $response = $this->actingAs($customerUser2)->get('/quotes/' . $quote->id);
        $response->assertStatus(403);
        
        // Test customer 2 cannot accept customer 1's quote
        $response = $this->actingAs($customerUser2)->post('/quotes/' . $quote->id . '/accept');
        $response->assertStatus(403);
    }
}
