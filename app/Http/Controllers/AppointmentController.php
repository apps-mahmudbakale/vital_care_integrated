<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('appointments.index');
    }

    public function create()
    {
        return view('appointments.create', [
            'appointmentTypes' => \App\Models\AppointmentType::all(),
            'clinics' => \App\Models\Clinic::all(),
            'specialties' => \App\Models\Specialty::all(),
            'patients' => \App\Models\Patient::with('user')->get(), // Simple list for now
        ]);
    }

    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_type_id' => 'required|exists:appointment_types,id',
            'clinic_id' => 'required|exists:clinics,id',
            'specialty_id' => 'required|exists:specialties,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'is_all_day' => 'boolean',
            'is_follow_up' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'Scheduled';

        $appointment = \App\Models\Appointment::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Appointment scheduled successfully.',
                'check_in_prompt' => \Carbon\Carbon::parse($appointment->start_at)->isToday(),
                'appointment_id' => $appointment->id,
                'patient_name' => $appointment->patient->user->firstname
            ]);
        }

        return redirect()->route('app.appointments.index')->with('success', 'Appointment scheduled successfully.');
    }
}
