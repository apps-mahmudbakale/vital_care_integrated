<?php

namespace App\Livewire;

use Livewire\Component;

class DrugRequestForm extends Component
{
    public $patient;
    public $drugs = [];
    public $requestedDrugs = []; // Array of {drug_id, quantity, dosage}
    
    public $selected_drug_id;
    public $quantity = 1;
    public $dosage;
    public $instruction;

    protected $rules = [
        'selected_drug_id' => 'required|exists:drugs,id',
        'quantity' => 'required|integer|min:1',
    ];

    public function mount($patient)
    {
        $this->patient = $patient;
        $this->drugs = \App\Models\Drug::where('status', 'active')->orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $drug = \App\Models\Drug::find($this->selected_drug_id);
        $billingRef = str()->random(6);
        
        $serviceHandler = app(\App\Services\ServiceRequestHandler::class);

        // Process Billing
        $serviceHandler->handleServiceRequest(
            $drug->name,
            $this->patient->id,
            'pharmacy',
            $billingRef,
            $this->quantity
        );

        // Create Drug Request
        \App\Models\DrugRequest::create([
            'patient_id' => $this->patient->id,
            'drug_id' => $this->selected_drug_id,
            'quantity' => $this->quantity,
            'dosage' => $this->dosage,
            'instruction' => $this->instruction,
            'user_id' => auth()->id(),
            'bill_ref' => $billingRef,
            'status' => 'pending',
        ]);

        $this->dispatch('swal:modal', [
            'type' => 'success',
            'title' => 'Success',
            'text' => 'Drug Request submitted and billed successfully.',
        ]);

        return redirect()->route('app.patients.show', $this->patient->id);
    }

    public function render()
    {
        return view('livewire.drug-request-form');
    }
}
