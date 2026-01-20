<?php

namespace App\Livewire\Settings;

use App\Models\ICD10;
use Livewire\Component;
use Livewire\WithPagination;

class ICD10Manager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $number;
    public $group;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'number' => 'required|min:1|max:50',
        'group' => 'nullable|max:255',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $icd = ICD10::find($this->editingId);
            $icd->update([
                'name' => $this->name,
                'number' => $this->number,
                'group' => $this->group,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'ICD10 updated successfully.']);
        } else {
            ICD10::create([
                'name' => $this->name,
                'number' => $this->number,
                'group' => $this->group,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'ICD10 created successfully.']);
        }

        $this->dispatch('hide-icd10-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-icd10-modal');
    }

    public function edit($id)
    {
        $icd = ICD10::findOrFail($id);
        $this->editingId = $id;
        $this->name = $icd->name;
        $this->number = $icd->number;
        $this->group = $icd->group;
        $this->dispatch('show-icd10-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            ICD10::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'ICD10 deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->name = '';
        $this->number = '';
        $this->group = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $icd10s = ICD10::where('name', 'like', "%{$this->search}%")
            ->orWhere('number', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(5);

        return view('livewire.settings.icd10-manager', [
            'icd10s' => $icd10s,
        ]);
    }
}
