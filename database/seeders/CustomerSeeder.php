<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Seed the database by migrating existing customer names from sales
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get unique customer names from existing sales
            $customerNames = Sale::select('customer_name')
                ->whereNotNull('customer_name')
                ->distinct()
                ->pluck('customer_name');

            $this->command->info("Found {$customerNames->count()} unique customer names");

            foreach ($customerNames as $name) {
                if (empty(trim($name))) {
                    continue;
                }

                // Create customer record
                $customer = Customer::create([
                    'name' => $name,
                    'type' => 'retail', // Default type
                ]);

                // Update all sales with this customer name to link to the new customer
                Sale::where('customer_name', $name)->update([
                    'customer_id' => $customer->id,
                ]);

                // Calculate total purchases and orders for this customer
                $stats = Sale::where('customer_id', $customer->id)
                    ->selectRaw('COUNT(*) as total_orders, SUM(total_amount) as total_purchases')
                    ->first();

                $customer->update([
                    'total_orders' => $stats->total_orders,
                    'total_purchases' => $stats->total_purchases ?? 0,
                ]);

                $this->command->info("Created customer: {$name} ({$stats->total_orders} orders, Rp " . number_format($stats->total_purchases, 0) . ")");
            }

            $this->command->info("Successfully migrated all customers!");
        });
    }
}
