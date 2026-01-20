<?php

namespace App\Livewire\Settings\Consultation;

use App\Models\Specialty;
use Livewire\Component;
use Livewire\WithPagination;

class SpecialtyManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $price = 0.00;
    public $follow_up_price = 0.00;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'price' => 'required|numeric|min:0',
        'follow_up_price' => 'required|numeric|min:0',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $specialty = Specialty::find($this->editingId);
            $specialty->update([
                'name' => $this->name,
                'price' => $this->price,
                'follow_up_price' => $this->follow_up_price,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Specialty updated successfully.']);
        } else {
            Specialty::create([
                'name' => $this->name,
                'price' => $this->price,
                'follow_up_price' => $this->follow_up_price,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Specialty created successfully.']);
        }

        $this->dispatch('hide-specialty-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-specialty-modal');
    }

    public function edit($id)
    {
        $specialty = Specialty::findOrFail($id);
        $this->editingId = $id;
        $this->name = $specialty->name;
        $this->price = $specialty->price;
        $this->follow_up_price = $specialty->follow_up_price;
        $this->dispatch('show-specialty-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            Specialty::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Specialty deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->name = '';
        $this->price = 0.00;
        $this->follow_up_price = 0.00;
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $specialties = Specialty::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.consultation.specialty-manager', [
            'specialties' => $specialties
        ]);
    }
}
