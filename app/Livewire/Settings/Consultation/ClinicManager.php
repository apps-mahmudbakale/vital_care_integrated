<?php

namespace App\Livewire\Settings\Consultation;

use App\Models\Clinic;
use Livewire\Component;
use Livewire\WithPagination;

class ClinicManager extends Component
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
            $clinic = Clinic::find($this->editingId);
            $clinic->update([
                'name' => $this->name,
                'location' => $this->location,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Clinic updated successfully.']);
        } else {
            Clinic::create([
                'name' => $this->name,
                'location' => $this->location,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Clinic created successfully.']);
        }

        $this->dispatch('hide-clinic-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-clinic-modal');
    }

    public function edit($id)
    {
        $clinic = Clinic::findOrFail($id);
        $this->editingId = $id;
        $this->name = $clinic->name;
        $this->location = $clinic->location;
        $this->dispatch('show-clinic-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            Clinic::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Clinic deleted successfully.']);
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
        $clinics = Clinic::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.consultation.clinic-manager', [
            'clinics' => $clinics
        ]);
    }
}
