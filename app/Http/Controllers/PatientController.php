<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;

class PatientController extends Controller
{
    public function index()
    {
        return view('patients.index');
    }

    public function create()
    {
        $settings = app(\App\Settings\SystemSettings::class);
        $religions = \App\Models\Religion::all();
        $hmos = \App\Models\HmoGroup::all();
        $states = [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
            'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT - Abuja', 'Gombe',
            'Imo', 'Jagawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos',
            'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto',
            'Taraba', 'Yobe', 'Zamfara'
        ];
        
        // Data placeholder for pre-filling, e.g. from a temporary registration
        $data = request('temp_id') ? \App\Models\Patient::find(request('temp_id')) : (object)[
            'id' => '',
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'gender' => '',
            'date_of_birth' => '',
            'religion_id' => '',
            'marital_status' => '',
            'occupation' => '',
            'state_of_residence' => '',
            'lga_of_residence' => '',
            'residential_address' => '',
        ];

        return view('patients.create', compact('settings', 'religions', 'hmos', 'states', 'data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
            
            // Patient specific
            'marital_status' => 'required|string',
            'religion_id' => 'required|exists:religions,id',
            'tribe' => 'required|string',
            'disability' => 'nullable|string',
            'occupation' => 'required|string|max:255',
            'residential_address' => 'required|string',
            'state_of_residence' => 'required|string|max:255',
            'lga_of_residence' => 'required|string|max:255',
            'next_of_kin_name' => 'required|string|max:255',
            'next_of_kin_relationship' => 'required|string|max:255',
            'next_of_kin_phone' => 'required|string|max:255',
            'next_of_kin_address' => 'required|string',
            'hmo_id' => 'nullable|exists:hmo_groups,id',
            'dependent' => 'nullable|string',
            'principal_id' => 'nullable|string',
            'hospital_no' => 'nullable|string|unique:patients,hospital_no',
        ]);

        // Generate Hospital No if not provided
        if (empty($validated['hospital_no'])) {
            // Logic to generate hospital no, e.g., incremental ID or timestamp
            // For now, using a simple timestamp based generator or leaving it to be handled
            // Ideally, this should be better. Let's use a random string or count based.
            // Using logic: EMR + timestamp for uniqueness
             $validated['hospital_no'] = 'PAT-' . time();
        }

        // Create User
        $user = \App\Models\User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['phone'] ?? '12345678'), // Default password is phone
        ]);

       

        // Create Patient
        $patient = $user->patient()->create([
            'hospital_no' => $validated['hospital_no'],
            'middlename' => $validated['middlename'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'religion_id' => $validated['religion_id'],
            'tribe' => $validated['tribe'],
            'disability' => $validated['disability'],
            'occupation' => $validated['occupation'],
            'marital_status' => $validated['marital_status'],
            'residential_address' => $validated['residential_address'],
            'state_of_residence' => $validated['state_of_residence'],
            'lga_of_residence' => $validated['lga_of_residence'],
            'next_of_kin_name' => $validated['next_of_kin_name'],
            'next_of_kin_relationship' => $validated['next_of_kin_relationship'],
            'next_of_kin_phone' => $validated['next_of_kin_phone'],
            'next_of_kin_address' => $validated['next_of_kin_address'],
            'hmo_id' => $validated['hmo_id'],
            'dependent' => $validated['dependent'],
            'principal_id' => $validated['principal_id'],
        ]);
        
        // Assign a default role if needed, e.g. 'Patient'
        // $user->assignRole('Patient');

        return redirect()->route('app.patients.index')->with('success', 'Patient registered successfully.');
    }

    public function show(\App\Models\Patient $patient)
    {
        $patient->load(['user', 'religion', 'hmo', 'checkIns' => function($query) {
        $query->latest();
    }, 'wallet', 'transactions' => function($query) {
        $query->latest();
    }, 'vitals' => function($query) {
        $query->latest();
    }, 'allergies', 'diagnoses']);
    $outstanding_balance = Billing::where('user_id', $patient->id)->where('status', 0)->sum('amount');
    $wallet_balance = $patient->wallet ? $patient->wallet->balance : 0;
        return view('patients.show', compact('patient', 'outstanding_balance', 'wallet_balance'));
    }
}
