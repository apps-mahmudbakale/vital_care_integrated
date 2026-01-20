<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Vital;
use Livewire\Component;

class PatientVitals extends Component
{
    public $patient;

    public function mount(Patient $patient)
    {
        $this->patient = $patient;
    }
    public function getVitalsHistoryProperty()
    {
        // Fetch readings with reference
        $readings = \App\Models\VitalReading::with('reference')
            ->where('patient_id', $this->patient->id)
            ->oldest()
            ->get();

        // Group by reference name
        $grouped = [];
        foreach ($readings as $reading) {
            $name = $reading->reference->name;
            $grouped[$name][] = [
                'x' => $reading->created_at->toIso8601String(),
                'y' => (float) $reading->value
            ];
        }

        return $grouped;
    }

    public function render()
    {
        return view('livewire.patient-vitals');
    }
}
