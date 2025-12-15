<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditSupplier extends Component
{
    public $supplierId = null;
    public $supplier = null;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:255')]
    public $contact = '';

    #[Validate('required|string|max:255')]
    public $payment_terms = '';

    #[On('edit-supplier')]
    public function editSupplier($id)
    {
        $this->supplierId = $id;
        $this->supplier = Supplier::find($id);
        
        if ($this->supplier) {
            $this->name = $this->supplier->name;
            $this->contact = $this->supplier->contact;
            $this->payment_terms = $this->supplier->payment_terms;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->supplier) {
            $this->supplier->update([
                'name' => $this->name,
                'contact' => $this->contact,
                'payment_terms' => $this->payment_terms,
            ]);
        }

        $this->reset();
        $this->dispatch('supplier-updated');
    }

    public function cancel()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.suppliers.edit-supplier');
    }
}
