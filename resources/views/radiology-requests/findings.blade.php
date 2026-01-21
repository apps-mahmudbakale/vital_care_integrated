<div class="modal-header border-bottom py-3">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
            <span class="avatar-initial rounded bg-label-primary">
                <i class="ti tabler-report-medical fs-2"></i>
            </span>
        </div>
        <div>
            <h5 class="modal-title mb-0">Record Radiology Findings</h5>
            <span class="text-muted fw-light">{{ $radiologyRequest->test->name }}</span>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body py-4">
    <!-- Patient & Clinical Context Card -->
    <div class="card bg-label-secondary border-0 mb-5">
        <div class="card-body p-4">
            <!-- Patient Basic Info -->
            <div class="row align-items-center mb-3 pb-3 border-bottom border-white border-opacity-50">
                <div class="col-sm-8 text-nowrap">
                    <h5 class="mb-1 text-primary fw-bold">{{ $radiologyRequest->patient->user->FullName() }}</h5>
                    <div class="d-flex gap-4 small text-muted">
                        <span><i class="ti tabler-id me-1"></i><strong>Hosp. No:</strong> {{ $radiologyRequest->patient->hospital_no }}</span>
                        <span><i class="ti tabler-calendar-event me-1"></i><strong>Date:</strong> {{ $radiologyRequest->created_at->format('d M, Y') }}</span>
                    </div>
                </div>
                <div class="col-sm-4 text-sm-end mt-2 mt-sm-0">
                    <span class="badge bg-{{ $radiologyRequest->priority == 'Emergency' ? 'danger' : ($radiologyRequest->priority == 'Urgent' ? 'warning' : 'primary') }} px-3">
                        {{ $radiologyRequest->priority }}
                    </span>
                </div>
            </div>

            <!-- Clinical Notes Section -->
            <div class="row g-4">
                <div class="col-md-6 border-end border-white border-opacity-50">
                    <label class="text-muted small text-uppercase fw-bold mb-1 d-block">
                        <i class="ti tabler-notes me-1"></i> Physician's Request Note
                    </label>
                    <p class="mb-0 text-dark small" style="line-height: 1.5;">
                        {{ $radiologyRequest->request_note ?: 'No specific instructions provided.' }}
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small text-uppercase fw-bold mb-1 d-block">
                        <i class="ti tabler-stethoscope me-1"></i> Latest Treatment Plan
                    </label>
                    <p class="mb-0 text-dark small" style="line-height: 1.5;">
                        @php
                            $latestDiagnosis = \App\Models\Diagnosis::where('patient_id', $radiologyRequest->patient_id)
                                ->latest()
                                ->first();
                        @endphp
                        {{ $latestDiagnosis?->treatment_plan ?: 'No active treatment plan found.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('app.radiology-requests.add_findings') }}" method="POST" id="radiology-findings-form" class="mt-2" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $radiologyRequest->patient_id }}">
        <input type="hidden" name="radiology_request_id" value="{{ $radiologyRequest->id }}">
        <input type="hidden" name="radiology_test_id" value="{{ $radiologyRequest->radiology_test_id }}">
        
        <div class="mt-4 px-1">
            <label class="form-label fw-bold text-heading">
                <i class="ti tabler-upload me-1"></i> Upload Images / DICOM
            </label>
            <input type="file" name="file" class="form-control" accept=".zip,.dcm,.jpg,.jpeg,.png,.pdf">
            <div class="form-text">Upload single images or a ZIP archive containing DICOM folders (Max 50MB).</div>
        </div>

        <div class="mt-4 px-1">
            <label class="form-label fw-bold text-heading">
                <i class="ti tabler-list-details me-1"></i> Radiologist's Report / Findings
            </label>
            <input id="x_findings" value="{!! $radiologyRequest->result->findings ?? $radiologyRequest->findings !!}" type="hidden" name="findings">
            <trix-editor input="x_findings" class="form-control" style="min-height: 250px;"></trix-editor>
        </div>
    </form>
</div>

<style>
  .trix-button-group--file-tools { display: none !important; }
</style>

<div class="modal-footer border-top p-4">
    <button type="button" class="btn btn-label-secondary px-4 me-2" data-bs-dismiss="modal">Discard Changes</button>
    <button type="submit" form="radiology-findings-form" class="btn btn-primary px-4">
        <i class="ti tabler-device-floppy me-2"></i> Confirm & Save Findings
    </button>
</div>
