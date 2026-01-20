<?php

namespace App\Livewire\Settings\Laboratory;

use App\Models\LabParameter;
use Livewire\Component;
use Livewire\WithPagination;

class LabParameterManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:1|max:255|unique:lab_parameters,name',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['name'] = 'required|min:1|max:255|unique:lab_parameters,name,' . $this->editingId;
        }

        $this->validate($rules);

        if ($this->editingId) {
            LabParameter::find($this->editingId)->update([
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Parameter updated successfully.']);
        } else {
            LabParameter::create([
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Parameter created successfully.']);
        }

        $this->dispatch('hide-parameter-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-parameter-modal');
    }

    public function edit($id)
    {
        $parameter = LabParameter::findOrFail($id);
        $this->editingId = $id;
        $this->name = $parameter->name;
        $this->dispatch('show-parameter-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            LabParameter::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Parameter deleted successfully.']);
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $parameters = LabParameter::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.settings.laboratory.lab-parameter-manager', [
            'parameters' => $parameters,
        ]);
    }
}
