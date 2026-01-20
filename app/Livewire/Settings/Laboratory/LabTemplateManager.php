<?php

namespace App\Livewire\Settings\Laboratory;

use App\Models\LabParameter;
use App\Models\LabTemplate;
use App\Models\LabTemplateItem;
use Livewire\Component;
use Livewire\WithPagination;

class LabTemplateManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    public $items = []; // [{lab_parameter_id, reference, unit}]

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'items.*.lab_parameter_id' => 'required|exists:lab_parameters,id',
        'items.*.reference' => 'nullable|string',
        'items.*.unit' => 'nullable|string',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function addItem()
    {
        $this->items[] = [
            'lab_parameter_id' => '',
            'reference' => '',
            'unit' => '',
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $template = LabTemplate::find($this->editingId);
            $template->update(['name' => $this->name]);
            
            // Sync items
            $template->items()->delete();
            foreach ($this->items as $item) {
                $template->items()->create($item);
            }
            
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Template updated successfully.']);
        } else {
            $template = LabTemplate::create(['name' => $this->name]);
            foreach ($this->items as $item) {
                $template->items()->create($item);
            }
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Template created successfully.']);
        }

        $this->dispatch('hide-template-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->addItem(); // Start with one empty item
        $this->dispatch('show-template-modal');
    }

    public function edit($id)
    {
        $template = LabTemplate::with('items')->findOrFail($id);
        $this->editingId = $id;
        $this->name = $template->name;
        $this->items = $template->items->map(function($item) {
            return [
                'lab_parameter_id' => $item->lab_parameter_id,
                'reference' => $item->reference,
                'unit' => $item->unit,
            ];
        })->toArray();

        $this->dispatch('show-template-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            LabTemplate::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Template deleted successfully.']);
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->items = [];
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $templates = LabTemplate::with('items.parameter')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $parameters = LabParameter::orderBy('name')->get();

        return view('livewire.settings.laboratory.lab-template-manager', [
            'templates' => $templates,
            'parameters' => $parameters,
        ]);
    }
}
