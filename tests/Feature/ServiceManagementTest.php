<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Service;
use App\Models\Role;

class ServiceManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test service listing page.
     */
    public function test_services_listing_page(): void
    {
        // Create categories
        $category1 = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        $category2 = Category::create([
            'name' => 'Brochures',
            'slug' => 'brochures',
            'status' => 'active',
        ]);
        
        // Create vendor
        $vendor = Vendor::create([
            'user_id' => 1,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create services
        Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category1->id,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category2->id,
            'title' => 'Tri-fold Brochures',
            'slug' => 'tri-fold-brochures',
            'description' => 'Professional tri-fold brochures printing service',
            'base_price' => 149.99,
            'status' => 'active',
        ]);
        
        // Test services listing page
        $response = $this->get('/services');
        $response->assertStatus(200);
        $response->assertSee('Premium Business Cards');
        $response->assertSee('Tri-fold Brochures');
    }

    /**
     * Test service filtering by category.
     */
    public function test_service_filtering_by_category(): void
    {
        // Create categories
        $category1 = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        $category2 = Category::create([
            'name' => 'Brochures',
            'slug' => 'brochures',
            'status' => 'active',
        ]);
        
        // Create vendor
        $vendor = Vendor::create([
            'user_id' => 1,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create services
        Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category1->id,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category2->id,
            'title' => 'Tri-fold Brochures',
            'slug' => 'tri-fold-brochures',
            'description' => 'Professional tri-fold brochures printing service',
            'base_price' => 149.99,
            'status' => 'active',
        ]);
        
        // Test filtering by category
        $response = $this->get('/services?category=business-cards');
        $response->assertStatus(200);
        $response->assertSee('Premium Business Cards');
        $response->assertDontSee('Tri-fold Brochures');
    }

    /**
     * Test service details page.
     */
    public function test_service_details_page(): void
    {
        // Create category
        $category = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        // Create vendor
        $vendor = Vendor::create([
            'user_id' => 1,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create service
        $service = Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        // Test service details page
        $response = $this->get('/services/' . $service->slug);
        $response->assertStatus(200);
        $response->assertSee('Premium Business Cards');
        $response->assertSee('High-quality premium business cards printing service');
        $response->assertSee('99.99');
        $response->assertSee('Test Printing Company');
    }

    /**
     * Test vendor can create service.
     */
    public function test_vendor_can_create_service(): void
    {
        // Create roles
        $vendorRole = Role::create(['name' => 'vendor', 'display_name' => 'Vendor']);
        
        // Create vendor user
        $vendorUser = User::factory()->create();
        $vendorUser->roles()->attach($vendorRole);
        
        // Create vendor profile
        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create category
        $category = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        // Test vendor can create service
        $response = $this->actingAs($vendorUser)->post('/vendor/services', [
            'category_id' => $category->id,
            'title' => 'Premium Business Cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'turnaround_time' => '3-5 days',
            'min_quantity' => 100,
            'max_quantity' => 10000,
        ]);
        
        $response->assertRedirect('/vendor/services');
        $this->assertDatabaseHas('services', [
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'Premium Business Cards',
        ]);
    }

    /**
     * Test vendor can update service.
     */
    public function test_vendor_can_update_service(): void
    {
        // Create roles
        $vendorRole = Role::create(['name' => 'vendor', 'display_name' => 'Vendor']);
        
        // Create vendor user
        $vendorUser = User::factory()->create();
        $vendorUser->roles()->attach($vendorRole);
        
        // Create vendor profile
        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'company_name' => 'Test Printing Company',
            'status' => 'active',
        ]);
        
        // Create category
        $category = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        // Create service
        $service = Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        // Test vendor can update service
        $response = $this->actingAs($vendorUser)->put('/vendor/services/' . $service->id, [
            'category_id' => $category->id,
            'title' => 'Updated Business Cards',
            'description' => 'Updated description',
            'base_price' => 129.99,
        ]);
        
        $response->assertRedirect('/vendor/services');
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => 'Updated Business Cards',
            'description' => 'Updated description',
            'base_price' => 129.99,
        ]);
    }

    /**
     * Test vendor cannot update another vendor's service.
     */
    public function test_vendor_cannot_update_another_vendors_service(): void
    {
        // Create roles
        $vendorRole = Role::create(['name' => 'vendor', 'display_name' => 'Vendor']);
        
        // Create vendor users
        $vendorUser1 = User::factory()->create();
        $vendorUser1->roles()->attach($vendorRole);
        
        $vendorUser2 = User::factory()->create();
        $vendorUser2->roles()->attach($vendorRole);
        
        // Create vendor profiles
        $vendor1 = Vendor::create([
            'user_id' => $vendorUser1->id,
            'company_name' => 'Test Printing Company 1',
            'status' => 'active',
        ]);
        
        $vendor2 = Vendor::create([
            'user_id' => $vendorUser2->id,
            'company_name' => 'Test Printing Company 2',
            'status' => 'active',
        ]);
        
        // Create category
        $category = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        // Create service for vendor 1
        $service = Service::create([
            'vendor_id' => $vendor1->id,
            'category_id' => $category->id,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'High-quality premium business cards printing service',
            'base_price' => 99.99,
            'status' => 'active',
        ]);
        
        // Test vendor 2 cannot update vendor 1's service
        $response = $this->actingAs($vendorUser2)->put('/vendor/services/' . $service->id, [
            'category_id' => $category->id,
            'title' => 'Updated Business Cards',
            'description' => 'Updated description',
            'base_price' => 129.99,
        ]);
        
        $response->assertStatus(403);
        $this->assertDatabaseMissing('services', [
            'id' => $service->id,
            'title' => 'Updated Business Cards',
        ]);
    }
}
