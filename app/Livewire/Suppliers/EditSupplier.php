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

    #[Validate('in:active,inactive,suspended')]
    public $status = 'active';

    #[On('edit-supplier')]
    public function editSupplier($id)
    {
        $this->supplierId = $id;
        $this->supplier = Supplier::find($id);
        
        if ($this->supplier) {
            $this->name = $this->supplier->name;
            $this->contact_person = $this->supplier->contact_person;
            $this->email = $this->supplier->email;
            $this->phone = $this->supplier->phone;
            $this->address = $this->supplier->address;
            $this->tax_id = $this->supplier->tax_id;
            $this->contact = $this->supplier->contact;
            $this->payment_terms = $this->supplier->payment_terms;
            $this->credit_limit = $this->supplier->credit_limit;
            $this->status = $this->supplier->status;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->supplier) {
            $this->supplier->update([
                'name' => $this->name,
                'contact_person' => $this->contact_person,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'tax_id' => $this->tax_id,
                'contact' => $this->contact,
                'payment_terms' => $this->payment_terms,
                'credit_limit' => $this->credit_limit ?: 0,
                'status' => $this->status,
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
