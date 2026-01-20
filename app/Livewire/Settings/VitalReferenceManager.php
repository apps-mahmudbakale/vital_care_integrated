<?php

namespace App\Livewire\Settings;

use App\Models\VitalReference;
use Livewire\Component;
use Livewire\WithPagination;

class VitalReferenceManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $min_value;
    public $max_value;
    public $unit;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'min_value' => 'nullable|max:255',
        'max_value' => 'nullable|max:255',
        'unit' => 'nullable|max:50',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $reference = VitalReference::find($this->editingId);
            $reference->update([
                'name' => $this->name,
                'min_value' => $this->min_value,
                'max_value' => $this->max_value,
                'unit' => $this->unit,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Reference range updated successfully.']);
        } else {
            VitalReference::create([
                'name' => $this->name,
                'min_value' => $this->min_value,
                'max_value' => $this->max_value,
                'unit' => $this->unit,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Reference range created successfully.']);
        }

        $this->dispatch('hide-ref-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-ref-modal');
    }

    public function edit($id)
    {
        $reference = VitalReference::findOrFail($id);
        $this->editingId = $id;
        $this->name = $reference->name;
        $this->min_value = $reference->min_value;
        $this->max_value = $reference->max_value;
        $this->unit = $reference->unit;
        $this->dispatch('show-ref-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            VitalReference::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Reference range deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->name = '';
        $this->min_value = '';
        $this->max_value = '';
        $this->unit = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $references = VitalReference::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.vital-reference-manager', [
            'references' => $references
        ]);
    }
}
