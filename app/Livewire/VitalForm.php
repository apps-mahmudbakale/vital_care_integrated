<?php

namespace App\Livewire;

use App\Models\Vital;
use App\Models\VitalReading;
use App\Models\Patient; // Ensure Patient model is imported
use Livewire\Component;

class VitalForm extends Component
{
    public $patient_id;
    public $readings = [];
    public $references = [];

    protected $rules = [
        'patient_id' => 'required|exists:patients,id',
        'readings.*.vital_reference_id' => 'required|exists:vital_references,id',
        'readings.*.value' => 'required|numeric',
    ];

    public function mount($patient = null)
    {
        if ($patient) {
            $this->patient_id = $patient instanceof Patient ? $patient->id : $patient;
        }
        $this->references = \App\Models\VitalReference::all();
        $this->addReading();
    }

    public function addReading()
    {
        $this->readings[] = ['vital_reference_id' => '', 'value' => ''];
    }

    public function removeReading($index)
    {
        unset($this->readings[$index]);
        $this->readings = array_values($this->readings);
    }

    public function save()
    {
        $this->validate();

        foreach ($this->readings as $reading) {
            VitalReading::create([
                'patient_id' => $this->patient_id,
                'user_id' => auth()->id(),
                'vital_reference_id' => $reading['vital_reference_id'],
                'value' => $reading['value'],
            ]);
        }

        $this->reset(['readings']);
        $this->addReading();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Vitals recorded successfully.']);
        $this->dispatch('refreshVitals'); 
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.vital-form');
    }
}
