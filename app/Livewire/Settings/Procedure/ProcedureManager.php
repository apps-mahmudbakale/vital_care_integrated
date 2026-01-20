<?php

namespace App\Livewire\Settings\Procedure;

use App\Models\Procedure;
use App\Models\ProcedureCategory;
use Livewire\Component;
use Livewire\WithPagination;

class ProcedureManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $procedure_category_id;
    public $name;
    public $price = 0.00;
    public $description;
    public $template;

    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'procedure_category_id' => 'required|exists:procedure_categories,id',
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
            $procedure = Procedure::find($this->editingId);
            $procedure->update([
                'procedure_category_id' => $this->procedure_category_id,
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'template' => $this->template,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Procedure updated successfully.']);
        } else {
            Procedure::create([
                'procedure_category_id' => $this->procedure_category_id,
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'template' => $this->template,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Procedure created successfully.']);
        }

        $this->dispatch('hide-procedure-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-procedure-modal');
    }

    public function edit($id)
    {
        $procedure = Procedure::findOrFail($id);
        $this->editingId = $id;
        $this->procedure_category_id = $procedure->procedure_category_id;
        $this->name = $procedure->name;
        $this->price = $procedure->price;
        $this->description = $procedure->description;
        $this->template = $procedure->template;
        $this->dispatch('show-procedure-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            Procedure::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Procedure deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->procedure_category_id = null;
        $this->name = '';
        $this->price = 0.00;
        $this->description = '';
        $this->template = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $procedures = Procedure::with('category')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $categories = ProcedureCategory::all();

        return view('livewire.settings.procedure.procedure-manager', [
            'procedures' => $procedures,
            'categories' => $categories
        ]);
    }
}
