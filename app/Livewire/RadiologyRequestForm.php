<?php

namespace App\Livewire;

use App\Models\RadiologyRequest;
use App\Models\RadiologyTest;
use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RadiologyRequestForm extends Component
{
    public $patient;
    public $rows = [];
    public $status = 'Pending';

    public function mount(Patient $patient)
    {
        $this->patient = $patient;
        $this->addRow();
    }

    public function addRow()
    {
        $this->rows[] = [
            'radiology_test_id' => '',
            'priority' => 'Regular',
            'request_note' => '',
        ];
        $this->dispatch('rowAdded');
    }

    public function removeRow($index)
    {
        if (count($this->rows) > 1) {
            unset($this->rows[$index]);
            $this->rows = array_values($this->rows);
        }
    }

    public function save()
    {
        $this->validate([
            'rows.*.radiology_test_id' => 'required',
            'rows.*.priority' => 'required',
        ], [
            'rows.*.radiology_test_id.required' => 'Please select a test.',
        ]);

        $serviceHandler = new \App\Services\ServiceRequestHandler();
        $billRef = str()->random(6);

        foreach ($this->rows as $row) {
            RadiologyRequest::create([
                'patient_id' => $this->patient->id,
                'radiology_test_id' => $row['radiology_test_id'],
                'user_id' => Auth::id(),
                'request_note' => $row['request_note'],
                'priority' => $row['priority'],
                'status' => $this->status,
            ]);

            // Create Billing Record using ServiceHandler
            $test = RadiologyTest::find($row['radiology_test_id']);
            $serviceHandler->handleServiceRequest(
                $test->name, 
                $this->patient->id, 
                'radiology',
                $billRef,
                1
            );
        }

        session()->flash('success', 'Radiology tests requested and billed successfully.');
        
        return redirect()->route('app.patients.show', $this->patient->id);
    }

    public function render()
    {
        return view('livewire.radiology-request-form', [
            'radiologyTests' => RadiologyTest::orderBy('name')->get()
        ]);
    }
}
