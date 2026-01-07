<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerList extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $filterType = 'all'; // all, retail, wholesale, contractor

    public $showCreateForm = false;

    public function mount()
    {
        $this->authorize('viewAny', Customer::class);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Customer::query();

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Type filter
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $customers = $query->latest()->paginate(15);

        return view('livewire.customers.customer-list', [
            'customers' => $customers,
        ]);
    }
}
