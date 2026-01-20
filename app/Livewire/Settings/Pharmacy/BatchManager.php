<?php

namespace App\Livewire\Settings\Pharmacy;

use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\PharmacyStore;
use Livewire\Component;
use Livewire\WithPagination;

class BatchManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Header Fields
    public $store_id;
    public $batch_number;

    // Repeater Items
    public $items = [];

    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'store_id' => 'required|exists:pharmacy_stores,id',
        'batch_number' => 'required|string|max:255',
        'items' => 'required|array|min:1',
        'items.*.drug_id' => 'required|exists:drugs,id',
        'items.*.quantity' => 'required|integer|min:0',
        'items.*.expiry_date' => 'required|date',
    ];

    public function mount()
    {
        $this->addItem();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function addItem()
    {
        $this->items[] = ['drug_id' => '', 'quantity' => '', 'expiry_date' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            // Edit Mode: Update single record
            // We assume items[0] holds the data
            $batch = DrugBatch::find($this->editingId);
            $batch->update([
                'store_id' => $this->store_id,
                'batch_number' => $this->batch_number,
                'drug_id' => $this->items[0]['drug_id'],
                'quantity' => $this->items[0]['quantity'],
                'expiry_date' => $this->items[0]['expiry_date'],
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Batch updated successfully.']);
        } else {
            // Create Mode: Loop through items
            foreach ($this->items as $item) {
                DrugBatch::create([
                    'store_id' => $this->store_id,
                    'batch_number' => $this->batch_number,
                    'drug_id' => $item['drug_id'],
                    'quantity' => $item['quantity'],
                    'expiry_date' => $item['expiry_date'],
                ]);
            }
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Batches created successfully.']);
        }

        $this->cancelEdit();
    }

    public function edit($id)
    {
        $batch = DrugBatch::findOrFail($id);
        $this->editingId = $id;
        $this->store_id = $batch->store_id;
        $this->batch_number = $batch->batch_number;
        
        // Populate single item for edit
        $this->items = [[
            'drug_id' => $batch->drug_id,
            'quantity' => $batch->quantity,
            'expiry_date' => $batch->expiry_date
        ]];
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            DrugBatch::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Batch deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->store_id = null;
        $this->batch_number = '';
        $this->items = [];
        $this->addItem(); // Reset to one empty item
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $batches = DrugBatch::with(['drug', 'store'])
            ->where('batch_number', 'like', '%' . $this->search . '%')
            ->orWhereHas('drug', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(5);

        $drugs = Drug::orderBy('name')->get();
        $stores = PharmacyStore::orderBy('name')->get();

        return view('livewire.settings.pharmacy.batch-manager', [
            'batches' => $batches,
            'drugs' => $drugs,
            'stores' => $stores,
        ]);
    }
}
