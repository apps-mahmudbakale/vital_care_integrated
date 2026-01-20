@extends('layouts.app')

@section('title', 'Radiology Request Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Radiology Request Details</h5>
                    <span class="badge bg-label-{{ $radiologyRequest->status == 'Completed' ? 'success' : ($radiologyRequest->status == 'Processing' ? 'info' : 'secondary') }}">
                        {{ $radiologyRequest->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Patient</label>
                            <p class="fw-bold fs-5 mb-0">{{ $radiologyRequest->patient->user->FullName() }}</p>
                            <small class="text-muted">Hosp. No: {{ $radiologyRequest->patient->hospital_no }}</small>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <label class="text-muted small text-uppercase">Request Date</label>
                            <p class="fw-medium mb-0">{{ $radiologyRequest->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <hr class="my-2">
                        <div class="col-sm-6">
                            <label class="text-muted small text-uppercase">Radiology Test</label>
                            <p class="fw-bold mb-0 text-primary fs-5">{{ $radiologyRequest->test->name }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <label class="text-muted small text-uppercase">Priority</label>
                            <div>
                                <span class="badge bg-{{ $radiologyRequest->priority == 'Emergency' ? 'danger' : ($radiologyRequest->priority == 'Urgent' ? 'warning' : 'primary') }}">
                                    {{ $radiologyRequest->priority }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="text-muted small text-uppercase">Physician Note</label>
                            <div class="bg-light p-3 rounded mt-1">
                                {{ $radiologyRequest->request_note ?: 'No specific instructions provided.' }}
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4 pt-2">
                            <a href="{{ route('app.radiology-requests.index') }}" class="btn btn-label-secondary me-2">Back to List</a>
                            <a href="{{ route('app.radiology-requests.edit', $radiologyRequest->id) }}" class="btn btn-primary">Update Status</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
