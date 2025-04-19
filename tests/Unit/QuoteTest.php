<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\QuoteRequest;
use App\Models\Quote;
use App\Models\User;
use App\Models\Service;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test quote request creation.
     */
    public function test_can_create_quote_request(): void
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
        
        $quoteRequestData = [
            'user_id' => $user->id,
            'service_id' => $service->id,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'delivery_address' => '123 Test Street, Test City',
            'additional_notes' => 'Please include a proof before printing',
            'status' => 'pending',
        ];

        $quoteRequest = QuoteRequest::create($quoteRequestData);

        $this->assertInstanceOf(QuoteRequest::class, $quoteRequest);
        $this->assertEquals($user->id, $quoteRequest->user_id);
        $this->assertEquals($service->id, $quoteRequest->service_id);
        $this->assertEquals(500, $quoteRequest->quantity);
        $this->assertEquals('pending', $quoteRequest->status);
    }

    /**
     * Test quote creation.
     */
    public function test_can_create_quote(): void
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
        
        $quoteRequest = QuoteRequest::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        $quoteData = [
            'quote_request_id' => $quoteRequest->id,
            'vendor_id' => 1,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30),
            'notes' => 'Price includes delivery',
            'status' => 'pending',
        ];

        $quote = Quote::create($quoteData);

        $this->assertInstanceOf(Quote::class, $quote);
        $this->assertEquals($quoteRequest->id, $quote->quote_request_id);
        $this->assertEquals(450.00, $quote->price);
        $this->assertEquals('7 days', $quote->turnaround_time);
        $this->assertEquals('pending', $quote->status);
    }

    /**
     * Test quote request relationships.
     */
    public function test_quote_request_relationships(): void
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
        
        $quoteRequest = QuoteRequest::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        // Test user relationship
        $this->assertInstanceOf(User::class, $quoteRequest->user);
        $this->assertEquals($user->id, $quoteRequest->user->id);
        
        // Test service relationship
        $this->assertInstanceOf(Service::class, $quoteRequest->service);
        $this->assertEquals($service->id, $quoteRequest->service->id);
    }

    /**
     * Test quote relationships.
     */
    public function test_quote_relationships(): void
    {
        $vendor = Vendor::create([
            'user_id' => 1,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        $quoteRequest = QuoteRequest::create([
            'user_id' => 1,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Double-sided, full color, 350gsm paper',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        $quote = Quote::create([
            'quote_request_id' => $quoteRequest->id,
            'vendor_id' => $vendor->id,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30),
            'status' => 'pending',
        ]);
        
        // Test quote request relationship
        $this->assertInstanceOf(QuoteRequest::class, $quote->quoteRequest);
        $this->assertEquals($quoteRequest->id, $quote->quoteRequest->id);
        
        // Test vendor relationship
        $this->assertInstanceOf(Vendor::class, $quote->vendor);
        $this->assertEquals($vendor->id, $quote->vendor->id);
    }

    /**
     * Test quote request status scopes.
     */
    public function test_quote_request_status_scopes(): void
    {
        // Create quote requests with different statuses
        $pendingQuoteRequest = QuoteRequest::create([
            'user_id' => 1,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Pending quote request',
            'delivery_date' => now()->addDays(14),
            'status' => 'pending',
        ]);
        
        $approvedQuoteRequest = QuoteRequest::create([
            'user_id' => 1,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Approved quote request',
            'delivery_date' => now()->addDays(14),
            'status' => 'approved',
        ]);
        
        $rejectedQuoteRequest = QuoteRequest::create([
            'user_id' => 1,
            'service_id' => 1,
            'quantity' => 500,
            'specifications' => 'Rejected quote request',
            'delivery_date' => now()->addDays(14),
            'status' => 'rejected',
        ]);
        
        // Test status scopes
        $this->assertEquals(1, QuoteRequest::pending()->count());
        $this->assertEquals(1, QuoteRequest::approved()->count());
        $this->assertEquals(1, QuoteRequest::rejected()->count());
        
        $this->assertTrue(QuoteRequest::pending()->get()->contains($pendingQuoteRequest));
        $this->assertTrue(QuoteRequest::approved()->get()->contains($approvedQuoteRequest));
        $this->assertTrue(QuoteRequest::rejected()->get()->contains($rejectedQuoteRequest));
    }

    /**
     * Test quote status scopes.
     */
    public function test_quote_status_scopes(): void
    {
        // Create quotes with different statuses
        $pendingQuote = Quote::create([
            'quote_request_id' => 1,
            'vendor_id' => 1,
            'price' => 450.00,
            'turnaround_time' => '7 days',
            'valid_until' => now()->addDays(30),
            'status' => 'pending',
        ]);
        
        $acceptedQuote = Quote::create([
            'quote_request_id' => 2,
            'vendor_id' => 1,
            'price' => 550.00,
            'turnaround_time' => '5 days',
            'valid_until' => now()->addDays(30),
            'status' => 'accepted',
        ]);
        
        $rejectedQuote = Quote::create([
            'quote_request_id' => 3,
            'vendor_id' => 1,
            'price' => 650.00,
            'turnaround_time' => '3 days',
            'valid_until' => now()->addDays(30),
            'status' => 'rejected',
        ]);
        
        // Test status scopes
        $this->assertEquals(1, Quote::pending()->count());
        $this->assertEquals(1, Quote::accepted()->count());
        $this->assertEquals(1, Quote::rejected()->count());
        
        $this->assertTrue(Quote::pending()->get()->contains($pendingQuote));
        $this->assertTrue(Quote::accepted()->get()->contains($acceptedQuote));
        $this->assertTrue(Quote::rejected()->get()->contains($rejectedQuote));
    }
}
