<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Attributes\On;
use Livewire\Component;

class SaleDetails extends Component
{
    public $saleId = null;
    public $sale = null;
    public $showModal = false;

    #[On('view-sale-details')]
    public function viewSaleDetails($id)
    {
        $this->saleId = $id;
        $this->sale = Sale::with('saleItems.product')->find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('saleId', 'sale');
    }

    public function render()
    {
        return view('livewire.sales.sale-details');
    }
}
