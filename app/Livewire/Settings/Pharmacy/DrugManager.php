<?php

namespace App\Livewire\Settings\Pharmacy;

use App\Models\Drug;
use App\Models\DrugCategory;
use Livewire\Component;
use Livewire\WithPagination;

class DrugManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $category_id;
    public $generic_id;
    public $name;
    public $weight;
    public $price = '';
    public $threshold = '10';
    public $is_active = true;

    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'category_id' => 'required|exists:drug_categories,id',
        'generic_id' => 'nullable|exists:drug_generics,id',
        'name' => 'required|min:2|max:255',
        'weight' => 'nullable|string|max:255',
        'price' => 'required|string',
        'threshold' => 'required|string',
        'is_active' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $drug = Drug::find($this->editingId);
            $drug->update([
                'category_id' => $this->category_id,
                'generic_id' => $this->generic_id,
                'name' => $this->name,
                'weight' => $this->weight,
                'price' => $this->price,
                'threshold' => $this->threshold,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Drug updated successfully.']);
        } else {
            Drug::create([
                'category_id' => $this->category_id,
                'generic_id' => $this->generic_id,
                'name' => $this->name,
                'weight' => $this->weight,
                'price' => $this->price,
                'threshold' => $this->threshold,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Drug created successfully.']);
        }

        $this->dispatch('hide-drug-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-drug-modal');
    }

    public function edit($id)
    {
        $drug = Drug::findOrFail($id);
        $this->editingId = $id;
        $this->category_id = $drug->category_id;
        $this->generic_id = $drug->generic_id;
        $this->name = $drug->name;
        $this->weight = $drug->weight;
        $this->price = $drug->price;
        $this->threshold = $drug->threshold;
        $this->is_active = $drug->is_active;
        $this->dispatch('show-drug-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            Drug::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Drug deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->category_id = null;
        $this->generic_id = null;
        $this->name = '';
        $this->weight = '';
        $this->price = '';
        $this->threshold = '10';
        $this->is_active = true;
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $drugs = Drug::with(['category', 'generic'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhereHas('generic', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(5);

        $categories = DrugCategory::all();
        $generics = \App\Models\DrugGeneric::orderBy('name')->get();

        return view('livewire.settings.pharmacy.drug-manager', [
            'drugs' => $drugs,
            'categories' => $categories,
            'generics' => $generics,
        ]);
    }
}
