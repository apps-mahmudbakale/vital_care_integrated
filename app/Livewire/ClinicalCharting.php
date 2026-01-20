<?php

namespace App\Livewire;

use App\Models\CheckIn;
use App\Models\Diagnosis;
use App\Models\Drug;
use App\Models\InvestigationOrder;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\VitalReading;
use App\Models\VitalReference;
use App\Models\ICD10;
use Livewire\Component;

class ClinicalCharting extends Component
{
    public CheckIn $checkIn;
    public Patient $patient;
    public $currentStep = 1;
    public $totalSteps = 6;

    // Step 1: Vitals
    public $vitalsData = [];
    public $vitalReferences = [];

    // Step 2: Complaints
    public $presenting_complaint;

    // Step 3: Diagnosis
    public $diagnoses = []; // now includes 'code'
    public $diagnosis_search = '';
    public $search_results_diagnosis = [];

    // Step 4: Treatment Plan
    public $treatment_plan;

    // Step 5: Investigations
    public $investigations = [];
    public $investigation_search = '';
    public $search_results_investigation = [];

    // Step 6: Prescriptions
    public $prescriptions = [];
    public $drug_search = '';
    public $search_results_drug = [];

    public function mount(CheckIn $checkIn)
    {
        $this->checkIn = $checkIn;
        $this->patient = $checkIn->patient;

        $this->presenting_complaint = (string) $checkIn->presenting_complaint;
        $this->treatment_plan = (string) $checkIn->treatment_plan;

        // Load latest vital readings for the patient to display in Tab 1 (Read-only)
        $this->vitalReferences = VitalReading::with('reference')
            ->where('patient_id', $this->patient->id)
            ->latest()
            ->get()
            ->unique('vital_reference_id');

        foreach ($this->vitalReferences as $reading) {
            $this->vitalsData[$reading->vital_reference_id] = $reading->value;
        }

        // Initialize rows
        if (empty($this->diagnoses)) $this->addDiagnosis();
        if (empty($this->investigations)) $this->addInvestigation();
        if (empty($this->prescriptions)) $this->addPrescription();
    }

    public function setStep($step)
    {
        $this->currentStep = $step;
    }

    public function nextStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->dispatch('step-changed');
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('step-changed');
        }
    }

    // Diagnosis Helpers
    public function addDiagnosis()
    {
        $this->diagnoses[] = ['icd_10_id' => '', 'name' => '', 'code' => '', 'status' => 'provisional', 'notes' => ''];
    }

    public function removeDiagnosis($index)
    {
        unset($this->diagnoses[$index]);
        $this->diagnoses = array_values($this->diagnoses);
    }

    public function updatedDiagnosisSearch()
    {
        if (strlen($this->diagnosis_search) > 2) {
            $this->search_results_diagnosis = ICD10::where('number', 'like', '%' . $this->diagnosis_search . '%')
                ->orWhere('name', 'like', '%' . $this->diagnosis_search . '%')
                ->limit(10)
                ->get(['id', 'number', 'name'])
                ->toArray();
        } else {
            $this->search_results_diagnosis = [];
        }
    }

    public function selectDiagnosis($index, $id, $name, $code)
    {
        // If clicking "Add Another" creates a new row, use the last index
        if ($index >= count($this->diagnoses)) {
            $this->addDiagnosis();
            $index = count($this->diagnoses) - 1;
        }

        $this->diagnoses[$index]['icd_10_id'] = $id;
        $this->diagnoses[$index]['name'] = $name;
        $this->diagnoses[$index]['code'] = $code;

        $this->search_results_diagnosis = [];
        $this->diagnosis_search = '';
    }

    // Other helpers remain unchanged
    public function addInvestigation()
    {
        $this->investigations[] = ['id' => '', 'type' => '', 'name' => '', 'notes' => ''];
    }

    public function removeInvestigation($index)
    {
        unset($this->investigations[$index]);
        $this->investigations = array_values($this->investigations);
    }

    public function addPrescription()
    {
        $this->prescriptions[] = ['drug_id' => '', 'name' => '', 'dosage' => '', 'frequency' => '', 'duration' => '', 'notes' => ''];
    }

    public function removePrescription($index)
    {
        unset($this->prescriptions[$index]);
        $this->prescriptions = array_values($this->prescriptions);
    }

    public function updatedInvestigationSearch()
    {
        if (strlen($this->investigation_search) > 2) {
            $labs = LabTest::where('name', 'like', '%' . $this->investigation_search . '%')
                ->limit(5)
                ->get()
                ->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'type' => 'lab']);

            $radiology = RadiologyTest::where('name', 'like', '%' . $this->investigation_search . '%')
                ->limit(5)
                ->get()
                ->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'type' => 'radiology']);

            $this->search_results_investigation = $labs->concat($radiology)->toArray();
        } else {
            $this->search_results_investigation = [];
        }
    }

    public function selectInvestigation($index, $id, $type, $name)
    {
        $this->investigations[$index]['id'] = $id;
        $this->investigations[$index]['type'] = $type;
        $this->investigations[$index]['name'] = $name;
        $this->search_results_investigation = [];
        $this->investigation_search = '';
    }

    public function updatedDrugSearch()
    {
        if (strlen($this->drug_search) > 2) {
            $this->search_results_drug = Drug::where('name', 'like', '%' . $this->drug_search . '%')
                ->orWhereHas('generic', function ($q) {
                    $q->where('name', 'like', '%' . $this->drug_search . '%');
                })
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->search_results_drug = [];
        }
    }

    public function selectDrug($index, $id, $name)
    {
        $this->prescriptions[$index]['drug_id'] = $id;
        $this->prescriptions[$index]['name'] = $name;
        $this->search_results_drug = [];
        $this->drug_search = '';
    }

    public function render()
    {
        return view('livewire.clinical-charting');
    }
}