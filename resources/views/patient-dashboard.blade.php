@extends('layouts.app')

@section('title', 'My Health Dashboard')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h4 class="card-title text-white">Welcome back, {{ auth()->user()->firstname }}!</h4>
                <p class="card-text">Access your medical records, appointments, and test results here.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="ti tabler-calendar fs-1 text-primary mb-3"></i>
                <h5 class="card-title">Appointments</h5>
                <p class="card-text">View your upcoming and past appointments.</p>
                <a href="{{ route('app.appointments.index') }}" class="btn btn-outline-primary">View Appointments</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="ti tabler-flask fs-1 text-info mb-3"></i>
                <h5 class="card-title">Lab Results</h5>
                <p class="card-text">Check the status and results of your lab tests.</p>
                <a href="{{ route('app.lab-requests.index') }}" class="btn btn-outline-info">View Results</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="ti tabler-radioactive fs-1 text-warning mb-3"></i>
                <h5 class="card-title">Radiology</h5>
                <p class="card-text">View your radiology scan requests and reports.</p>
                <a href="{{ route('app.radiology-requests.index') }}" class="btn btn-outline-warning">View Reports</a>
            </div>
        </div>
    </div>
</div>
@endsection
