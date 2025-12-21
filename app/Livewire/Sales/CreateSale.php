<?php

namespace App\Livewire\Sales;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateSale extends Component
{
    #[Validate('required|string|max:255')]
    public $customer_name = '';

    #[Validate('required|date')]
    public $date = '';

    #[Validate('required|in:cash,card,check,transfer')]
    public $payment_method = 'cash';

    #[Validate('in:none,percentage,fixed')]
    public $discount_type = 'none';

    public $discount_value = 0;

    #[Validate('nullable|string|max:500')]
    public $notes = '';

    public $items = [];

    public $productSearch = '';

    public $selectedProduct = null;

    public $quantity = 1;

    public $showProductSearch = false;

    public $customerSuggestions = [];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->payment_method = 'cash';
        $this->discount_type = 'none';
        $this->discount_value = 0;
    }

    public function updatedCustomerName()
    {
        if (strlen($this->customer_name) >= 2) {
            // Get recent unique customer names from sales
            $this->customerSuggestions = Sale::select('customer_name')
                ->where('customer_name', 'like', '%'.$this->customer_name.'%')
                ->distinct()
                ->limit(5)
                ->pluck('customer_name')
                ->toArray();
        } else {
            $this->customerSuggestions = [];
        }
    }

    public function setToday()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function applyQuickDiscount($percentage)
    {
        $this->discount_type = 'percentage';
        $this->discount_value = $percentage;
    }

    public function clearDiscount()
    {
        $this->discount_type = 'none';
        $this->discount_value = 0;
    }

    public function handleKeyboardShortcut($key)
    {
        if ($key === 'save' && ! empty($this->items)) {
            $this->save();
        }
    }

    public function resetForm()
    {
        $this->reset(
            'customer_name',
            'date',
            'payment_method',
            'discount_type',
            'discount_value',
            'notes',
            'items',
            'productSearch',
            'selectedProduct',
            'quantity'
        );

        $this->date = now()->format('Y-m-d');
        $this->payment_method = 'cash';
        $this->discount_type = 'none';
        $this->discount_value = 0;
    }

    public function selectProduct($productId, $productName, $price)
    {
        $this->selectedProduct = $productId;
        $this->productSearch = $productName;
        $this->showProductSearch = false;
        $this->quantity = 1;
    }

    public function clearProduct()
    {
        $this->selectedProduct = null;
        $this->productSearch = '';
        $this->quantity = 1;
        $this->showProductSearch = false;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addItem()
    {
        if (! $this->selectedProduct || ! $this->quantity) {
            $this->dispatch('notification',
                message: 'Please select a product and enter quantity',
                type: 'error'
            );

            return;
        }

        try {
            $product = Product::find($this->selectedProduct);

            if (! $product) {
                $this->dispatch('notification',
                    message: 'Product not found',
                    type: 'error'
                );
                $this->clearProduct();

                return;
            }

            $quantityToAdd = (int) $this->quantity;

            if ($quantityToAdd <= 0) {
                $this->dispatch('notification',
                    message: 'Quantity must be greater than 0',
                    type: 'error'
                );

                return;
            }

            if ($product->current_stock < $quantityToAdd) {
                $this->dispatch('notification',
                    message: "Not enough stock. Available: {$product->current_stock}",
                    type: 'error'
                );

                return;
            }

            // Check if product already in items
            $existingIndex = collect($this->items)->search(fn ($item) => $item['product_id'] === $product->id);

            if ($existingIndex !== false) {
                $newQuantity = $this->items[$existingIndex]['quantity'] + $quantityToAdd;

                if ($product->current_stock < $newQuantity) {
                    $this->dispatch('notification',
                        message: "Not enough stock. Available: {$product->current_stock}. Current in cart: {$this->items[$existingIndex]['quantity']}",
                        type: 'error'
                    );

                    return;
                }

                $this->items[$existingIndex]['quantity'] = $newQuantity;
                $this->dispatch('notification',
                    message: "{$product->name} quantity updated to {$newQuantity}",
                    type: 'success'
                );
            } else {
                $this->items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'category' => $product->category,
                    'price' => (float) $product->price,
                    'quantity' => $quantityToAdd,
                ];
                $this->dispatch('notification',
                    message: "{$product->name} added to cart",
                    type: 'success'
                );
            }

            $this->clearProduct();
        } catch (\Exception $e) {
            $this->dispatch('notification',
                message: 'Error adding item: '.$e->getMessage(),
                type: 'error'
            );
        }
    }

    public function removeItem($index)
    {
        if (! isset($this->items[$index])) {
            $this->dispatch('notification',
                message: 'Item not found',
                type: 'error'
            );

            return;
        }

        $productName = $this->items[$index]['product_name'];
        unset($this->items[$index]);
        $this->items = array_values($this->items);

        $this->dispatch('notification',
            message: "✓ {$productName} removed from cart",
            type: 'success'
        );
    }

    public function updateQuantity($index, $quantity)
    {
        if (! isset($this->items[$index])) {
            $this->dispatch('notification',
                message: 'Item not found',
                type: 'error'
            );

            return;
        }

        $quantity = (int) $quantity;

        if ($quantity <= 0) {
            $this->dispatch('notification',
                message: 'Quantity must be greater than 0',
                type: 'error'
            );

            return;
        }

        try {
            $product = Product::find($this->items[$index]['product_id']);

            if (! $product) {
                $this->dispatch('notification',
                    message: 'Product not found',
                    type: 'error'
                );

                return;
            }

            if ($quantity > $product->current_stock) {
                $this->dispatch('notification',
                    message: "Not enough stock. Available: {$product->current_stock}",
                    type: 'error'
                );

                return;
            }

            $this->items[$index]['quantity'] = $quantity;
        } catch (\Exception $e) {
            $this->dispatch('notification',
                message: 'Error updating quantity: '.$e->getMessage(),
                type: 'error'
            );
        }
    }

    public function getSubtotal()
    {
        try {
            return collect($this->items)->sum(function ($item) {
                $itemPrice = (float) ($item['price'] ?? 0);
                $itemQuantity = (int) ($item['quantity'] ?? 0);

                return $itemPrice * $itemQuantity;
            });
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getDiscountAmount()
    {
        try {
            $subtotal = $this->getSubtotal();

            // If no items or invalid subtotal, return 0
            if ($subtotal <= 0) {
                return 0;
            }

            $discountValue = (float) ($this->discount_value ?? 0);

            if ($this->discount_type === 'percentage') {
                // Ensure percentage is between 0-100
                $discountValue = max(0, min(100, $discountValue));
                $discountAmount = ($subtotal * $discountValue) / 100;

                return min($discountAmount, $subtotal);
            } elseif ($this->discount_type === 'fixed') {
                // Ensure fixed discount doesn't exceed subtotal
                return min(max(0, $discountValue), $subtotal);
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTotalAmount()
    {
        try {
            $subtotal = $this->getSubtotal();

            // If no items, return 0
            if ($subtotal <= 0) {
                return 0;
            }

            $total = $subtotal - $this->getDiscountAmount();

            return max(0, $total); // Ensure total never goes negative
        } catch (\Exception $e) {
            return $this->getSubtotal();
        }
    }

    public function save()
    {
        try {
            // Validate customer name and date
            $this->validate([
                'customer_name' => 'required|string|max:255',
                'date' => 'required|date',
                'payment_method' => 'required|in:cash,card,check,transfer',
                'discount_type' => 'in:none,percentage,fixed',
                'notes' => 'nullable|string|max:500',
            ]);

            // Validate items exist
            if (empty($this->items)) {
                $this->dispatch('notification',
                    message: 'Please add at least one item to the cart',
                    type: 'error'
                );

                return;
            }

            // Validate each item
            foreach ($this->items as $item) {
                if (! isset($item['product_id']) || ! isset($item['quantity']) || ! isset($item['price'])) {
                    $this->dispatch('notification',
                        message: 'Invalid item data in cart',
                        type: 'error'
                    );

                    return;
                }

                // Check product still exists and has stock
                $product = Product::find($item['product_id']);
                if (! $product) {
                    $this->dispatch('notification',
                        message: "Product {$item['product_name']} no longer exists",
                        type: 'error'
                    );

                    return;
                }

                if ($product->current_stock < $item['quantity']) {
                    $this->dispatch('notification',
                        message: "Not enough stock for {$product->name}. Available: {$product->current_stock}",
                        type: 'error'
                    );

                    return;
                }
            }

            // Validate discount
            $subtotal = $this->getSubtotal();
            $discountAmount = $this->getDiscountAmount();

            if ($discountAmount >= $subtotal && $subtotal > 0) {
                $this->dispatch('notification',
                    message: 'Discount cannot be equal to or greater than subtotal',
                    type: 'error'
                );

                return;
            }

            $totalAmount = $this->getTotalAmount();

            if ($totalAmount < 0) {
                $this->dispatch('notification',
                    message: 'Invalid calculation: total amount is negative',
                    type: 'error'
                );

                return;
            }

            if ($totalAmount == 0 && $subtotal > 0) {
                $this->dispatch('notification',
                    message: 'Sale total cannot be zero',
                    type: 'error'
                );

                return;
            }

            // Create the sale within a transaction
            DB::beginTransaction();

            try {
                $sale = Sale::create([
                    'customer_name' => $this->customer_name,
                    'date' => $this->date,
                    'total_amount' => $subtotal,
                    'discount_type' => $this->discount_type,
                    'discount_value' => $this->discount_type !== 'none' ? max(0, min($discountAmount, $subtotal)) : 0,
                    'payment_method' => $this->payment_method,
                    'notes' => $this->notes ?: null,
                    'created_by' => Auth::id(),
                ]);

                // Add sale items
                foreach ($this->items as $item) {
                    $sale->saleItems()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                    ]);

                    // Decrement stock
                    Product::find($item['product_id'])->decrement('current_stock', $item['quantity']);
                }

                DB::commit();

                $this->resetForm();
                $totalAmount = $sale->total_amount - $sale->discount_value;

                $this->dispatch('notification',
                    message: '✓ Sale completed successfully! Total: Rp '.number_format($totalAmount, 0, ',', '.'),
                    type: 'success'
                );

                // Redirect to receipt printing page
                return redirect()->route('sales.receipt', $sale->id);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notification',
                message: 'Validation error: '.implode(', ', array_map(fn ($errors) => implode(', ', $errors), $e->errors())),
                type: 'error'
            );
        } catch (\Exception $e) {
            $this->dispatch('notification',
                message: 'Error creating sale: '.$e->getMessage(),
                type: 'error'
            );
            Log::error('Sale creation error: '.$e->getMessage());
        }
    }

    public function cancel()
    {
        $this->resetForm();
        $this->dispatch('close-create-form');
    }

    public function getProductSuggestions()
    {
        try {
            if (strlen($this->productSearch) < 1) {
                return [];
            }

            return Product::where('name', 'like', '%'.$this->productSearch.'%')
                ->orWhere('category', 'like', '%'.$this->productSearch.'%')
                // Removed SKU search here
                ->where('current_stock', '>', 0)
                ->limit(8)
                ->get(['id', 'name', 'category', 'price', 'current_stock']) // Removed 'sku' from select
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Product search error: '.$e->getMessage());

            return [];
        }
    }

    public function render()
    {
        $products = $this->getProductSuggestions();

        return view('livewire.sales.create-sale', [
            'products' => $products,
            'subtotal' => $this->getSubtotal(),
            'discountAmount' => $this->getDiscountAmount(),
            'totalAmount' => $this->getTotalAmount(),
        ]);
    }
}
