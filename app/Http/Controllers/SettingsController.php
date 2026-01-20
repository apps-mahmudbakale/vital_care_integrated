<?php

namespace App\Http\Controllers;

use App\Settings\SystemSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(SystemSettings $settings)
    {
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request, SystemSettings $settings)
    {
        // General Settings
        $settings->system_name = $request->input('system_name');
        $settings->system_email = $request->input('system_email');
        $settings->system_phone = $request->input('system_phone');
        $settings->system_address = $request->input('system_address');
        $settings->hospital_number_prefix = $request->input('hospital_number_prefix');
        $settings->auto_checkin = $request->boolean('auto_checkin');
        $settings->insurance_billers = $request->boolean('insurance_billers');
        $settings->clinic_type = $request->input('clinic_type');

        if ($request->hasFile('system_logo')) {
            $path = $request->file('system_logo')->store('settings', 'public');
            $settings->system_logo = 'storage/' . $path;
        }

        // Feature Toggles
        $settings->show_laboratory = $request->boolean('show_laboratory');
        $settings->show_pharmacy = $request->boolean('show_pharmacy');
        $settings->show_radiology = $request->boolean('show_radiology');
        $settings->show_billing = $request->boolean('show_billing');
        $settings->show_reports = $request->boolean('show_reports');
        $settings->show_admission = $request->boolean('show_admission');
        $settings->show_appointments = $request->boolean('show_appointments');
        $settings->show_patients = $request->boolean('show_patients');
        $settings->show_opticals = $request->boolean('show_opticals');

        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
    public function admissions()
    {
        return view('settings.admissions.index');
    }

    public function consultation()
    {
        return view('settings.consultation.index');
    }

    public function pharmacy()
    {
        return view('settings.pharmacy.index');
    }

    public function lab()
    {
        return view('settings.laboratory.index');
    }

    public function radiology()
    {
        return view('settings.radiology.index');
    }

    public function antenatal()
    {
        return view('settings.antenatal.index');
    }

    public function procedure()
    {
        return view('settings.procedure.index');
    }
}
