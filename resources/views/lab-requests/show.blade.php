@extends('layouts.app')

@section('title', 'Laboratory Request Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lab Request Details</h5>
                    <span class="badge bg-label-{{ $labRequest->status == 'Completed' ? 'success' : ($labRequest->status == 'Processing' ? 'info' : 'secondary') }}">
                        {{ $labRequest->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Patient</label>
                            <p class="fw-bold fs-5 mb-0">{{ $labRequest->patient->user->FullName() }}</p>
                            <small class="text-muted">Hosp. No: {{ $labRequest->patient->hospital_no }}</small>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <label class="text-muted small text-uppercase">Request Date</label>
                            <p class="fw-medium mb-0">{{ $labRequest->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <hr class="my-2">
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Laboratory Test</label>
                            <p class="fw-bold mb-0 text-primary fs-5">{{ $labRequest->test->name }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <label class="text-muted small text-uppercase">Priority</label>
                            <div>
                                <span class="badge bg-{{ $labRequest->priority == 'Emergency' ? 'danger' : ($labRequest->priority == 'Urgent' ? 'warning' : 'primary') }}">
                                    {{ $labRequest->priority }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="text-muted small text-uppercase">Physician Note</label>
                            <div class="bg-light p-3 rounded mt-1">
                                {{ $labRequest->request_note ?: 'No specific instructions provided.' }}
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4 pt-2">
                            <a href="{{ route('app.lab-requests.index') }}" class="btn btn-label-secondary me-2">Back to List</a>
                            <a href="{{ route('app.lab-requests.edit', $labRequest->id) }}" class="btn btn-primary">Update Status</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
