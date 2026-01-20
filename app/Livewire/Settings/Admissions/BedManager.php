<?php

namespace App\Livewire\Settings\Admissions;

use App\Models\Bed;
use App\Models\Ward;
use Livewire\Component;
use Livewire\WithPagination;

class BedManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $ward_id;
    public $name;
    public $price;
    public $status = 'available';
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'ward_id' => 'required|exists:wards,id',
        'name' => 'required|min:1|max:255',
        'price' => 'required|numeric|min:0',
        'status' => 'required|in:available,occupied,maintenance',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $bed = Bed::find($this->editingId);
            $bed->update([
                'ward_id' => $this->ward_id,
                'name' => $this->name,
                'price' => $this->price,
                'status' => $this->status,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Bed updated successfully.']);
        } else {
            Bed::create([
                'ward_id' => $this->ward_id,
                'name' => $this->name,
                'price' => $this->price,
                'status' => $this->status,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Bed created successfully.']);
        }

        $this->dispatch('hide-bed-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-bed-modal');
    }

    public function edit($id)
    {
        $bed = Bed::findOrFail($id);
        $this->editingId = $id;
        $this->ward_id = $bed->ward_id;
        $this->name = $bed->name;
        $this->price = $bed->price;
        $this->status = $bed->status;
        $this->dispatch('show-bed-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            Bed::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Bed deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->ward_id = null;
        $this->name = '';
        $this->price = '';
        $this->status = 'available';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $beds = Bed::with('ward')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        $wards = Ward::all();

        return view('livewire.settings.admissions.bed-manager', [
            'beds' => $beds,
            'wards' => $wards
        ]);
    }
}
