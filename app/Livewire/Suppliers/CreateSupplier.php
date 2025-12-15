<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateSupplier extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:255')]
    public $contact = '';

    #[Validate('required|string|max:255')]
    public $payment_terms = '';

    public function save()
    {
        $this->validate();

        Supplier::create([
            'name' => $this->name,
            'contact' => $this->contact,
            'payment_terms' => $this->payment_terms,
        ]);

        $this->reset();
        $this->dispatch('supplier-created');
    }

    public function cancel()
    {
        $this->reset();
        $this->dispatch('close-create-form');
    }

    public function render()
    {
        return view('livewire.suppliers.create-supplier');
    }
}
