<?php

namespace App\Livewire\Settings;

use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentMethodManager extends Component
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
            $pm = PaymentMethod::find($this->editingId);
            $pm->update([
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Payment method updated successfully.']);
        } else {
            PaymentMethod::create([
                'name' => $this->name,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Payment method created successfully.']);
        }

        $this->dispatch('hide-payment-modal');
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('show-payment-modal');
    }

    public function edit($id)
    {
        $pm = PaymentMethod::findOrFail($id);
        $this->editingId = $id;
        $this->name = $pm->name;
        $this->dispatch('show-payment-modal');
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
    }

    public function delete()
    {
        if ($this->deletingId) {
            PaymentMethod::destroy($this->deletingId);
            $this->deletingId = null;
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Payment method deleted successfully.']);
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
        $paymentMethods = PaymentMethod::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(5);

        return view('livewire.settings.payment-method-manager', [
            'paymentMethods' => $paymentMethods
        ]);
    }
}
