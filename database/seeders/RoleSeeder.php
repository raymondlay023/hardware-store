<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Full system access - Can manage everything including users and settings',
            ],
            [
                'name' => 'manager',
                'description' => 'Management access - Can manage products, purchases, sales, and view reports',
            ],
            [
                'name' => 'cashier',
                'description' => 'Sales access - Can create sales and manage customers only',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }

        echo "✅ Created 3 roles: admin, manager, cashier\n";
    }
}
