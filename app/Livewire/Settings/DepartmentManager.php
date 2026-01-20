<?php

namespace App\Livewire\Settings;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $search = '';
    public $editingId = null;
    public $isConfirmingDeletion = false;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255|unique:departments,name',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['name'] = 'required|min:2|max:255|unique:departments,name,' . $this->editingId;
        }

        $this->validate($rules);

        if ($this->editingId) {
            $department = Department::find($this->editingId);
            $department->update(['name' => $this->name]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Department updated successfully.']);
        } else {
            Department::create(['name' => $this->name]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Department created successfully.']);
        }

        $this->dispatch('hide-dept-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-dept-modal');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $this->editingId = $id;
        $this->name = $department->name;
        $this->dispatch('show-dept-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            Department::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Department deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->name = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $departments = Department::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.department-manager', [
            'departments' => $departments
        ]);
    }
}
