<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration.
     */
    public function test_user_can_register(): void
    {
        // Create customer role
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'accepted',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
        
        // Check if user has customer role
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('customer'));
    }

    /**
     * Test user login.
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /**
     * Test user login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /**
     * Test user logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test access to protected routes.
     */
    public function test_unauthenticated_user_cannot_access_protected_routes(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test role-based access control.
     */
    public function test_role_based_access_control(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $vendorRole = Role::create(['name' => 'vendor', 'display_name' => 'Vendor']);
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Create users with different roles
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole);
        
        $vendorUser = User::factory()->create();
        $vendorUser->roles()->attach($vendorRole);
        
        $customerUser = User::factory()->create();
        $customerUser->roles()->attach($customerRole);
        
        // Test admin access
        $response = $this->actingAs($adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        
        // Test vendor cannot access admin routes
        $response = $this->actingAs($vendorUser)->get('/admin/dashboard');
        $response->assertStatus(403);
        
        // Test customer cannot access admin or vendor routes
        $response = $this->actingAs($customerUser)->get('/admin/dashboard');
        $response->assertStatus(403);
        
        $response = $this->actingAs($customerUser)->get('/vendor/dashboard');
        $response->assertStatus(403);
    }
}
