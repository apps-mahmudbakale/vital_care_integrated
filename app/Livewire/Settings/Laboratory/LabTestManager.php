<?php

namespace App\Livewire\Settings\Laboratory;

use App\Models\LabCategory;
use App\Models\LabTest;
use App\Models\LabTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class LabTestManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lab_category_id;
    public $name;
    public $price = 0.00;
    public $template;

    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'lab_category_id' => 'required|exists:lab_categories,id',
        'name' => 'required|min:2|max:255',
        'price' => 'required|numeric|min:0',
        'template' => 'required|exists:lab_templates,id',
    ];
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $test = LabTest::find($this->editingId);
            $test->update([
                'lab_category_id' => $this->lab_category_id,
                'name' => $this->name,
                'price' => $this->price,
                'template_id' => $this->template,
            ]);


            $this->dispatch('notify', ['type' => 'success', 'message' => 'Lab Test updated successfully.']);
        } else {
            LabTest::create([ // Removed $test =
                'lab_category_id' => $this->lab_category_id,
                'name' => $this->name,
                'price' => $this->price,
                'template_id' => $this->template,
            ]);


            $this->dispatch('notify', ['type' => 'success', 'message' => 'Lab Test created successfully.']);
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
        $test = LabTest::findOrFail($id);
        $this->editingId = $id;
        $this->lab_category_id = $test->lab_category_id;
        $this->name = $test->name;
        $this->price = $test->price;
        $this->template = $test->template_id;

        $this->dispatch('show-test-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            LabTest::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Lab Test deleted successfully.']);
        }
    }

    public function resetForm()
    {
        $this->lab_category_id = null;
        $this->name = '';
        $this->price = 0.00;
        $this->template = null;
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $tests = LabTest::with(['category'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $categories = LabCategory::all();
        $templates = LabTemplate::all();

        return view('livewire.settings.laboratory.lab-test-manager', [
            'tests' => $tests,
            'categories' => $categories,
            'templates' => $templates,
        ]);
    }
}
