@extends('layouts.app')

@section('title', 'Patient Profile - ' . $patient->user->FullName())

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="user-profile-header-banner" style="background-color: #0D9394; height: 150px;">
                </div>
                <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="user image" class="d-block h-auto ms-0 ms-sm-6 rounded-circle user-profile-img" style="width: 120px; border: 5px solid #fff;" />
                    </div>
                    <div class="flex-grow-1 mt-3 mt-lg-5">
                        <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4 class="mb-2 mt-lg-6">{{ $patient->user->FullName() }}</h4>
                                <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class="icon-base ti tabler-gender-female icon-lg"></i><span class="fw-medium">{{ $patient->gender }}</span>
                                    </li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class="icon-base ti tabler-id-badge icon-lg"></i><span class="fw-medium">{{ app(App\Settings\SystemSettings::class)->hospital_number_prefix ?: 'EMR' }}{{ $patient->hospital_no }}</span>
                                    </li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class="icon-base ti tabler-map-pin icon-lg"></i><span class="fw-medium">{{ $patient->state_of_residence }}</span>
                                    </li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class="icon-base ti tabler-calendar icon-lg"></i><span class="fw-medium"> Joined {{ $patient->created_at->format('M Y') }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mb-1">
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" type="button" id="patientActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-base ti tabler-dots-vertical icon-md"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="patientActions">
                                        <a class="dropdown-item" href="{{ route('app.patients.edit', $patient->id) }}">
                                            <i class="icon-base ti tabler-edit icon-xs me-2"></i>Edit Patient
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="icon-base ti tabler-calendar-plus icon-xs me-2"></i>Schedule Appointment
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="icon-base ti tabler-bed icon-xs me-2"></i>Add Admission
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-primary" href="javascript:void(0)">
                                            <i class="icon-base ti tabler-user-check icon-xs me-2"></i>Checked In
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- Financial Summary Cards -->
    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base ti tabler-wallet icon-lg"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">₦{{ number_format($wallet_balance, 2) }}</h4>
                    </div>
                    <p class="mb-0">Wallet Balance</p>
                    <a href="javascript:void(0)" class="btn btn-xs btn-label-primary mt-3">
                        <i class="icon-base ti tabler-plus icon-xs me-1"></i> Fund
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="icon-base ti tabler-receipt-off icon-lg"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">₦ {{ number_format($outstanding_balance, 2) }}</h4>
                    </div>
                    <p class="mb-0">Outstanding Balance</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base ti tabler-credit-card icon-lg"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">₦ 0.00</h4>
                    </div>
                    <p class="mb-0">Credit Limit</p>
                    <a href="javascript:void(0)" class="btn btn-xs btn-label-warning mt-3">
                        <i class="icon-base ti tabler-adjustments icon-xs me-1"></i> Set Limit
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="icon-base ti tabler-package icon-lg"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">None</h4>
                    </div>
                    <p class="mb-0">Package Subscription</p>
                    <a href="javascript:void(0)" class="btn btn-xs btn-label-success mt-3">
                        <i class="icon-base ti tabler-settings icon-xs me-1"></i> Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--/ Financial Summary Cards -->

    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top nav-tabs-shadow mb-6">
                <ul class="nav nav-tabs flex-nowrap" id="profileTabs" role="tablist" style="overflow-x: auto; overflow-y: hidden;">
                    <li class="nav-item">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
                            <i class="icon-base ti tabler-user-check icon-sm me-1_5"></i> Overview
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">
                            <i class="icon-base ti tabler-history icon-sm me-1_5"></i> Visit History
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals" type="button" role="tab" aria-controls="vitals" aria-selected="false">
                            <i class="icon-base ti tabler-activity icon-sm me-1_5"></i> Vital Signs
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="allergies-tab" data-bs-toggle="tab" data-bs-target="#allergies" type="button" role="tab" aria-controls="allergies" aria-selected="false">
                            <i class="icon-base ti tabler-virus icon-sm me-1_5"></i> Allergies
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="labs-tab" data-bs-toggle="tab" data-bs-target="#labs" type="button" role="tab" aria-controls="labs" aria-selected="false">
                            <i class="icon-base ti tabler-flask icon-sm me-1_5"></i> Labs
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="investigations-tab" data-bs-toggle="tab" data-bs-target="#investigations" type="button" role="tab" aria-controls="investigations" aria-selected="false">
                            <i class="icon-base ti tabler-microscope icon-sm me-1_5"></i> Investigations
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="diagnoses-tab" data-bs-toggle="tab" data-bs-target="#diagnoses" type="button" role="tab" aria-controls="diagnoses" aria-selected="false">
                            <i class="icon-base ti tabler-stethoscope icon-sm me-1_5"></i> Diagnoses
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="pharmacy-tab" data-bs-toggle="tab" data-bs-target="#pharmacy" type="button" role="tab" aria-controls="pharmacy" aria-selected="false">
                            <i class="icon-base ti tabler-pill icon-sm me-1_5"></i> Pharmacy
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
                            <i class="icon-base ti tabler-file-text icon-sm me-1_5"></i> Documents
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="bills-tab" data-bs-toggle="tab" data-bs-target="#bills" type="button" role="tab" aria-controls="bills" aria-selected="false">
                            <i class="icon-base ti tabler-receipt icon-sm me-1_5"></i> Bills
                        </button>
                    </li>
                    @if(app(App\Settings\SystemSettings::class)->show_opticals)
                    <li class="nav-item">
                        <button class="nav-link" id="va-tab" data-bs-toggle="tab" data-bs-target="#va" type="button" role="tab" aria-controls="va" aria-selected="false">
                            <i class="icon-base ti tabler-eye icon-sm me-1_5"></i> VA
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="refractions-tab" data-bs-toggle="tab" data-bs-target="#refractions" type="button" role="tab" aria-controls="refractions" aria-selected="false">
                            <i class="icon-base ti tabler-glasses icon-sm me-1_5"></i> Refractions
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="iop-tab" data-bs-toggle="tab" data-bs-target="#iop" type="button" role="tab" aria-controls="iop" aria-selected="false">
                            <i class="icon-base ti tabler-gauge icon-sm me-1_5"></i> IOP
                        </button>
                    </li>
                    @endif
                </ul>
                <!-- Tab Content -->
                <div class="tab-content">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <!-- About User -->
                    <div class="card mb-6">
                        <div class="card-body">
                            <p class="card-text text-uppercase text-body-secondary small mb-0">About</p>
                            <ul class="list-unstyled my-3 py-1">
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-user icon-lg"></i><span class="fw-medium mx-2">Full Name:</span> <span>{{ $patient->user->FullName() }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-gender-male icon-lg"></i><span class="fw-medium mx-2">Gender:</span> <span>{{ $patient->gender }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-calendar icon-lg"></i><span class="fw-medium mx-2">Age:</span> <span>{{ $patient->getAge() }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-heart icon-lg"></i><span class="fw-medium mx-2">Marital Status:</span> <span>{{ $patient->marital_status }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-crown icon-lg"></i><span class="fw-medium mx-2">Religion:</span> <span>{{ $patient->religion?->name ?? 'N/A' }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="icon-base ti tabler-briefcase icon-lg"></i><span class="fw-medium mx-2">Occupation:</span> <span>{{ $patient->occupation ?? 'N/A' }}</span>
                                </li>
                            </ul>
                            <p class="card-text text-uppercase text-body-secondary small mb-0">Contacts</p>
                            <ul class="list-unstyled my-3 py-1">
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-phone-call icon-lg"></i><span class="fw-medium mx-2">Contact:</span> <span>{{ $patient->phone }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-mail icon-lg"></i><span class="fw-medium mx-2">Email:</span> <span>{{ $patient->user->email }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-home icon-lg"></i><span class="fw-medium mx-2">Address:</span> <span>{{ $patient->residential_address }}</span>
                                </li>
                            </ul>
                            <p class="card-text text-uppercase text-body-secondary small mb-0">Insurance (HMO)</p>
                            <ul class="list-unstyled mb-0 mt-3 pt-1">
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-shield-check icon-lg"></i><span class="fw-medium mx-2">Provider:</span> <span>{{ $patient->hmo?->name ?? 'None' }}</span>
                                </li>
                                @if($patient->hmo_id)
                                <li class="d-flex align-items-center mb-4">
                                    <i class="icon-base ti tabler-id icon-lg"></i><span class="fw-medium mx-2">Principal ID:</span> <span>{{ $patient->principal_id }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="icon-base ti tabler-users icon-lg"></i><span class="fw-medium mx-2">Relation:</span> <span>{{ $patient->dependent }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!--/ About User -->
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <!-- Next of Kin -->
                    <div class="card mb-6">
                        <div class="card-header align-items-center">
                            <h5 class="card-action-title mb-0">
                                <i class="icon-base ti tabler-users icon-lg me-4"></i>Next of Kin Information
                            </h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small text-uppercase">Name</label>
                                    <p class="fw-bold mb-0">{{ $patient->next_of_kin_name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small text-uppercase">Relationship</label>
                                    <p class="fw-bold mb-0">{{ $patient->next_of_kin_relationship }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small text-uppercase">Phone</label>
                                    <p class="fw-bold mb-0">{{ $patient->next_of_kin_phone }}</p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label text-muted small text-uppercase">Address</label>
                                    <p class="fw-bold mb-0">{{ $patient->next_of_kin_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Next of Kin -->

                    <!-- Activity Timeline -->
                    <div class="card card-action mb-6">
                        <div class="card-header align-items-center">
                            <h5 class="card-action-title mb-0">
                                <i class="icon-base ti tabler-chart-bar-popular icon-lg me-4"></i>Activity Timeline
                            </h5>
                        </div>
                        <div class="card-body pt-3">
                            <ul class="timeline mb-0">
                                @forelse($patient->checkIns()->latest()->take(5)->get() as $checkIn)
                                <li class="timeline-item timeline-item-transparent {{ $loop->last ? 'border-transparent' : '' }}">
                                    <span class="timeline-point timeline-point-primary"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-0">Visit / Attendance</h6>
                                            <small class="text-body-secondary">{{ $checkIn->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-2">Patient checked in for consultation/procedure.</p>
                                    </div>
                                </li>
                                @empty
                                <li class="timeline-item timeline-item-transparent border-transparent">
                                    <span class="timeline-point timeline-point-secondary"></span>
                                    <div class="timeline-event">
                                        <p class="mb-0 text-muted">No recent activities found.</p>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <!--/ Activity Timeline -->
                </div>
            </div>
        </div>
        <!-- /Overview Tab -->

        <!-- History Tab -->
        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Visit History</h5>
                </div>
                <div class="card-body">
                    <livewire:visit-history :patient="$patient" />
                </div>
            </div>
        </div>
        <!-- /History Tab -->

        <!-- Vitals Tab -->
        <div class="tab-pane fade" id="vitals" role="tabpanel" aria-labelledby="vitals-tab">
           <livewire:patient-vitals :patient="$patient" />
        </div>
        <!-- /Vitals Tab -->

        <!-- Allergies Tab -->
        <div class="tab-pane fade" id="allergies" role="tabpanel" aria-labelledby="allergies-tab">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Allergies</h5>
                    <button type="button" 
                        class="btn btn-sm btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#global-modal"
                        data-url="{{ route('app.allergies.create', $patient->id) }}">
                        <i class="icon-base ti tabler-plus icon-md"></i> New Allergy
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover border-top">
                            <thead>
                                <tr>
                                    <th>Allergy</th>
                                    <th>Reaction</th>
                                    <th>Severity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient->allergies as $allergy)
                                <tr>
                                    <td>{{ $allergy->allergy_name }}</td>
                                    <td>{{ $allergy->reaction }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $allergy->severity == 'severe' ? 'danger' : ($allergy->severity == 'moderate' ? 'warning' : 'info') }}">
                                            {{ ucfirst($allergy->severity) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="icon-base ti tabler-dots-vertical icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="javascript:;" 
                                                    class="dropdown-item" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#global-modal"
                                                    data-url="{{ route('app.allergies.edit', $allergy->id) }}">
                                                    <i class="icon-base ti tabler-edit icon-xs me-2"></i> Edit
                                                </a>
                                                <form action="{{ route('app.allergies.destroy', $allergy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this allergy?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="icon-base ti tabler-trash icon-xs me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">No allergies recorded.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Allergies Tab -->

        <!-- Labs Tab -->
        <div class="tab-pane fade" id="labs" role="tabpanel" aria-labelledby="labs-tab">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Lab Requests</h5>
                    <button type="button" 
                        class="btn btn-sm btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#global-modal"
                        data-url="{{ route('app.lab-requests.create', $patient->id) }}">
                        <i class="icon-base ti tabler-plus icon-md"></i> New Lab Request
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover border-top">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Test</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Requested By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $labRequests = \App\Models\LabRequest::where('patient_id', $patient->id)->latest()->get();
                                @endphp
                                @forelse($labRequests as $request)
                                <tr>
                                    <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $request->test->name }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $request->priority == 'Emergency' ? 'danger' : ($request->priority == 'Urgent' ? 'warning' : 'primary') }}">
                                            {{ $request->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $request->status == 'Completed' ? 'success' : ($request->status == 'Processing' ? 'info' : 'secondary') }}">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td>{{ $request->user->firstname . ' ' . $request->user->lastname ?? 'System' }}</td>
                                    <td>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="icon-base ti tabler-dots-vertical icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="{{ route('app.lab-requests.show', $request->id) }}" class="dropdown-item">
                                                    <i class="icon-base ti tabler-eye icon-xs me-2"></i> View
                                                </a>
                                                <a href="{{ route('app.lab-requests.edit', $request->id) }}" class="dropdown-item">
                                                    <i class="icon-base ti tabler-edit icon-xs me-2"></i> Edit
                                                </a>
                                                <form action="{{ route('app.lab-requests.destroy', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this request?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="icon-base ti tabler-trash icon-xs me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">No lab requests found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Labs Tab -->

        <!-- Investigations Tab -->
        <div class="tab-pane fade" id="investigations" role="tabpanel" aria-labelledby="investigations-tab">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Radiology Requests</h5>
                    <button type="button" 
                        class="btn btn-sm btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#global-modal"
                        data-url="{{ route('app.radiology-requests.create', $patient->id) }}">
                        <i class="icon-base ti tabler-plus icon-md"></i> New Radiology Request
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover border-top">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Test</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Requested By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $radiologyRequests = \App\Models\RadiologyRequest::where('patient_id', $patient->id)->latest()->get();
                                @endphp
                                @forelse($radiologyRequests as $request)
                                <tr>
                                    <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $request->test->name }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $request->priority == 'Emergency' ? 'danger' : ($request->priority == 'Urgent' ? 'warning' : 'primary') }}">
                                            {{ $request->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $request->status == 'Completed' ? 'success' : ($request->status == 'Processing' ? 'info' : 'secondary') }}">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td>{{ $request->user->firstname . ' ' . $request->user->lastname ?? 'System' }}</td>
                                    <td>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="icon-base ti tabler-dots-vertical icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="{{ route('app.radiology-requests.show', $request->id) }}" class="dropdown-item">
                                                    <i class="icon-base ti tabler-eye icon-xs me-2"></i> View
                                                </a>
                                                <a href="{{ route('app.radiology-requests.edit', $request->id) }}" class="dropdown-item">
                                                    <i class="icon-base ti tabler-edit icon-xs me-2"></i> Edit
                                                </a>
                                                <form action="{{ route('app.radiology-requests.destroy', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this request?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="icon-base ti tabler-trash icon-xs me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">No radiology requests found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Investigations Tab -->

        <!-- Diagnoses Tab -->
        <div class="tab-pane fade" id="diagnoses" role="tabpanel" aria-labelledby="diagnoses-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Diagnoses</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover border-top">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Diagnosis</th>
                                    <th>Type</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient->diagnoses as $diagnosis)
                                <tr>
                                    <td>{{ $diagnosis->created_at->format('d M Y') }}</td>
                                    <td>{{ $diagnosis->diagnosis_name }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $diagnosis->diagnosis_type == 'final' ? 'success' : 'primary' }}">
                                            {{ ucfirst($diagnosis->diagnosis_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $diagnosis->notes }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">No clinical diagnoses recorded.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Diagnoses Tab -->

        <!-- Pharmacy Tab -->
        <div class="tab-pane fade" id="pharmacy" role="tabpanel" aria-labelledby="pharmacy-tab">
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pharmacy Requests (Orders)</h5>
                    <button type="button" 
                        class="btn btn-sm btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#global-modal"
                        data-url="{{ route('app.drug-requests.create', $patient->id) }}">
                        <i class="icon-base ti tabler-plus icon-md"></i> New Drug Request
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover border-top">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Drug</th>
                                    <th>Qty</th>
                                    <th>Dosage</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $drugRequests = \App\Models\DrugRequest::where('patient_id', $patient->id)->latest()->get();
                                @endphp
                                @forelse($drugRequests as $request)
                                <tr>
                                    <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $request->drug->name }}</td>
                                    <td>{{ $request->quantity }}</td>
                                    <td>{{ $request->dosage }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $request->status == 'dispensed' ? 'success' : ($request->status == 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="icon-base ti tabler-dots-vertical icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="{{ route('app.drug-requests.show', $request->id) }}" class="dropdown-item">
                                                    <i class="icon-base ti tabler-eye icon-xs me-2"></i> View
                                                </a>
                                                <form action="{{ route('app.drug-requests.destroy', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="icon-base ti tabler-trash icon-xs me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">No pharmacy requests found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Clinical Prescriptions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover border-top">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Drug</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $prescriptions = \App\Models\Prescription::where('patient_id', $patient->id)->latest()->get();
                                @endphp
                                @forelse($prescriptions as $prescription)
                                <tr>
                                    <td>{{ $prescription->created_at->format('d M Y') }}</td>
                                    <td>{{ $prescription->drug->name }}</td>
                                    <td>{{ $prescription->dosage }}</td>
                                    <td>{{ $prescription->frequency }}</td>
                                    <td>{{ $prescription->duration }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $prescription->status == 'dispensed' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($prescription->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">No clinical prescriptions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Pharmacy Tab -->

        <!-- Documents Tab -->
        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Documents</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Uploaded patient documents, consent forms, and scans.</p>
                </div>
            </div>
        </div>
        <!-- /Documents Tab -->

        <!-- Bills Tab -->
        <div class="tab-pane fade" id="bills" role="tabpanel" aria-labelledby="bills-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bills & Invoices</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Detailed list of bills, payments, and outstanding balances.</p>
                </div>
            </div>
        </div>
        <!-- /Bills Tab -->

        @if(app(App\Settings\SystemSettings::class)->show_opticals)
        <!-- Ophthalmology Tabs -->
        <div class="tab-pane fade" id="va" role="tabpanel" aria-labelledby="va-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Visual Acuity (VA)</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Ophthalmology VA measurements history.</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="refractions" role="tabpanel" aria-labelledby="refractions-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Refractions</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Ophthalmology refraction records.</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="iop" role="tabpanel" aria-labelledby="iop-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Intraocular Pressure (IOP)</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Ophthalmology IOP measurement history.</p>
                </div>
            </div>
        </div>
        <!-- /Ophthalmology Tabs -->
        @endif
                </div>
                <!-- /Tab Content -->
            </div>
        </div>
    </div>
<!-- / Content -->
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#record-vitals').click(function() {
            $('#global-modal').modal('show');
        });
    });
</script>
@endpush
