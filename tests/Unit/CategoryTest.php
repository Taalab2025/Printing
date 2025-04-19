<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test category creation.
     */
    public function test_can_create_category(): void
    {
        $categoryData = [
            'name' => 'Business Cards',
            'name_ar' => 'بطاقات العمل',
            'slug' => 'business-cards',
            'description' => 'Professional business cards printing services',
            'description_ar' => 'خدمات طباعة بطاقات العمل الاحترافية',
            'image' => 'categories/business-cards.jpg',
            'icon' => 'fa-id-card',
            'position' => 1,
            'status' => 'active',
        ];

        $category = Category::create($categoryData);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Business Cards', $category->name);
        $this->assertEquals('بطاقات العمل', $category->name_ar);
        $this->assertEquals('business-cards', $category->slug);
        $this->assertEquals('active', $category->status);
        $this->assertEquals(1, $category->position);
    }

    /**
     * Test category parent-child relationship.
     */
    public function test_category_parent_child_relationship(): void
    {
        // Create parent category
        $parentCategory = Category::create([
            'name' => 'Marketing Materials',
            'slug' => 'marketing-materials',
            'status' => 'active',
        ]);
        
        // Create child categories
        $childCategory1 = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'parent_id' => $parentCategory->id,
            'status' => 'active',
        ]);
        
        $childCategory2 = Category::create([
            'name' => 'Brochures',
            'slug' => 'brochures',
            'parent_id' => $parentCategory->id,
            'status' => 'active',
        ]);
        
        // Refresh parent category
        $parentCategory = $parentCategory->fresh();
        
        // Test parent-child relationship
        $this->assertNull($parentCategory->parent);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $parentCategory->children);
        $this->assertEquals(2, $parentCategory->children->count());
        $this->assertTrue($parentCategory->children->contains($childCategory1));
        $this->assertTrue($parentCategory->children->contains($childCategory2));
        
        // Test child-parent relationship
        $this->assertInstanceOf(Category::class, $childCategory1->parent);
        $this->assertEquals($parentCategory->id, $childCategory1->parent->id);
    }

    /**
     * Test category-service relationship.
     */
    public function test_category_has_many_services(): void
    {
        // Create category
        $category = Category::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'status' => 'active',
        ]);
        
        // Create services
        $service1 = Service::create([
            'category_id' => $category->id,
            'vendor_id' => 1,
            'title' => 'Standard Business Cards',
            'slug' => 'standard-business-cards',
            'description' => 'Standard business cards printing',
            'base_price' => 50.00,
            'status' => 'active',
        ]);
        
        $service2 = Service::create([
            'category_id' => $category->id,
            'vendor_id' => 1,
            'title' => 'Premium Business Cards',
            'slug' => 'premium-business-cards',
            'description' => 'Premium business cards printing',
            'base_price' => 100.00,
            'status' => 'active',
        ]);
        
        // Test relationship
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $category->services);
        $this->assertEquals(2, $category->services->count());
        $this->assertTrue($category->services->contains($service1));
        $this->assertTrue($category->services->contains($service2));
    }

    /**
     * Test category status scopes.
     */
    public function test_category_status_scopes(): void
    {
        // Create categories with different statuses
        $activeCategory = Category::create([
            'name' => 'Active Category',
            'slug' => 'active-category',
            'status' => 'active',
        ]);
        
        $inactiveCategory = Category::create([
            'name' => 'Inactive Category',
            'slug' => 'inactive-category',
            'status' => 'inactive',
        ]);
        
        // Test active scope
        $this->assertEquals(1, Category::active()->count());
        $this->assertTrue(Category::active()->get()->contains($activeCategory));
        $this->assertFalse(Category::active()->get()->contains($inactiveCategory));
        
        // Test inactive scope
        $this->assertEquals(1, Category::inactive()->count());
        $this->assertTrue(Category::inactive()->get()->contains($inactiveCategory));
        $this->assertFalse(Category::inactive()->get()->contains($activeCategory));
    }

    /**
     * Test category position ordering.
     */
    public function test_category_position_ordering(): void
    {
        // Create categories with different positions
        Category::create([
            'name' => 'Category C',
            'slug' => 'category-c',
            'position' => 3,
            'status' => 'active',
        ]);
        
        Category::create([
            'name' => 'Category A',
            'slug' => 'category-a',
            'position' => 1,
            'status' => 'active',
        ]);
        
        Category::create([
            'name' => 'Category B',
            'slug' => 'category-b',
            'position' => 2,
            'status' => 'active',
        ]);
        
        // Get categories ordered by position
        $orderedCategories = Category::orderBy('position')->get();
        
        // Test ordering
        $this->assertEquals('Category A', $orderedCategories[0]->name);
        $this->assertEquals('Category B', $orderedCategories[1]->name);
        $this->assertEquals('Category C', $orderedCategories[2]->name);
    }
}
