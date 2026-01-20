<?php

namespace App\Livewire\Settings\Consultation;

use App\Models\AppointmentType;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentTypeManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $duration = 30;
    public $price = 0.00;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'duration' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $type = AppointmentType::find($this->editingId);
            $type->update([
                'name' => $this->name,
                'duration' => $this->duration,
                'price' => $this->price,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Appointment type updated successfully.']);
        } else {
            AppointmentType::create([
                'name' => $this->name,
                'duration' => $this->duration,
                'price' => $this->price,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Appointment type created successfully.']);
        }

        $this->dispatch('hide-appointment-type-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-appointment-type-modal');
    }

    public function edit($id)
    {
        $type = AppointmentType::findOrFail($id);
        $this->editingId = $id;
        $this->name = $type->name;
        $this->duration = $type->duration;
        $this->price = $type->price;
        $this->dispatch('show-appointment-type-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            AppointmentType::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Appointment type deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->name = '';
        $this->duration = 30;
        $this->price = 0.00;
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $types = AppointmentType::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.consultation.appointment-type-manager', [
            'types' => $types
        ]);
    }
}
