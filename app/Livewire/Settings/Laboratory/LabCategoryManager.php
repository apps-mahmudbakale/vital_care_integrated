<?php

namespace App\Livewire\Settings\Laboratory;

use App\Models\LabCategory;
use Livewire\Component;
use Livewire\WithPagination;

class LabCategoryManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $category = LabCategory::find($this->editingId);
            $category->update([
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Lab Category updated successfully.']);
        } else {
            LabCategory::create([
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Lab Category created successfully.']);
        }

        $this->dispatch('hide-category-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-category-modal');
    }

    public function edit($id)
    {
        $category = LabCategory::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->dispatch('show-category-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            LabCategory::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Lab Category deleted successfully.']);
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
        $categories = LabCategory::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.laboratory.lab-category-manager', [
            'categories' => $categories
        ]);
    }
}
