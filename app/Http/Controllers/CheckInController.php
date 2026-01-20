<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use Illuminate\Http\Request;
use App\Models\VitalReading;
use App\Models\Diagnosis;
use App\Models\InvestigationOrder;
use App\Models\DrugRequest;
use App\Models\LabTest;
use App\Models\RadiologyTest;
use App\Models\ICD10;
use App\Models\ProcedureOrder;
use App\Services\ServiceRequestHandler;
use App\Models\LabRequest;
use App\Models\RadiologyRequest;

class CheckInController extends Controller
{
    public function complaint(CheckIn $checkIn)
    {
        $currentStep = 1;
        $totalSteps = 6;
        $patient = $checkIn->patient;
        $vitalReferences = VitalReading::where('patient_id', $patient->id)
            ->whereDate('created_at', today())
            ->latest()
            ->get()
            ->unique('vital_reference_id');
        $icds = ICD10::all();
        
        return view('checkins.complaint', compact('checkIn', 'currentStep', 'totalSteps',  'icds',  'vitalReferences'));
    }

    public function storeCharting(Request $request, CheckIn $checkIn)
    {

        // dd($request->all());
        // Update CheckIn rich text fields
        $checkIn->presenting_complaint = $request->presenting_complaint;
        $checkIn->treatment_plan = $request->treatment_plan;
        $checkIn->save();

        $patient = $checkIn->patient;
        $userId = auth()->id();

        // Save Vitals
        if ($request->has('vitalsData')) {
            foreach ($request->vitalsData as $refId => $value) {
                if (filled($value)) {
                    VitalReading::create([
                        'patient_id' => $patient->id,
                        'check_in_id' => $checkIn->id,
                        'vital_reference_id' => $refId,
                        'value' => $value,
                        'user_id' => $userId,
                    ]);
                }
            }
        }

        // Save Diagnoses
        if ($request->has('diagnoses')) {
            foreach ($request->diagnoses as $diag) {
                // dd($diag['notes']);
                if (isset($diag['icd_10_id']) && filled($diag['icd_10_id'])) {
                    Diagnosis::create([
                        'patient_id' => $patient->id,
                        'check_in_id' => $checkIn->id,
                        'icd_10_id' => $diag['icd_10_id'],
                        'notes' => $diag['notes'] ?? '',
                        'presenting_complain' => $request->presenting_complaint,
                        'status' => $diag['status'] ?? 'provisional',
                        'treatment_plan' => $request->treatment_plan,
                        'user_id' => $userId,
                    ]);
                }
            }
        }

        // Save Investigations
        if ($request->has('investigations')) {
            $serviceHandler = new ServiceRequestHandler();
             $billRef = str()->random(6);
            foreach ($request->investigations as $inv) {
                // dd($inv['type']);
                if (isset($inv['id']) && filled($inv['id'])) {
                    if($inv['type'] == 'lab') {
                        LabRequest::create([
                            'patient_id' => $patient->id,
                            'test_id' => $inv['id'],
                            'user_id' => $userId,
                            'request_note' => $inv['notes'] ?? '',
                            'request_ref' => $billRef,
                            'priority' => $inv['priority'] ?? 'Regular',
                            'status' => $inv['status'] ?? 'Pending',
                        ]);
                        $test = LabTest::find($inv['id']);
                        $serviceHandler->handleServiceRequest(
                            $test->name, 
                            $patient->id, 
                            'laboratory',
                            $billRef,
                            1
                        );
                    }
                    if($inv['type'] == 'radiology') {
                        RadiologyRequest::create([
                            'patient_id' => $patient->id,
                            'radiology_test_id' => $inv['id'],
                            'user_id' => $userId,
                            'request_note' => $inv['notes'] ?? '',
                            'request_ref' => $billRef,
                            'priority' => $inv['priority'] ?? 'Regular',
                            'status' => $inv['status'] ?? 'Pending',
                        ]);
                        $test = RadiologyTest::find($inv['id']);
                        $serviceHandler->handleServiceRequest(
                            $test->name, 
                            $patient->id, 
                            'radiology',
                            $billRef,
                            1
                        );
                    }
                }
            }
        }

        // Save Prescriptions as Drug Requests
        if ($request->has('prescriptions')) {
            foreach ($request->prescriptions as $pres) {
                if ((isset($pres['drug_id']) && filled($pres['drug_id'])) || (isset($pres['generic_name']) && filled($pres['generic_name']))) {
                    \App\Models\DrugRequest::create([
                        'patient_id' => $patient->id,
                        'check_in_id' => $checkIn->id,
                        'drug_id' => filled($pres['drug_id']) ? $pres['drug_id'] : null,
                        'generic_id' => $pres['generic_name'] ?? null,
                        'store_id' => $pres['store_id'] ?? null,
                        'quantity' => 1, // Default to 1 as quantity isn't in the form explicitly, or derive from dosage? Form doesn't have quantity.
                        'dosage' => $pres['dosage'] ?? '',
                        'frequency' => $pres['frequency'] ?? '',
                        'duration' => $pres['duration'] ?? '',
                        'duration_unit' => $pres['duration_unit'] ?? '',
                        'notes' => $pres['notes'] ?? '',
                        'instruction' => $pres['notes'] ?? '', // Mirror notes to instruction for now
                        'status' => 'pending',
                        'user_id' => $userId,
                        'bill_ref' => $billRef ?? str()->random(6), // Reuse billRef if available or create new
                    ]);
                }
            }
        }

        // Save Procedures
        if ($request->has('procedures')) {
            foreach ($request->procedures as $proc) {
                if (isset($proc['procedure_id']) && filled($proc['procedure_id'])) {
                    ProcedureOrder::create([
                        'check_in_id' => $checkIn->id,
                        'patient_id' => $patient->id,
                        'procedure_id' => $proc['procedure_id'],
                        'icd_10_id' => $proc['icd_10_id'] ?? null,
                        'notes' => $proc['notes'] ?? '',
                        'user_id' => $userId,
                    ]);
                }
            }
        }

        $checkIn->update([
            'status' => 'completed',
        ]);
        $checkIn->appointment()->update([
            'status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Clinical charting completed successfully.');
    }
    public function getDrugsByStore($store)
    {
        // For now, we return all drugs as the relationship isn't defined yet.
        // In the future, this query should be filtered by the store's inventory.
        
        // Fetch generics from DrugGeneric table
        $genericDrugs = \App\Models\DrugGeneric::select('id', 'name')->get();

        $brands = \App\Models\Drug::select('id', 'name')->get();

        return response()->json([
            'generics' => $genericDrugs,
            'brands' => $brands
        ]);
    }
}
