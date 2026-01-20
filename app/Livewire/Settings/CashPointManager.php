<?php

namespace App\Livewire\Settings;

use App\Models\CashPoint;
use Livewire\Component;
use Livewire\WithPagination;

class CashPointManager extends Component
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
            $cashPoint = CashPoint::find($this->editingId);
            $cashPoint->update([
                'name' => $this->name,
                'location' => $this->location,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Cash point updated successfully.']);
        } else {
            CashPoint::create([
                'name' => $this->name,
                'location' => $this->location,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Cash point created successfully.']);
        }

        $this->dispatch('hide-cashpoint-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-cashpoint-modal');
    }

    public function edit($id)
    {
        $cashPoint = CashPoint::findOrFail($id);
        $this->editingId = $id;
        $this->name = $cashPoint->name;
        $this->location = $cashPoint->location;
        $this->dispatch('show-cashpoint-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            CashPoint::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Cash point deleted successfully.']);
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
        $cashPoints = CashPoint::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.cash-point-manager', [
            'cashPoints' => $cashPoints
        ]);
    }
}
