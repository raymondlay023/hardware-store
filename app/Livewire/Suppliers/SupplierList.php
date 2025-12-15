<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierList extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateForm = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('supplier-created')]
    public function supplierCreated()
    {
        $this->showCreateForm = false;
        $this->resetPage();
    }

    #[On('close-create-form')]
    public function closeCreateForm()
    {
        $this->showCreateForm = false;
    }

    public function deleteSupplier($id)
    {
        Supplier::find($id)->delete();
        $this->dispatch('notification', message: 'Supplier deleted successfully');
    }

    public function render()
    {
        $suppliers = Supplier::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('contact', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.suppliers.supplier-list', ['suppliers' => $suppliers]);
    }
}
