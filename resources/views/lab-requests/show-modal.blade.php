<div class="modal-header border-bottom">
    <h5 class="modal-title">Lab Request Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row g-3">
        <div class="col-sm-6">
            <label class="text-muted small text-uppercase">Patient</label>
            <p class="fw-bold fs-5 mb-0 text-primary">{{ $labRequest->patient->user->FullName() }}</p>
            <small class="text-muted">Hosp. No: {{ $labRequest->patient->hospital_no }}</small>
        </div>
        <div class="col-sm-6 text-sm-end">
            <label class="text-muted small text-uppercase">Status</label>
            <div>
                <span class="badge bg-label-{{ $labRequest->status == 'Completed' ? 'success' : ($labRequest->status == 'Processing' ? 'info' : 'secondary') }}">
                    {{ $labRequest->status }}
                </span>
            </div>
            <small class="text-muted">{{ $labRequest->created_at->format('d M Y, H:i') }}</small>
        </div>
        
        <hr class="my-2">
        
        <div class="col-sm-6">
            <label class="text-muted small text-uppercase">Laboratory Test</label>
            <p class="fw-bold mb-0 text-dark fs-6">{{ $labRequest->test->name }}</p>
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
            <div class="card bg-label-secondary border-0">
                <div class="card-body p-3">
                    <label class="text-muted small text-uppercase d-block mb-1">Physician Note</label>
                    <p class="mb-0">{{ $labRequest->request_note ?: 'No specific instructions provided.' }}</p>
                </div>
            </div>
        </div>

        @if($labRequest->findings)
        <div class="col-12 mt-3">
             <div class="card bg-label-success border-0">
                <div class="card-body p-3">
                    <label class="text-muted small text-uppercase d-block mb-1">Lab Findings</label>
                    <div class="text-dark">
                        {!! nl2br(e($labRequest->findings)) !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="modal-footer border-top">
    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
    <a href="{{ route('app.lab-requests.edit', $labRequest->id) }}" class="btn btn-primary">Update Request</a>
</div>
