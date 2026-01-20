<?php

namespace App\Livewire;

use App\Models\Allergy;
use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AllergyForm extends Component
{
    public $patient;
    public $allergy;
    public $allergy_name;
    public $reaction;
    public $severity = 'moderate';

    protected $rules = [
        'allergy_name' => 'required|string|max:255',
        'reaction' => 'nullable|string|max:255',
        'severity' => 'required|in:mild,moderate,severe',
    ];

    public function mount(Patient $patient, Allergy $allergy = null)
    {
        $this->patient = $patient;
        $this->allergy = $allergy;

        if ($allergy) {
            $this->allergy_name = $allergy->allergy_name;
            $this->reaction = $allergy->reaction;
            $this->severity = $allergy->severity;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->allergy) {
            $this->allergy->update([
                'allergy_name' => $this->allergy_name,
                'reaction' => $this->reaction,
                'severity' => $this->severity,
            ]);
            $message = 'Allergy updated successfully.';
        } else {
            Allergy::create([
                'patient_id' => $this->patient->id,
                'allergy_name' => $this->allergy_name,
                'reaction' => $this->reaction,
                'severity' => $this->severity,
                'user_id' => Auth::id(),
            ]);
            $message = 'Allergy added successfully.';
        }

        $this->dispatch('allergyAdded');
        $this->dispatch('close-modal');

        session()->flash('success', $message);
        
        return redirect()->route('app.patients.show', $this->patient->id);
    }

    public function render()
    {
        return view('livewire.allergy-form');
    }
}
