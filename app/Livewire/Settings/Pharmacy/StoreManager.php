<?php

namespace App\Livewire\Settings\Pharmacy;

use App\Models\PharmacyStore;
use Livewire\Component;
use Livewire\WithPagination;

class StoreManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $location;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'location' => 'nullable|max:255',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $store = PharmacyStore::find($this->editingId);
            $store->update([
                'name' => $this->name,
                'location' => $this->location,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Pharmacy Store updated successfully.']);
        } else {
            PharmacyStore::create([
                'name' => $this->name,
                'location' => $this->location,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Pharmacy Store created successfully.']);
        }

        $this->dispatch('hide-store-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-store-modal');
    }

    public function edit($id)
    {
        $store = PharmacyStore::findOrFail($id);
        $this->editingId = $id;
        $this->name = $store->name;
        $this->location = $store->location;
        $this->dispatch('show-store-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            PharmacyStore::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Pharmacy Store deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->name = '';
        $this->location = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $stores = PharmacyStore::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.pharmacy.store-manager', [
            'stores' => $stores
        ]);
    }
}
