<?php

namespace App\Livewire\Settings\Consultation;

use App\Models\Clinic;
use App\Models\ConsultingRoom;
use Livewire\Component;
use Livewire\WithPagination;

class RoomManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $clinic_id;
    public $name;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'clinic_id' => 'required|exists:clinics,id',
        'name' => 'required|min:1|max:255',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $room = ConsultingRoom::find($this->editingId);
            $room->update([
                'clinic_id' => $this->clinic_id,
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Room updated successfully.']);
        } else {
            ConsultingRoom::create([
                'clinic_id' => $this->clinic_id,
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Room created successfully.']);
        }

        $this->dispatch('hide-room-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-room-modal');
    }

    public function edit($id)
    {
        $room = ConsultingRoom::findOrFail($id);
        $this->editingId = $id;
        $this->clinic_id = $room->clinic_id;
        $this->name = $room->name;
        $this->dispatch('show-room-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            ConsultingRoom::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Room deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->clinic_id = null;
        $this->name = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $rooms = ConsultingRoom::with('clinic')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        $clinics = Clinic::all();

        return view('livewire.settings.consultation.room-manager', [
            'rooms' => $rooms,
            'clinics' => $clinics
        ]);
    }
}
