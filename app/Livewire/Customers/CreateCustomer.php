<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;

class CreateCustomer extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $type = 'retail';
    public $credit_limit = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:customers,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'type' => 'required|in:retail,wholesale,contractor',
        'credit_limit' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'name.required' => 'Customer name is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'type.required' => 'Please select a customer type',
        'type.in' => 'Invalid customer type',
        'credit_limit.required' => 'Credit limit is required',
        'credit_limit.numeric' => 'Credit limit must be a number',
        'credit_limit.min' => 'Credit limit cannot be negative',
    ];

    public function save()
    {
        $this->validate();

        Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'type' => $this->type,
            'credit_limit' => $this->credit_limit,
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Customer created successfully!'
        ]);

        $this->dispatch('customerCreated');
        $this->reset();
    }

    public function saveAndClose()
    {
        $this->save();
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.customers.create-customer');
    }
}
