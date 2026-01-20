<div class="modal-header border-bottom py-3">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
            <span class="avatar-initial rounded bg-label-primary">
                <i class="ti tabler-report-medical fs-2"></i>
            </span>
        </div>
        <div>
            <h5 class="modal-title mb-0">Record Lab Findings</h5>
            <span class="text-muted fw-light">{{ $labRequest->test->name }}</span>
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
                    <h5 class="mb-1 text-primary fw-bold">{{ $labRequest->patient->user->FullName() }}</h5>
                    <div class="d-flex gap-4 small text-muted">
                        <span><i class="ti tabler-id me-1"></i><strong>Hosp. No:</strong> {{ $labRequest->patient->hospital_no }}</span>
                        <span><i class="ti tabler-calendar-event me-1"></i><strong>Date:</strong> {{ $labRequest->created_at->format('d M, Y') }}</span>
                    </div>
                </div>
                <div class="col-sm-4 text-sm-end mt-2 mt-sm-0">
                    <span class="badge bg-{{ $labRequest->priority == 'Emergency' ? 'danger' : ($labRequest->priority == 'Urgent' ? 'warning' : 'primary') }} px-3">
                        {{ $labRequest->priority }}
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
                        {{ $labRequest->request_note ?: 'No specific instructions provided.' }}
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small text-uppercase fw-bold mb-1 d-block">
                        <i class="ti tabler-stethoscope me-1"></i> Latest Treatment Plan
                    </label>
                    <p class="mb-0 text-dark small" style="line-height: 1.5;">
                        @php
                            $latestDiagnosis = \App\Models\Diagnosis::where('patient_id', $labRequest->patient_id)
                                ->latest()
                                ->first();
                        @endphp
                        {{ $latestDiagnosis?->treatment_plan ?: 'No active treatment plan found.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('app.lab-requests.add_findings') }}" method="POST" id="lab-findings-form" class="mt-2">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $labRequest->patient_id }}">
        <input type="hidden" name="lab_id" value="{{ $labRequest->id }}">
        <input type="hidden" name="lab_test_id" value="{{ $labRequest->test_id }}">
        <input type="hidden" name="lab_template_id" value="{{ $labRequest->test->template_id }}">
        
        <h6 class="mb-3 px-1 fw-bold"><i class="ti tabler-list-details me-1"></i> Test Parameters Result Entry</h6>
        <div class="table-responsive rounded border">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 py-3">Parameter</th>
                        <th class="py-3">Measured Value</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($labRequest->test->template->items as $item)
                        <tr>
                            <td class="align-middle ps-3 py-3">
                                <span class="fw-bold d-block text-heading">{{ $item->parameter->name }}</span>
                                <small class="text-muted">Ref: {{ $item->reference }} {{ $item->unit }}</small>
                            </td>
                            <td class="py-3 pe-3">
                                <input type="text" name="items[{{ $item->id }}]" class="form-control"
                                    placeholder="Enter result..." required>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-danger py-5">
                                <i class="ti tabler-alert-triangle d-block fs-1 mb-2"></i>
                                No parameters configured for this lab test template.<br>
                                <span class="small">Please contact admin to configure "{{ $labRequest->test->template->name ?? 'Template' }}" items.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        <div class="mt-4 px-1">
            <label class="form-label fw-bold text-heading">
                <i class="ti tabler-message-2 me-1"></i> Pathologist's Final Interpretation / Comments
            </label>
            <textarea name="pathologist_comments" class="form-control" rows="3" 
                      placeholder="Enter general observations or professional interpretation here...">{{ $labRequest->findings }}</textarea>
        </div>
    </form>
</div>

<div class="modal-footer border-top p-4">
    <button type="button" class="btn btn-label-secondary px-4 me-2" data-bs-dismiss="modal">Discard Changes</button>
    <button type="submit" form="lab-findings-form" class="btn btn-primary px-4">
        <i class="ti tabler-device-floppy me-2"></i> Confirm & Save Findings
    </button>
</div>