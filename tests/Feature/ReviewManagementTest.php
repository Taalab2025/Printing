<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Vendor;
use App\Models\Role;
use App\Models\VendorResponse;

class ReviewManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test customer can submit review for completed order.
     */
    public function test_customer_can_submit_review_for_completed_order(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create vendor
        $vendor = Vendor::create([
            'user_id' => 2,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create completed order for customer
        $order = Order::create([
            'user_id' => $customerUser->id,
            'service_id' => 1,
            'vendor_id' => $vendor->id,
            'order_number' => 'ORD-001',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'completed',
        ]);
        
        // Test customer can submit review
        $response = $this->actingAs($customerUser)->post('/orders/' . $order->id . '/reviews', [
            'rating' => 4,
            'title' => 'Great service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
        ]);
        
        $response->assertRedirect('/orders/' . $order->id);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $customerUser->id,
            'order_id' => $order->id,
            'vendor_id' => $vendor->id,
            'rating' => 4,
            'title' => 'Great service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
            'status' => 'pending',
        ]);
    }

    /**
     * Test customer cannot submit review for incomplete order.
     */
    public function test_customer_cannot_submit_review_for_incomplete_order(): void
    {
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create customer user
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Create vendor
        $vendor = Vendor::create([
            'user_id' => 2,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create processing order for customer
        $order = Order::create([
            'user_id' => $customerUser->id,
            'service_id' => 1,
            'vendor_id' => $vendor->id,
            'order_number' => 'ORD-001',
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'processing',
        ]);
        
        // Test customer cannot submit review for incomplete order
        $response = $this->actingAs($customerUser)->post('/orders/' . $order->id . '/reviews', [
            'rating' => 4,
            'title' => 'Great service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
        ]);
        
        $response->assertStatus(403);
        $this->assertDatabaseMissing('reviews', [
            'order_id' => $order->id,
        ]);
    }

    /**
     * Test vendor can respond to review.
     */
    public function test_vendor_can_respond_to_review(): void
    {
        // Create roles
        $vendorRole = Role::create(['name' => 'vendor', 'display_name' => 'Vendor']);
        
        // Create vendor user
        $vendorUser = User::factory()->create();
        $vendorUser->roles()->attach($vendorRole);
        
        // Create vendor
        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create review for vendor
        $review = Review::create([
            'user_id' => 2,
            'order_id' => 1,
            'vendor_id' => $vendor->id,
            'rating' => 4,
            'title' => 'Great service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
            'status' => 'approved',
        ]);
        
        // Test vendor can respond to review
        $response = $this->actingAs($vendorUser)->post('/vendor/reviews/' . $review->id . '/respond', [
            'response' => 'Thank you for your feedback! We appreciate your business.',
        ]);
        
        $response->assertRedirect('/vendor/reviews');
        $this->assertDatabaseHas('vendor_responses', [
            'review_id' => $review->id,
            'vendor_id' => $vendor->id,
            'response' => 'Thank you for your feedback! We appreciate your business.',
        ]);
    }

    /**
     * Test admin can moderate reviews.
     */
    public function test_admin_can_moderate_reviews(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        
        // Create admin user
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole);
        
        // Create pending review
        $review = Review::create([
            'user_id' => 2,
            'order_id' => 1,
            'vendor_id' => 1,
            'rating' => 4,
            'title' => 'Great service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
            'status' => 'pending',
        ]);
        
        // Test admin can approve review
        $response = $this->actingAs($adminUser)->post('/admin/reviews/' . $review->id . '/approve');
        
        $response->assertRedirect('/admin/reviews');
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'approved',
        ]);
        
        // Create another pending review
        $review2 = Review::create([
            'user_id' => 3,
            'order_id' => 2,
            'vendor_id' => 1,
            'rating' => 1,
            'title' => 'Poor service',
            'comment' => 'Inappropriate content that violates terms.',
            'status' => 'pending',
        ]);
        
        // Test admin can reject review
        $response = $this->actingAs($adminUser)->post('/admin/reviews/' . $review2->id . '/reject', [
            'rejection_reason' => 'Violates terms of service',
        ]);
        
        $response->assertRedirect('/admin/reviews');
        $this->assertDatabaseHas('reviews', [
            'id' => $review2->id,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test customer can view vendor reviews.
     */
    public function test_customer_can_view_vendor_reviews(): void
    {
        // Create vendor
        $vendor = Vendor::create([
            'user_id' => 1,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create approved reviews for vendor
        $review1 = Review::create([
            'user_id' => 2,
            'order_id' => 1,
            'vendor_id' => $vendor->id,
            'rating' => 5,
            'title' => 'Excellent service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
            'status' => 'approved',
        ]);
        
        $review2 = Review::create([
            'user_id' => 3,
            'order_id' => 2,
            'vendor_id' => $vendor->id,
            'rating' => 4,
            'title' => 'Good service',
            'comment' => 'Good quality printing but delivery was a bit late.',
            'status' => 'approved',
        ]);
        
        // Create vendor response
        VendorResponse::create([
            'review_id' => $review2->id,
            'vendor_id' => $vendor->id,
            'response' => 'Thank you for your feedback. We apologize for the delay in delivery.',
        ]);
        
        // Test public can view vendor reviews
        $response = $this->get('/vendors/' . $vendor->id . '/reviews');
        $response->assertStatus(200);
        $response->assertSee('Excellent service');
        $response->assertSee('Good service');
        $response->assertSee('Thank you for your feedback');
        
        // Verify rejected reviews are not shown
        $review3 = Review::create([
            'user_id' => 4,
            'order_id' => 3,
            'vendor_id' => $vendor->id,
            'rating' => 1,
            'title' => 'Poor service',
            'comment' => 'Rejected review',
            'status' => 'rejected',
        ]);
        
        $response = $this->get('/vendors/' . $vendor->id . '/reviews');
        $response->assertDontSee('Poor service');
        $response->assertDontSee('Rejected review');
    }
}
