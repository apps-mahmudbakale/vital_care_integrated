<?php

namespace App\Livewire;

use App\Models\LabRequest;
use App\Models\LabTest;
use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LabRequestForm extends Component
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
            'test_id' => '',
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
            'rows.*.test_id' => 'required',
            'rows.*.priority' => 'required',
        ], [
            'rows.*.test_id.required' => 'Please select a test.',
        ]);

        $serviceHandler = new \App\Services\ServiceRequestHandler();
        $billRef = str()->random(6);

        foreach ($this->rows as $row) {
            LabRequest::create([
                'patient_id' => $this->patient->id,
                'test_id' => $row['test_id'],
                'user_id' => Auth::id(),
                'request_note' => $row['request_note'],
                'request_ref' => $billRef,
                'priority' => $row['priority'],
                'status' => $this->status,
            ]);

            // Create Billing Record using ServiceHandler
            $test = LabTest::find($row['test_id']);
            $serviceHandler->handleServiceRequest(
                $test->name, 
                $this->patient->id, 
                'laboratory',
                $billRef,
                1
            );
        }

        session()->flash('success', 'Lab tests requested and billed successfully.');
        
        return redirect()->route('app.patients.show', $this->patient->id);
    }

    public function render()
    {
        return view('livewire.lab-request-form', [
            'labTests' => LabTest::orderBy('name')->get()
        ]);
    }
}
