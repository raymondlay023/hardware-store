<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;

class EditCustomer extends Component
{
    public Customer $customer;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $type = 'retail';
    public $credit_limit = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'type' => 'required|in:retail,wholesale,contractor',
        'credit_limit' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'name.required' => 'Customer name is required',
        'email.email' => 'Please enter a valid email address',
        'type.required' => 'Please select a customer type',
        'type.in' => 'Invalid customer type',
        'credit_limit.required' => 'Credit limit is required',
        'credit_limit.numeric' => 'Credit limit must be a number',
        'credit_limit.min' => 'Credit limit cannot be negative',
    ];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->type = $customer->type;
        $this->credit_limit = $customer->credit_limit;
    }

    public function save()
    {
        // Validate email uniqueness excluding current customer
        $this->validate([
            'email' => 'nullable|email|max:255|unique:customers,email,' . $this->customer->id,
        ] + $this->rules);

        $this->customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'type' => $this->type,
            'credit_limit' => $this->credit_limit,
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Customer updated successfully!'
        ]);

        $this->dispatch('customerUpdated');
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.customers.edit-customer');
    }
}
