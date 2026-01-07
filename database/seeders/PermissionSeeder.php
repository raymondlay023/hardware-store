<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // Product permissions
            ['name' => 'products.view', 'display_name' => 'View Products', 'category' => 'products', 'description' => 'Can view product list'],
            ['name' => 'products.create', 'display_name' => 'Create Products', 'category' => 'products', 'description' => 'Can create new products'],
            ['name' => 'products.update', 'display_name' => 'Update Products', 'category' => 'products', 'description' => 'Can edit product details'],
            ['name' => 'products.delete', 'display_name' => 'Delete Products', 'category' => 'products', 'description' => 'Can delete products'],
            ['name' => 'products.import', 'display_name' => 'Import Products', 'category' => 'products', 'description' => 'Can bulk import products'],
            ['name' => 'products.export', 'display_name' => 'Export Products', 'category' => 'products', 'description' => 'Can export product data'],

            // Sales permissions
            ['name' => 'sales.view', 'display_name' => 'View Sales', 'category' => 'sales', 'description' => 'Can view sales transactions'],
            ['name' => 'sales.create', 'display_name' => 'Create Sales', 'category' => 'sales', 'description' => 'Can create new sales'],
            ['name' => 'sales.update', 'display_name' => 'Update Sales', 'category' => 'sales', 'description' => 'Can edit sales (within grace period)'],
            ['name' => 'sales.delete', 'display_name' => 'Delete Sales', 'category' => 'sales', 'description' => 'Can void/delete sales'],
            ['name' => 'sales.discount', 'display_name' => 'Apply Discounts', 'category' => 'sales', 'description' => 'Can apply discounts to sales'],

            // Customer permissions
            ['name' => 'customers.view', 'display_name' => 'View Customers', 'category' => 'customers', 'description' => 'Can view customer list'],
            ['name' => 'customers.create', 'display_name' => 'Create Customers', 'category' => 'customers', 'description' => 'Can add new customers'],
            ['name' => 'customers.update', 'display_name' => 'Update Customers', 'category' => 'customers', 'description' => 'Can edit customer details'],
            ['name' => 'customers.delete', 'display_name' => 'Delete Customers', 'category' => 'customers', 'description' => 'Can delete customers'],

            // Supplier permissions
            ['name' => 'suppliers.view', 'display_name' => 'View Suppliers', 'category' => 'suppliers', 'description' => 'Can view supplier list'],
            ['name' => 'suppliers.create', 'display_name' => 'Create Suppliers', 'category' => 'suppliers', 'description' => 'Can add new suppliers'],
            ['name' => 'suppliers.update', 'display_name' => 'Update Suppliers', 'category' => 'suppliers', 'description' => 'Can edit supplier details'],
            ['name' => 'suppliers.delete', 'display_name' => 'Delete  Suppliers', 'category' => 'suppliers', 'description' => 'Can delete suppliers'],

            // Inventory/Stock permissions
            ['name' => 'inventory.view', 'display_name' => 'View Inventory', 'category' => 'inventory', 'description' => 'Can view stock levels'],
            ['name' => 'inventory.adjust', 'display_name' => 'Adjust Inventory', 'category' => 'inventory', 'description' => 'Can manually adjust stock'],
            ['name' => 'inventory.history', 'display_name' => 'View Stock History', 'category' => 'inventory', 'description' => 'Can view stock movement history'],

            // Purchase permissions
            ['name' => 'purchases.view', 'display_name' => 'View Purchases', 'category' => 'purchases', 'description' => 'Can view purchase orders'],
            ['name' => 'purchases.create', 'display_name' => 'Create Purchases', 'category' => 'purchases', 'description' => 'Can create purchase orders'],
            ['name' => 'purchases.update', 'display_name' => 'Update Purchases', 'category' => 'purchases', 'description' => 'Can edit purchase orders'],
            ['name' => 'purchases.delete', 'display_name' => 'Delete Purchases', 'category' => 'purchases', 'description' => 'Can delete purchase orders'],

            // Report permissions
            ['name' => 'reports.sales', 'display_name' => 'Sales Reports', 'category' => 'reports', 'description' => 'Can view sales reports'],
            ['name' => 'reports.inventory', 'display_name' => 'Inventory Reports', 'category' => 'reports', 'description' => 'Can view inventory reports'],
            ['name' => 'reports.profit', 'display_name' => 'Profit Reports', 'category' => 'reports', 'description' => 'Can view profit/margin reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'category' => 'reports', 'description' => 'Can export reports to PDF/Excel'],

            // User Management permissions
            ['name' => 'users.view', 'display_name' => 'View Users', 'category' => 'users', 'description' => 'Can view user list'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'category' => 'users', 'description' => 'Can add new users'],
            ['name' => 'users.update', 'display_name' => 'Update Users', 'category' => 'users', 'description' => 'Can edit user details'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'category' => 'users', 'description' => 'Can delete users'],

            // Settings permissions
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'category' => 'settings', 'description' => 'Can view system settings'],
            ['name' => 'settings.update', 'display_name' => 'Update Settings', 'category' => 'settings', 'description' => 'Can modify system settings'],

            // Dashboard permissions
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'category' => 'dashboard', 'description' => 'Can view dashboard analytics'],
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        echo "\n✅ Permissions seeded successfully!\n";
        echo "📊 Total permissions: " . count($permissions) . "\n\n";
    }

    /**
     * Assign permissions to each role
     */
    private function assignPermissionsToRoles(): void
    {
        // Admin: All permissions (but we won't assign manually, 
        // User model returns true for all permissions for admin)

        // Manager permissions
        $manager = Role::where('name', 'manager')->first();
        if ($manager) {
            $managerPermissions = [
                // Products - Full access
                'products.view', 'products.create', 'products.update', 'products.delete',
                'products.import', 'products.export',
                
                // Sales - Full access
                'sales.view', 'sales.create', 'sales.update', 'sales.delete', 'sales.discount',
                
                // Customers - Full access
                'customers.view', 'customers.create', 'customers.update', 'customers.delete',
                
                // Suppliers - Full access
                'suppliers.view', 'suppliers.create', 'suppliers.update', 'suppliers.delete',
                
                // Inventory - Full access
                'inventory.view', 'inventory.adjust', ' inventory.history',
                
                // Purchases - Full access
                'purchases.view', 'purchases.create', 'purchases.update', 'purchases.delete',
                
                // Reports - Full access
                'reports.sales', 'reports.inventory', 'reports.profit', 'reports.export',
                
                // Dashboard
                'dashboard.view',
                
                // Users - View only
                'users.view',
            ];

            $manager->permissions()->sync(
                Permission::whereIn('name', $managerPermissions)->pluck('id')
            );

            echo "✅ Manager permissions assigned\n";
        }

        // Cashier permissions
        $cashier = Role::where('name', 'cashier')->first();
        if ($cashier) {
            $cashierPermissions = [
                // Products - View only
                'products.view',
                
                // Sales - Create and view
                'sales.view', 'sales.create',
                
                // Customers - View and create
                'customers.view', 'customers.create',
                
                // Inventory - View only
                'inventory.view',
                
                // Dashboard - Basic view
                'dashboard.view',
            ];

            $cashier->permissions()->sync(
                Permission::whereIn('name', $cashierPermissions)->pluck('id')
            );

            echo "✅ Cashier permissions assigned\n";
        }
    }
}
