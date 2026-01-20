<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use App\Models\Patient;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    public function create(Patient $patient)
    {
        return view('allergies.create', compact('patient'));
    }

    public function edit(Allergy $allergy)
    {
        $patient = $allergy->patient;
        return view('allergies.create', compact('patient', 'allergy'));
    }

    public function destroy(Allergy $allergy)
    {
        $patientId = $allergy->patient_id;
        $allergy->delete();

        return redirect()->route('app.patients.show', $patientId)->with('success', 'Allergy removed successfully.');
    }
}
