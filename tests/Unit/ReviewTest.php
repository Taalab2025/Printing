<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Review;
use App\Models\User;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\VendorResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test review creation.
     */
    public function test_can_create_review(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'service_id' => 1,
            'order_number' => 'ORD-' . date('YmdHis'),
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'completed',
        ]);
        
        $reviewData = [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'vendor_id' => 1,
            'rating' => 4,
            'title' => 'Great service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
            'status' => 'approved',
        ];

        $review = Review::create($reviewData);

        $this->assertInstanceOf(Review::class, $review);
        $this->assertEquals($user->id, $review->user_id);
        $this->assertEquals($order->id, $review->order_id);
        $this->assertEquals(4, $review->rating);
        $this->assertEquals('Great service', $review->title);
        $this->assertEquals('approved', $review->status);
    }

    /**
     * Test review relationships.
     */
    public function test_review_relationships(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::create([
            'user_id' => 2,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'service_id' => 1,
            'order_number' => 'ORD-' . date('YmdHis'),
            'quantity' => 500,
            'price' => 450.00,
            'status' => 'completed',
        ]);
        
        $review = Review::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'vendor_id' => $vendor->id,
            'rating' => 4,
            'title' => 'Great service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
            'status' => 'approved',
        ]);
        
        // Test user relationship
        $this->assertInstanceOf(User::class, $review->user);
        $this->assertEquals($user->id, $review->user->id);
        
        // Test order relationship
        $this->assertInstanceOf(Order::class, $review->order);
        $this->assertEquals($order->id, $review->order->id);
        
        // Test vendor relationship
        $this->assertInstanceOf(Vendor::class, $review->vendor);
        $this->assertEquals($vendor->id, $review->vendor->id);
    }

    /**
     * Test vendor response relationship.
     */
    public function test_review_has_vendor_response(): void
    {
        $review = Review::create([
            'user_id' => 1,
            'order_id' => 1,
            'vendor_id' => 1,
            'rating' => 4,
            'title' => 'Great service',
            'comment' => 'The printing quality was excellent and delivery was on time.',
            'status' => 'approved',
        ]);
        
        $vendorResponse = VendorResponse::create([
            'review_id' => $review->id,
            'vendor_id' => 1,
            'response' => 'Thank you for your feedback! We appreciate your business.',
        ]);
        
        // Test relationship
        $this->assertInstanceOf(VendorResponse::class, $review->vendorResponse);
        $this->assertEquals($vendorResponse->id, $review->vendorResponse->id);
        $this->assertEquals('Thank you for your feedback! We appreciate your business.', $review->vendorResponse->response);
    }

    /**
     * Test review status scopes.
     */
    public function test_review_status_scopes(): void
    {
        // Create reviews with different statuses
        $pendingReview = Review::create([
            'user_id' => 1,
            'order_id' => 1,
            'vendor_id' => 1,
            'rating' => 4,
            'title' => 'Pending Review',
            'comment' => 'Pending review comment',
            'status' => 'pending',
        ]);
        
        $approvedReview = Review::create([
            'user_id' => 1,
            'order_id' => 2,
            'vendor_id' => 1,
            'rating' => 5,
            'title' => 'Approved Review',
            'comment' => 'Approved review comment',
            'status' => 'approved',
        ]);
        
        $rejectedReview = Review::create([
            'user_id' => 1,
            'order_id' => 3,
            'vendor_id' => 1,
            'rating' => 2,
            'title' => 'Rejected Review',
            'comment' => 'Rejected review comment',
            'status' => 'rejected',
        ]);
        
        // Test status scopes
        $this->assertEquals(1, Review::pending()->count());
        $this->assertEquals(1, Review::approved()->count());
        $this->assertEquals(1, Review::rejected()->count());
        
        $this->assertTrue(Review::pending()->get()->contains($pendingReview));
        $this->assertTrue(Review::approved()->get()->contains($approvedReview));
        $this->assertTrue(Review::rejected()->get()->contains($rejectedReview));
    }

    /**
     * Test review rating scopes.
     */
    public function test_review_rating_scopes(): void
    {
        // Create reviews with different ratings
        $lowRatingReview = Review::create([
            'user_id' => 1,
            'order_id' => 1,
            'vendor_id' => 1,
            'rating' => 2,
            'title' => 'Low Rating Review',
            'comment' => 'Low rating review comment',
            'status' => 'approved',
        ]);
        
        $mediumRatingReview = Review::create([
            'user_id' => 1,
            'order_id' => 2,
            'vendor_id' => 1,
            'rating' => 3,
            'title' => 'Medium Rating Review',
            'comment' => 'Medium rating review comment',
            'status' => 'approved',
        ]);
        
        $highRatingReview = Review::create([
            'user_id' => 1,
            'order_id' => 3,
            'vendor_id' => 1,
            'rating' => 5,
            'title' => 'High Rating Review',
            'comment' => 'High rating review comment',
            'status' => 'approved',
        ]);
        
        // Test rating filters
        $this->assertEquals(1, Review::where('rating', '<=', 2)->count());
        $this->assertEquals(2, Review::where('rating', '>=', 3)->count());
        $this->assertEquals(1, Review::where('rating', '=', 5)->count());
    }

    /**
     * Test average rating calculation.
     */
    public function test_vendor_average_rating_calculation(): void
    {
        $vendor = Vendor::create([
            'user_id' => 1,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create reviews for the vendor
        Review::create([
            'user_id' => 1,
            'order_id' => 1,
            'vendor_id' => $vendor->id,
            'rating' => 4,
            'title' => 'Good service',
            'comment' => 'Good service comment',
            'status' => 'approved',
        ]);
        
        Review::create([
            'user_id' => 2,
            'order_id' => 2,
            'vendor_id' => $vendor->id,
            'rating' => 5,
            'title' => 'Excellent service',
            'comment' => 'Excellent service comment',
            'status' => 'approved',
        ]);
        
        Review::create([
            'user_id' => 3,
            'order_id' => 3,
            'vendor_id' => $vendor->id,
            'rating' => 3,
            'title' => 'Average service',
            'comment' => 'Average service comment',
            'status' => 'approved',
        ]);
        
        // Calculate average rating
        $averageRating = Review::where('vendor_id', $vendor->id)
            ->where('status', 'approved')
            ->avg('rating');
        
        $this->assertEquals(4.0, $averageRating);
    }
}
