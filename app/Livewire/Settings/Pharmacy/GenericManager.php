<?php

namespace App\Livewire\Settings\Pharmacy;

use App\Models\DrugCategory;
use App\Models\DrugGeneric;
use Livewire\Component;
use Livewire\WithPagination;

class GenericManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $category_id;
    public $name;

    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'category_id' => 'required|exists:drug_categories,id',
        'name' => 'required|min:2|max:255|unique:drug_generics,name',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        if ($this->editingId) {
            $this->validate([
                'category_id' => 'required|exists:drug_categories,id',
                'name' => 'required|min:2|max:255|unique:drug_generics,name,' . $this->editingId,
            ]);

            $generic = DrugGeneric::find($this->editingId);
            $generic->update([
                'category_id' => $this->category_id,
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Generic updated successfully.']);
        } else {
            $this->validate();
            
            DrugGeneric::create([
                'category_id' => $this->category_id,
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Generic created successfully.']);
        }

        $this->cancelEdit();
    }

    public function edit($id)
    {
        $generic = DrugGeneric::findOrFail($id);
        $this->editingId = $id;
        $this->category_id = $generic->category_id;
        $this->name = $generic->name;
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            DrugGeneric::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Generic deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->category_id = null;
        $this->name = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $generics = DrugGeneric::with('category')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        $categories = DrugCategory::orderBy('name')->get();

        return view('livewire.settings.pharmacy.generic-manager', [
            'generics' => $generics,
            'categories' => $categories,
        ]);
    }
}
