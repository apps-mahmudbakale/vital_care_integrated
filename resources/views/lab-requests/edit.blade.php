@extends('layouts.app')

@section('title', 'Edit Laboratory Request')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Lab Request: {{ $labRequest->test->name }}</h5>
                    <small class="text-muted float-end">Patient: {{ $labRequest->patient->user->FullName() }}</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('app.lab-requests.update', $labRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select" required>
                                <option value="Regular" {{ $labRequest->priority == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Urgent" {{ $labRequest->priority == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                <option value="Emergency" {{ $labRequest->priority == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Pending" {{ $labRequest->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processing" {{ $labRequest->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Completed" {{ $labRequest->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ $labRequest->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Request Note</label>
                            <textarea name="request_note" class="form-control" rows="3">{{ $labRequest->request_note }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('app.lab-requests.index') }}" class="btn btn-label-secondary">Back to List</a>
                            <button type="submit" class="btn btn-primary">Update Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
