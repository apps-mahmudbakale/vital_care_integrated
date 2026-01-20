<?php

namespace App\Livewire\Settings\Consultation;

use App\Models\ConsultationTemplate;
use App\Models\Specialty;
use Livewire\Component;
use Livewire\WithPagination;

class TemplateManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $specialty_id;
    public $name;
    public $content;
    public $search = '';
    public $editingId = null;
    public $deletingId = null;

    protected $rules = [
        'specialty_id' => 'required|exists:specialties,id',
        'name' => 'required|min:2|max:255',
        'content' => 'nullable|string',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $template = ConsultationTemplate::find($this->editingId);
            $template->update([
                'specialty_id' => $this->specialty_id,
                'name' => $this->name,
                'content' => $this->content,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Template updated successfully.']);
        } else {
            ConsultationTemplate::create([
                'specialty_id' => $this->specialty_id,
                'name' => $this->name,
                'content' => $this->content,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Template created successfully.']);
        }

        $this->dispatch('hide-template-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-template-modal');
    }

    public function edit($id)
    {
        $template = ConsultationTemplate::findOrFail($id);
        $this->editingId = $id;
        $this->specialty_id = $template->specialty_id;
        $this->name = $template->name;
        $this->content = $template->content;
        $this->dispatch('show-template-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            ConsultationTemplate::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Template deleted successfully.']);
        }
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->specialty_id = null;
        $this->name = '';
        $this->content = '';
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $templates = ConsultationTemplate::with('specialty')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        $specialties = Specialty::all();

        return view('livewire.settings.consultation.template-manager', [
            'templates' => $templates,
            'specialties' => $specialties
        ]);
    }
}
