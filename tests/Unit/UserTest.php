<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user creation.
     */
    public function test_can_create_user(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'password' => Hash::make('Password123!'),
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('+1234567890', $user->phone);
    }

    /**
     * Test user role assignment.
     */
    public function test_can_assign_role_to_user(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        $vendorRole = Role::create(['name' => 'vendor', 'display_name' => 'Vendor']);
        
        // Assign roles
        $user->roles()->attach($customerRole);
        $user->roles()->attach($vendorRole);
        
        // Refresh user model
        $user = $user->fresh();
        
        // Assert roles were assigned
        $this->assertTrue($user->hasRole('customer'));
        $this->assertTrue($user->hasRole('vendor'));
        $this->assertEquals(2, $user->roles->count());
    }

    /**
     * Test user role checking.
     */
    public function test_can_check_user_role(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create roles
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        
        // Assign customer role
        $user->roles()->attach($customerRole);
        
        // Refresh user model
        $user = $user->fresh();
        
        // Assert role checking works
        $this->assertTrue($user->hasRole('customer'));
        $this->assertFalse($user->hasRole('admin'));
    }

    /**
     * Test user password hashing.
     */
    public function test_password_is_hashed(): void
    {
        $plainPassword = 'Password123!';
        
        $user = User::factory()->create([
            'password' => Hash::make($plainPassword)
        ]);
        
        // Verify password is hashed
        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    /**
     * Test user model relationships.
     */
    public function test_user_relationships(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a role
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer']);
        
        // Assign role
        $user->roles()->attach($customerRole);
        
        // Refresh user model
        $user = $user->fresh();
        
        // Assert relationships
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->roles);
        $this->assertInstanceOf(Role::class, $user->roles->first());
    }
}
