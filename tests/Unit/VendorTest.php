<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test vendor creation.
     */
    public function test_can_create_vendor(): void
    {
        $user = User::factory()->create();
        
        $vendorData = [
            'user_id' => $user->id,
            'company_name' => 'Test Printing Company',
            'company_name_ar' => 'شركة الطباعة التجريبية',
            'description' => 'A test printing company',
            'description_ar' => 'شركة طباعة تجريبية',
            'logo' => 'logos/test-logo.png',
            'banner' => 'banners/test-banner.jpg',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'Test Country',
            'phone' => '+1234567890',
            'email' => 'contact@testprinting.com',
            'website' => 'https://testprinting.com',
            'status' => 'active',
            'rating' => 4.5,
            'featured' => true,
        ];

        $vendor = Vendor::create($vendorData);

        $this->assertInstanceOf(Vendor::class, $vendor);
        $this->assertEquals('Test Printing Company', $vendor->company_name);
        $this->assertEquals('شركة الطباعة التجريبية', $vendor->company_name_ar);
        $this->assertEquals('active', $vendor->status);
        $this->assertEquals(4.5, $vendor->rating);
        $this->assertTrue($vendor->featured);
    }

    /**
     * Test vendor-user relationship.
     */
    public function test_vendor_belongs_to_user(): void
    {
        $user = User::factory()->create();
        
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        $this->assertInstanceOf(User::class, $vendor->user);
        $this->assertEquals($user->id, $vendor->user->id);
    }

    /**
     * Test vendor-service relationship.
     */
    public function test_vendor_has_many_services(): void
    {
        $user = User::factory()->create();
        
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'status' => 'active',
        ]);
        
        $service1 = Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'Test Service 1',
            'slug' => 'test-service-1',
            'description' => 'Test service description',
            'base_price' => 100.00,
            'status' => 'active',
        ]);
        
        $service2 = Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'Test Service 2',
            'slug' => 'test-service-2',
            'description' => 'Another test service description',
            'base_price' => 150.00,
            'status' => 'active',
        ]);
        
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $vendor->services);
        $this->assertEquals(2, $vendor->services->count());
        $this->assertTrue($vendor->services->contains($service1));
        $this->assertTrue($vendor->services->contains($service2));
    }

    /**
     * Test vendor status scopes.
     */
    public function test_vendor_status_scopes(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        
        $activeVendor = Vendor::create([
            'user_id' => $user1->id,
            'company_name' => 'Active Vendor',
            'status' => 'active',
        ]);
        
        $pendingVendor = Vendor::create([
            'user_id' => $user2->id,
            'company_name' => 'Pending Vendor',
            'status' => 'pending',
        ]);
        
        $suspendedVendor = Vendor::create([
            'user_id' => $user3->id,
            'company_name' => 'Suspended Vendor',
            'status' => 'suspended',
        ]);
        
        $this->assertEquals(1, Vendor::active()->count());
        $this->assertEquals(1, Vendor::pending()->count());
        $this->assertEquals(1, Vendor::suspended()->count());
        
        $this->assertTrue(Vendor::active()->get()->contains($activeVendor));
        $this->assertTrue(Vendor::pending()->get()->contains($pendingVendor));
        $this->assertTrue(Vendor::suspended()->get()->contains($suspendedVendor));
    }

    /**
     * Test vendor featured scope.
     */
    public function test_vendor_featured_scope(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $featuredVendor = Vendor::create([
            'user_id' => $user1->id,
            'company_name' => 'Featured Vendor',
            'status' => 'active',
            'featured' => true,
        ]);
        
        $regularVendor = Vendor::create([
            'user_id' => $user2->id,
            'company_name' => 'Regular Vendor',
            'status' => 'active',
            'featured' => false,
        ]);
        
        $this->assertEquals(1, Vendor::featured()->count());
        $this->assertTrue(Vendor::featured()->get()->contains($featuredVendor));
        $this->assertFalse(Vendor::featured()->get()->contains($regularVendor));
    }
}
