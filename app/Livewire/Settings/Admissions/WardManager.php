<?php

namespace App\Livewire\Settings\Admissions;

use App\Models\Ward;
use Livewire\Component;
use Livewire\WithPagination;

class WardManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $location;
    public $description;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'location' => 'nullable|max:255',
        'description' => 'nullable|max:500',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $ward = Ward::find($this->editingId);
            $ward->update([
                'name' => $this->name,
                'location' => $this->location,
                'description' => $this->description,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Ward updated successfully.']);
        } else {
            Ward::create([
                'name' => $this->name,
                'location' => $this->location,
                'description' => $this->description,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Ward created successfully.']);
        }

        $this->dispatch('hide-ward-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-ward-modal');
    }

    public function edit($id)
    {
        $ward = Ward::findOrFail($id);
        $this->editingId = $id;
        $this->name = $ward->name;
        $this->location = $ward->location;
        $this->description = $ward->description;
        $this->dispatch('show-ward-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            Ward::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Ward deleted successfully.']);
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
        $this->description = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $wards = Ward::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.admissions.ward-manager', [
            'wards' => $wards
        ]);
    }
}
