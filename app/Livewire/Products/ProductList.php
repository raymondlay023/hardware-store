<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateForm = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function deleteProduct($id)
    {
        Product::find($id)->delete();
        $this->dispatch('notification', message: 'Product deleted successfully');
    }

    public function render()
    {
        $products = Product::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('category', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.products.product-list', ['products' => $products]);
    }
}
