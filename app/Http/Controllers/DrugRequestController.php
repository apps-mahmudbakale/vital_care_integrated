<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DrugRequestController extends Controller
{
    public function create($patient)
    {
        $patient = \App\Models\Patient::findOrFail($patient);
        return view('drug-requests.create', compact('patient'));
    }
}
