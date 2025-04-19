<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Service;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\ServiceMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test service creation.
     */
    public function test_can_create_service(): void
    {
        $vendor = Vendor::create([
            'user_id' => 1,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        $category = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        $serviceData = [
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'Premium Business Cards',
            'title_ar' => 'بطاقات عمل فاخرة',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'description_ar' => 'خدمة طباعة بطاقات عمل فاخرة عالية الجودة',
            'base_price' => 99.99,
            'discount_price' => 89.99,
            'turnaround_time' => '3-5 days',
            'min_quantity' => 100,
            'max_quantity' => 10000,
            'status' => 'active',
            'featured' => true,
        ];

        $service = Service::create($serviceData);

        $this->assertInstanceOf(Service::class, $service);
        $this->assertEquals('Premium Business Cards', $service->title);
        $this->assertEquals('بطاقات عمل فاخرة', $service->title_ar);
        $this->assertEquals('premium-business-cards', $service->slug);
        $this->assertEquals(99.99, $service->base_price);
        $this->assertEquals(89.99, $service->discount_price);
        $this->assertEquals('active', $service->status);
        $this->assertTrue($service->featured);
    }

    /**
     * Test service relationships.
     */
    public function test_service_relationships(): void
    {
        $vendor = Vendor::create([
            'user_id' => 1,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        $category = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        $service = Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        // Test vendor relationship
        $this->assertInstanceOf(Vendor::class, $service->vendor);
        $this->assertEquals($vendor->id, $service->vendor->id);
        
        // Test category relationship
        $this->assertInstanceOf(Category::class, $service->category);
        $this->assertEquals($category->id, $service->category->id);
    }

    /**
     * Test service media relationship.
     */
    public function test_service_has_many_media(): void
    {
        $service = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        // Create service media
        $media1 = ServiceMedia::create([
            'service_id' => $service->id,
            'file_path' => 'services/business-cards-1.jpg',
            'file_type' => 'image',
            'is_featured' => true,
            'sort_order' => 1,
        ]);
        
        $media2 = ServiceMedia::create([
            'service_id' => $service->id,
            'file_path' => 'services/business-cards-2.jpg',
            'file_type' => 'image',
            'is_featured' => false,
            'sort_order' => 2,
        ]);
        
        // Test relationship
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $service->media);
        $this->assertEquals(2, $service->media->count());
        $this->assertTrue($service->media->contains($media1));
        $this->assertTrue($service->media->contains($media2));
    }

    /**
     * Test service status scopes.
     */
    public function test_service_status_scopes(): void
    {
        // Create services with different statuses
        $activeService = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Active Service',
            'slug' => 'active-service',
            'description' => 'Active service description',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        $draftService = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Draft Service',
            'slug' => 'draft-service',
            'description' => 'Draft service description',
            'base_price' => 99.99,
            'status' => 'draft',
        ]);
        
        $inactiveService = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Inactive Service',
            'slug' => 'inactive-service',
            'description' => 'Inactive service description',
            'base_price' => 99.99,
            'status' => 'inactive',
        ]);
        
        // Test active scope
        $this->assertEquals(1, Service::active()->count());
        $this->assertTrue(Service::active()->get()->contains($activeService));
        
        // Test draft scope
        $this->assertEquals(1, Service::draft()->count());
        $this->assertTrue(Service::draft()->get()->contains($draftService));
        
        // Test inactive scope
        $this->assertEquals(1, Service::inactive()->count());
        $this->assertTrue(Service::inactive()->get()->contains($inactiveService));
    }

    /**
     * Test service featured scope.
     */
    public function test_service_featured_scope(): void
    {
        // Create featured and non-featured services
        $featuredService = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Featured Service',
            'slug' => 'featured-service',
            'description' => 'Featured service description',
            'base_price' => 99.99,
            'status' => 'active',
            'featured' => true,
        ]);
        
        $regularService = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Regular Service',
            'slug' => 'regular-service',
            'description' => 'Regular service description',
            'base_price' => 99.99,
            'status' => 'active',
            'featured' => false,
        ]);
        
        // Test featured scope
        $this->assertEquals(1, Service::featured()->count());
        $this->assertTrue(Service::featured()->get()->contains($featuredService));
        $this->assertFalse(Service::featured()->get()->contains($regularService));
    }

    /**
     * Test service price calculation.
     */
    public function test_service_price_calculation(): void
    {
        // Create service with discount
        $service = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Discounted Service',
            'slug' => 'discounted-service',
            'description' => 'Discounted service description',
            'base_price' => 100.00,
            'discount_price' => 80.00,
            'status' => 'active',
        ]);
        
        // Test price calculation method
        $this->assertEquals(80.00, $service->getCurrentPrice());
        
        // Create service without discount
        $serviceNoDiscount = Service::create([
            'vendor_id' => 1,
            'category_id' => 1,
            'title' => 'Regular Service',
            'slug' => 'regular-service',
            'description' => 'Regular service description',
            'base_price' => 100.00,
            'status' => 'active',
        ]);
        
        // Test price calculation method
        $this->assertEquals(100.00, $serviceNoDiscount->getCurrentPrice());
    }
}
