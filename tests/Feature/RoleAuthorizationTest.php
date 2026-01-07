<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);
    }

    public function test_admin_has_all_permissions(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        // Admin should have all permissions
        $this->assertTrue($admin->hasPermission('products.create'));
        $this->assertTrue($admin->hasPermission('sales.delete'));
        $this->assertTrue($admin->hasPermission('users.update'));
        $this->assertTrue($admin->hasPermission('settings.update'));
    }

    public function test_manager_has_correct_permissions(): void
    {
        $manager = User::factory()->create();
        $managerRole = Role::where('name', 'manager')->first();
        $manager->roles()->attach($managerRole);

        // Manager should have most permissions
        $this->assertTrue($manager->hasPermission('products.create'));
        $this->assertTrue($manager->hasPermission('products.update'));
        $this->assertTrue($manager->hasPermission('sales.create'));
        $this->assertTrue($manager->hasPermission('reports.profit'));

        // But not user management beyond viewing
        $this->assertTrue($manager->hasPermission('users.view'));
        $this->assertFalse($manager->hasPermission('users.create'));
        $this->assertFalse($manager->hasPermission('users.delete'));
    }

    public function test_cashier_has_limited_permissions(): void
    {
        $cashier = User::factory()->create();
        $cashierRole = Role::where('name', 'cashier')->first();
        $cashier->roles()->attach($cashierRole);

        // Cashier can view products and create sales
        $this->assertTrue($cashier->hasPermission('products.view'));
        $this->assertTrue($cashier->hasPermission('sales.create'));
        $this->assertTrue($cashier->hasPermission('customers.create'));

        // But cannot modify products or access advanced features
        $this->assertFalse($cashier->hasPermission('products.create'));
        $this->assertFalse($cashier->hasPermission('products.update'));
        $this->assertFalse($cashier->hasPermission('sales.delete'));
        $this->assertFalse($cashier->hasPermission('reports.profit'));
        $this->assertFalse($cashier->hasPermission('users.view'));
    }

    public function test_user_with_multiple_roles_has_combined_permissions(): void
    {
        $user = User::factory()->create();
        $cashierRole = Role::where('name', 'cashier')->first();
        $managerRole = Role::where('name', 'manager')->first();
        
        $user->roles()->attach([$cashierRole->id, $managerRole->id]);

        // Should have permissions from both roles
        $this->assertTrue($user->hasPermission('sales.create')); // From cashier
        $this->assertTrue($user->hasPermission('products.create')); // From manager
        $this->assertTrue($user->hasPermission('reports.profit')); // From manager
    }

    public function test_has_any_permission_works_correctly(): void
    {
        $cashier = User::factory()->create();
        $cashierRole = Role::where('name', 'cashier')->first();
        $cashier->roles()->attach($cashierRole);

        // Should return true if has ANY of the permissions
        $this->assertTrue($cashier->hasAnyPermission(['products.view', 'products.create']));
        $this->assertFalse($cashier->hasAnyPermission(['products.create', 'products.update']));
    }

    public function test_has_all_permissions_works_correctly(): void
    {
        $manager = User::factory()->create();
        $managerRole = Role::where('name', 'manager')->first();
        $manager->roles()->attach($managerRole);

        // Should return true only if has ALL permissions
        $this->assertTrue($manager->hasAllPermissions(['products.view', 'products.create']));
        $this->assertFalse($manager->hasAllPermissions(['products.create', 'users.create']));
    }

    public function test_product_policy_respects_permissions(): void
    {
        $manager = User::factory()->create();
        $managerRole = Role::where('name', 'manager')->first();
        $manager->roles()->attach($managerRole);

        $cashier = User::factory()->create();
        $cashierRole = Role::where('name', 'cashier')->first();
        $cashier->roles()->attach($cashierRole);

        $product = Product::factory()->create();

        // Manager can create and update
        $this->assertTrue($manager->can('create', Product::class));
        $this->assertTrue($manager->can('update', $product));
        $this->assertTrue($manager->can('delete', $product));

        // Cashier can only view
        $this->assertTrue($cashier->can('view', $product));
        $this->assertFalse($cashier->can('create', Product::class));
        $this->assertFalse($cashier->can('update', $product));
        $this->assertFalse($cashier->can('delete', $product));
    }

    public function test_role_can_grant_and_revoke_permissions(): void
    {
        $role = Role::factory()->create(['name' => 'test-role']);
        $permission = Permission::create([
            'name' => 'test.permission',
            'display_name' => 'Test Permission',
            'category' => 'test',
        ]);

        // Initially no permission
        $this->assertFalse($role->hasPermission('test.permission'));

        // Grant permission
        $role->grantPermission($permission);
        $this->assertTrue($role->hasPermission('test.permission'));

        // Revoke permission
        $role->revokePermission($permission);
        $this->assertFalse($role->hasPermission('test.permission'));
    }
}
