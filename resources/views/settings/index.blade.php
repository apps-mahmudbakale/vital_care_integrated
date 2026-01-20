@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">System /</span> Settings</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('app.settings.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- General Settings -->
                            <div class="row mb-4">
                                <h5 class="mb-3">General Settings</h5>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="system_name">System Name</label>
                                    <input type="text" class="form-control" id="system_name" name="system_name" value="{{ $settings->system_name }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="system_logo">System Logo</label>
                                    <input type="file" class="form-control" id="system_logo" name="system_logo">
                                    @if($settings->system_logo)
                                        <div class="mt-2">
                                            <img src="{{ asset($settings->system_logo) }}" alt="System Logo" height="50">
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="system_address">Address</label>
                                    <textarea class="form-control" id="system_address" name="system_address" rows="2">{{ $settings->system_address }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="system_email">System Email</label>
                                    <input type="email" class="form-control" id="system_email" name="system_email" value="{{ $settings->system_email }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="system_phone">Hotline / Phone</label>
                                    <input type="text" class="form-control" id="system_phone" name="system_phone" value="{{ $settings->system_phone }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="hospital_number_prefix">Hospital Number Prefix</label>
                                    <input type="text" class="form-control" id="hospital_number_prefix" name="hospital_number_prefix" value="{{ $settings->hospital_number_prefix }}">
                                </div>
                                
                                <!-- Clinic Type -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="clinic_type">Clinic Type</label>
                                    <select class="form-select" id="clinic_type" name="clinic_type">
                                        <option value="diagnostic_center" {{ $settings->clinic_type == 'diagnostic_center' ? 'selected' : '' }}>Diagnostic Center</option>
                                        <option value="dialysis_center" {{ $settings->clinic_type == 'dialysis_center' ? 'selected' : '' }}>Dialysis Center</option>
                                        <option value="eye_clinic" {{ $settings->clinic_type == 'eye_clinic' ? 'selected' : '' }}>Eye Clinic</option>
                                        <option value="full_hospital" {{ $settings->clinic_type == 'full_hospital' ? 'selected' : '' }}>Full Hospital</option>
                                        <option value="general" {{ $settings->clinic_type == 'general' ? 'selected' : '' }}>General Practice</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                     <label class="form-label d-block">&nbsp;</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="auto_checkin" name="auto_checkin" value="1" {{ $settings->auto_checkin ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auto_checkin">Auto Check-in</label>
                                    </div>
                                     <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="insurance_billers" name="insurance_billers" value="1" {{ $settings->insurance_billers ? 'checked' : '' }}>
                                        <label class="form-check-label" for="insurance_billers">Insurance Billers</label>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">

                            <!-- Feature Toggles -->
                            <h5 class="mb-3">Feature Toggles</h5>

                            <div class="row">
                                <!-- Clinical Services -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold text-primary mb-2">Clinical Services</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_laboratory" name="show_laboratory" value="1" {{ $settings->show_laboratory ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_laboratory">Laboratory</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_pharmacy" name="show_pharmacy" value="1" {{ $settings->show_pharmacy ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_pharmacy">Pharmacy</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_radiology" name="show_radiology" value="1" {{ $settings->show_radiology ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_radiology">Radiology</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_opticals" name="show_opticals" value="1" {{ $settings->show_opticals ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_opticals">Opticals</label>
                                    </div>
                                </div>

                                <!-- Patient Management -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold text-primary mb-2">Patient Management</label>
                                    @if($settings->show_patients)
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="show_patients" name="show_patients" value="1" {{ $settings->show_patients ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_patients">Patients</label>
                                        </div>
                                    @else
                                         <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="show_patients" name="show_patients" value="1" {{ $settings->show_patients ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_patients">Patients</label>
                                        </div>
                                    @endif

                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_admission" name="show_admission" value="1" {{ $settings->show_admission ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_admission">Admission</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_appointments" name="show_appointments" value="1" {{ $settings->show_appointments ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_appointments">Appointments</label>
                                    </div>
                                </div>

                                <!-- Operations & Admin -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold text-primary mb-2">Operations & Admin</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_billing" name="show_billing" value="1" {{ $settings->show_billing ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_billing">Billing</label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_reports" name="show_reports" value="1" {{ $settings->show_reports ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_reports">Reports</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-6 mb-6">
            <div class="col-md-6">
                <livewire:settings.department-manager />
            </div>
            <div class="col-md-6">
                <livewire:settings.vital-reference-manager />
            </div>
        </div>

        <div class="row g-6">
            <div class="col-md-6">
                <livewire:settings.cash-point-manager />
            </div>
            <div class="col-md-6">
                <livewire:settings.payment-method-manager />
            </div>
        </div>

        <div class="row g-6 mt-4">
            <div class="col-md-12">
                <livewire:settings.icd10-manager />
            </div>
        </div>
        <h2>Tariffs</h2>
        <div class="row">
             <div class="col-md-6 col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 pb-1">Admissions</h4>
                    <p class="small">Manage Wards, Beds, Routes for Fluid Chart</p>
                    <a href="{{ route('app.settings.admissions') }}" class="btn btn-primary w-100 waves-effect waves-light">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 pb-1">Consultation</h4>
                    <p class="small">Configure Consultation Locations, Manage Specialties, Consultation Documentation
                        Templates</p>
                    <a href="{{ route('app.settings.consultation') }}" class="btn btn-primary w-100 waves-effect waves-light">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 pb-1">Pharmacy</h4>
                    <p class="small">Manage Pharmacy Items, Pricing, Stock</p>
                    <a href="{{ route('app.settings.pharmacy') }}" class="btn btn-primary w-100 waves-effect waves-light">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 pb-1">Radiology</h4>
                    <p class="small">Manage Radiology Items, Pricing, Stock</p>
                    <a href="{{ route('app.settings.radiology') }}" class="btn btn-primary w-100 waves-effect waves-light">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 pb-1">Laboratory</h4>
                    <p class="small">Manage Laboratory Items, Pricing, Stock</p>
                    <a href="{{ route('app.settings.lab') }}" class="btn btn-primary w-100 waves-effect waves-light">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 pb-1">Procedures</h4>
                    <p class="small">Manage Procedures, Pricing, Template</p>
                    <a href="{{ route('app.settings.procedure') }}" class="btn btn-primary w-100 waves-effect waves-light">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 pb-1">Antenatal</h4>
                    <p class="small">Manage ANC Packages, Pricing</p>
                    <a href="{{ route('app.settings.antenatal') }}" class="btn btn-primary w-100 waves-effect waves-light">Open</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="mb-2 pb-1">Dialysis</h4>
                    <p class="small">Manage Dialysis Packages, Pricing, Services</p>
                    <a href="" class="btn btn-primary w-100 waves-effect waves-light">Open</a>
                </div>
            </div>
        </div>
    </div>
@endsection
