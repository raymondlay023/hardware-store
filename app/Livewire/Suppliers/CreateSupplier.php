<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateSupplier extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:255')]
    public $contact_person = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:50')]
    public $phone = '';

    #[Validate('nullable|string')]
    public $address = '';

    #[Validate('nullable|string|max:100')]
    public $tax_id = '';

    #[Validate('nullable|string|max:255')]
    public $contact = '';

    #[Validate('nullable|string|max:255')]
    public $payment_terms = '';

    #[Validate('nullable|numeric|min:0')]
    public $credit_limit = 0;

    public function save()
    {
        $this->validate();

        Supplier::create([
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'tax_id' => $this->tax_id,
            'contact' => $this->contact,
            'payment_terms' => $this->payment_terms,
            'credit_limit' => $this->credit_limit ?: 0,
            'status' => 'active',
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
