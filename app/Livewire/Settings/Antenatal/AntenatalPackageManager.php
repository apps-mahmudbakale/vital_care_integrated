<?php

namespace App\Livewire\Settings\Antenatal;

use App\Models\AntenatalPackage;
use Livewire\Component;
use Livewire\WithPagination;

class AntenatalPackageManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $price = 0.00;
    public $description;
    public $status = true;

    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'status' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $package = AntenatalPackage::find($this->editingId);
            $package->update([
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'status' => $this->status,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Antenatal Package updated successfully.']);
        } else {
            AntenatalPackage::create([
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'status' => $this->status,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Antenatal Package created successfully.']);
        }

        $this->dispatch('hide-package-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-package-modal');
    }

    public function edit($id)
    {
        $package = AntenatalPackage::findOrFail($id);
        $this->editingId = $id;
        $this->name = $package->name;
        $this->price = $package->price;
        $this->description = $package->description;
        $this->status = $package->status;
        $this->dispatch('show-package-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            AntenatalPackage::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Antenatal Package deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->price = 0.00;
        $this->description = '';
        $this->status = true;
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $packages = AntenatalPackage::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.settings.antenatal.antenatal-package-manager', [
            'packages' => $packages
        ]);
    }
}
