<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;
    protected User $cashier;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

        // Create test users
        $this->manager = User::factory()->create();
        $this->manager->roles()->attach(Role::where('name', 'manager')->first());

        $this->cashier = User::factory()->create();
        $this->cashier->roles()->attach(Role::where('name', 'cashier')->first());
    }

    public function test_manager_can_view_products_list(): void
    {
        Product::factory()->count(5)->create();

        $this->assertTrue($this->manager->hasPermission('products.view'));
    }

    public function test_manager_can_create_product(): void
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'brand' => 'Test Brand',
            'unit' => 'pcs',
            'cost' => 10000,
            'price' => 15000,
            'current_stock' => 100,
            'low_stock_threshold' => 20,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ];

        $this->assertTrue($this->manager->can('create', Product::class));

        $product = Product::create($productData);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 15000,
        ]);
    }

    public function test_manager_can_update_product(): void
    {
        $product = Product::factory()->create(['name' => 'Old Name']);

        $this->assertTrue($this->manager->can('update', $product));

        $product->update(['name' => 'New Name']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
        ]);
    }

    public function test_manager_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $this->assertTrue($this->manager->can('delete', $product));

        $product->delete();

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_cashier_cannot_create_product(): void
    {
        $this->assertFalse($this->cashier->can('create', Product::class));
        $this->assertFalse($this->cashier->hasPermission('products.create'));
    }

    public function test_cashier_can_view_products(): void
    {
        $product = Product::factory()->create();

        $this->assertTrue($this->cashier->can('view', $product));
        $this->assertTrue($this->cashier->hasPermission('products.view'));
    }

    public function test_product_profit_margin_calculation(): void
    {
        $product = Product::factory()->create([
            'cost' => 10000,
            'price' => 15000,
        ]);

        // Profit = 15000 - 10000 = 5000
        // Margin = (5000 / 15000) * 100 = 33.33%
        $expectedProfit = 5000;
        $expectedMargin = 33.33;

        $profit = $product->price - $product->cost;
        $margin = ($profit / $product->price) * 100;

        $this->assertEquals($expectedProfit, $profit);
        $this->assertEquals($expectedMargin, round($margin, 2));
    }

    public function test_low_stock_products_are_identified(): void
    {
        // Create products with different stock levels
        $lowStock = Product::factory()->create([
            'current_stock' => 5,
            'low_stock_threshold' => 10,
        ]);

        $adequateStock = Product::factory()->create([
            'current_stock' => 50,
            'low_stock_threshold' => 10,
        ]);

        // Low stock: current_stock <= low_stock_threshold
        $this->assertTrue($lowStock->current_stock <= $lowStock->low_stock_threshold);
        $this->assertFalse($adequateStock->current_stock <= $adequateStock->low_stock_threshold);
    }

    public function test_product_belongs_to_category(): void
    {
        $category = Category::factory()->create(['name' => 'Test Category']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertEquals('Test Category', $product->category->name);
    }

    public function test_product_belongs_to_supplier(): void
    {
        $supplier = Supplier::factory()->create(['name' => 'Test Supplier']);
        $product = Product::factory()->create(['supplier_id' => $supplier->id]);

        $this->assertEquals('Test Supplier', $product->supplier->name);
    }

    public function test_product_factory_creates_valid_product(): void
    {
        $product = Product::factory()->create();

        $this->assertNotNull($product->name);
        $this->assertNotNull($product->price);
        $this->assertNotNull($product->cost);
        $this->assertTrue($product->price > $product->cost); // Price should be higher than cost
    }

    public function test_product_factory_low_stock_state(): void
    {
        $product = Product::factory()->lowStock()->create();

        $this->assertTrue($product->current_stock < $product->low_stock_threshold);
    }

    public function test_product_factory_out_of_stock_state(): void
    {
        $product = Product::factory()->outOfStock()->create();

        $this->assertEquals(0, $product->current_stock);
    }
}
