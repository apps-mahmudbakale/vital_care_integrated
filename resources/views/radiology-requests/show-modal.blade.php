<div class="modal-header border-bottom">
    <h5 class="modal-title">Radiology Request Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row g-3">
        <div class="col-sm-6">
            <label class="text-muted small text-uppercase">Patient</label>
            <p class="fw-bold fs-5 mb-0 text-primary">{{ $radiologyRequest->patient->user->FullName() }}</p>
            <small class="text-muted">Hosp. No: {{ $radiologyRequest->patient->hospital_no }}</small>
        </div>
        <div class="col-sm-6 text-sm-end">
            <label class="text-muted small text-uppercase">Status</label>
            <div>
                <span class="badge bg-label-{{ $radiologyRequest->status == 'Completed' ? 'success' : ($radiologyRequest->status == 'Processing' ? 'info' : 'secondary') }}">
                    {{ $radiologyRequest->status }}
                </span>
            </div>
            <small class="text-muted">{{ $radiologyRequest->created_at->format('d M Y, H:i') }}</small>
        </div>
        
        <hr class="my-2">
        
        <div class="col-sm-6">
            <label class="text-muted small text-uppercase">Radiology Test</label>
            <p class="fw-bold mb-0 text-dark fs-6">{{ $radiologyRequest->test->name }}</p>
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
            <div class="card bg-label-secondary border-0">
                <div class="card-body p-3">
                    <label class="text-muted small text-uppercase d-block mb-1">Physician Note</label>
                    <p class="mb-0">{{ $radiologyRequest->request_note ?: 'No specific instructions provided.' }}</p>
                </div>
            </div>
        </div>

        @if($radiologyRequest->findings)
        <div class="col-12 mt-3">
             <div class="card bg-label-success border-0">
                <div class="card-body p-3">
                    <label class="text-muted small text-uppercase d-block mb-1">Radiology Findings</label>
                    <div class="text-dark">
                        {!! nl2br(e($radiologyRequest->findings)) !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="modal-footer border-top">
    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
    <a href="{{ route('app.radiology-requests.edit', $radiologyRequest->id) }}" class="btn btn-primary">Update Request</a>
</div>
