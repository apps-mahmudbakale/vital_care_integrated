<?php

namespace App\Livewire\Settings\Radiology;

use App\Models\RadiologyCategory;
use App\Models\RadiologyTest;
use Livewire\Component;
use Livewire\WithPagination;

class RadiologyTestManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $radiology_category_id;
    public $name;
    public $price = 0.00;
    public $description;
    public $template;

    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'radiology_category_id' => 'required|exists:radiology_categories,id',
        'name' => 'required|min:2|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'template' => 'nullable|string',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $test = RadiologyTest::find($this->editingId);
            $test->update([
                'radiology_category_id' => $this->radiology_category_id,
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'template' => $this->template,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Radiology Test updated successfully.']);
        } else {
            RadiologyTest::create([
                'radiology_category_id' => $this->radiology_category_id,
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'template' => $this->template,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Radiology Test created successfully.']);
        }

        $this->dispatch('hide-test-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-test-modal');
    }

    public function edit($id)
    {
        $test = RadiologyTest::findOrFail($id);
        $this->editingId = $id;
        $this->radiology_category_id = $test->radiology_category_id;
        $this->name = $test->name;
        $this->price = $test->price;
        $this->description = $test->description;
        $this->template = $test->template;
        $this->dispatch('show-test-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            RadiologyTest::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Radiology Test deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->radiology_category_id = null;
        $this->name = '';
        $this->price = 0.00;
        $this->description = '';
        $this->template = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $tests = RadiologyTest::with('category')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $categories = RadiologyCategory::all();

        return view('livewire.settings.radiology.radiology-test-manager', [
            'tests' => $tests,
            'categories' => $categories
        ]);
    }
}
