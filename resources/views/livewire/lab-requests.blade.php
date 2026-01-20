<div>
     <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4" wire:ignore>
                    <label class="form-label">Search Patient</label>
                    <select id="patient-select" class="form-select select2" data-placeholder="Select a patient">
                        <option value=""></option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->user->FullName() }} ({{ $patient->hospital_no }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select wire:model.live="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Processing">Processing</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Priority</label>
                    <select wire:model.live="priority" class="form-select">
                        <option value="">All Priorities</option>
                        <option value="Regular">Regular</option>
                        <option value="Urgent">Urgent</option>
                        <option value="Emergency">Emergency</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" wire:click="$refresh" class="btn btn-primary me-2">
                        <i class="icon-base ti tabler-refresh me-1"></i> Refresh
                    </button>
                    <button type="button" wire:click="resetFilters" class="btn btn-label-secondary">
                        <i class="icon-base ti tabler-x me-1"></i> Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            function initializeLabComponent() {
                if (typeof jQuery === 'undefined' || typeof Livewire === 'undefined') {
                    setTimeout(initializeLabComponent, 50);
                    return;
                }

                initSelect2();

                Livewire.on('filtersReset', () => {
                    $('#patient-select').val(null).trigger('change');
                });

                Livewire.on('showSpecimenRestrictedAlert', () => {
                    Swal.fire({
                        title: 'Specimen Required!',
                        text: 'Specimens have not been received for this test. Please click "Receive Specimens" before recording findings.',
                        icon: 'info',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Understood'
                    });
                });

                Livewire.on('showPaymentRestrictedAlert', () => {
                    Swal.fire({
                        title: 'Payment Required!',
                        text: 'This service has not been paid for. Please ensure payment is confirmed before adding findings.',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Understood'
                    });
                });

                Livewire.on('openFindingsModal', () => {
                     $('#findingsModal').modal('show');
                });

                Livewire.on('closeFindingsModal', () => {
                     $('#findingsModal').modal('hide');
                });

                Livewire.on('findingsSaved', () => {
                    Swal.fire({
                        title: 'Saved!',
                        text: 'Lab findings have been recorded successfully.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });

                $(document).on('click', '.confirm-approve', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('.approve-form');
                    Swal.fire({
                        title: 'Approve Result?',
                        text: "Once approved, the result will be ready for printing and usage. This action cannot be undone!",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Approve it!',
                        customClass: {
                            confirmButton: 'btn btn-success me-1',
                            cancelButton: 'btn btn-label-secondary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });

                $(document).on('click', '.confirm-delete', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('.delete-form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        customClass: {
                            confirmButton: 'btn btn-danger me-1',
                            cancelButton: 'btn btn-label-secondary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            function initSelect2() {
                if (typeof jQuery !== 'undefined' && $('#patient-select').length) {
                    $('#patient-select').select2({
                        placeholder: 'Search Patient...',
                        allowClear: true,
                        width: '100%'
                    }).on('change', function (e) {
                        @this.set('patientId', $(this).val());
                    });
                }
            }

            // Initialize on load
            initializeLabComponent();

            // Re-initialize on Livewire navigation
            document.addEventListener('livewire:navigated', function() {
                if (typeof jQuery !== 'undefined') {
                    initSelect2();
                }
            });
        })();
    </script>
    @endpush

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Laboratory Requests</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Test Name</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Specimen</th>
                        <th>Requested By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($labRequests as $request)
                    <tr>
                        <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('app.patients.show', $request->patient_id) }}" class="fw-medium text-primary">
                                {{ $request->patient->user->firstname }} {{ $request->patient->user->lastname }}
                            </a>
                        </td>
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
                        <td>
                            @if($request->isSpecimenReceived())
                                <span class="badge bg-label-success" title="Received at {{ $request->specimen_received_at->format('d M, H:i') }}">
                                    <i class="icon-base ti tabler-check icon-xs me-1"></i> Received
                                </span>
                            @else
                                <span class="badge bg-label-warning">
                                    <i class="icon-base ti tabler-clock icon-xs me-1"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td>{{ $request->user->firstname }} {{ $request->user->lastname }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-dots-vertical icon-md"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @if(!$request->isSpecimenReceived())
                                    <a class="dropdown-item text-primary" href="javascript:void(0)" wire:click="receiveSpecimen({{ $request->id }})">
                                        <i class="icon-base ti tabler-test-pipe icon-xs me-2"></i> Receive Specimens
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    @endif
                                    
                                    @if($request->isSpecimenReceived() && $request->status != 'Completed')
                                    <a class="dropdown-item" href="javascript:void(0)" 
                                    data-bs-toggle="modal" 
                                        data-bs-target="#global-modal" 
                                        data-url="{{ route('app.lab-requests.findings', $request->id) }}"
                                    >
                                        <i class="icon-base ti tabler-notebook icon-xs me-2"></i> Add Finding Notes
                                    </a>
                                    @endif

                                    @if($request->status == 'Completed' && (!$request->result || !$request->result->is_approved))
                                    <form action="{{ route('app.lab-requests.approve', $request->id) }}" method="POST" class="approve-form">
                                        @csrf
                                        <button type="button" class="dropdown-item text-success confirm-approve">
                                            <i class="icon-base ti tabler-circle-check icon-xs me-2"></i> Approve Result
                                        </button>
                                    </form>
                                    @endif

                                    @if($request->result && $request->result->is_approved)
                                    <a class="dropdown-item text-primary" href="{{ route('app.lab-requests.print', $request->id) }}" target="_blank">
                                        <i class="icon-base ti tabler-printer icon-xs me-2"></i> Print Result
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="javascript:void(0)" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#global-modal" 
                                        data-url="{{ route('app.lab-requests.show', $request->id) }}">
                                        <i class="icon-base ti tabler-eye icon-xs me-2"></i> View
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#global-modal" 
                                        data-url="{{ route('app.lab-requests.edit', $request->id) }}">
                                        <i class="icon-base ti tabler-edit icon-xs me-2"></i> Edit
                                    </a>
                                    <form action="{{ route('app.lab-requests.destroy', $request->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger confirm-delete">
                                            <i class="icon-base ti tabler-trash icon-xs me-2"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">No laboratory requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer px-3 py-2">
            {{ $labRequests->links() }}
        </div>
    </div>

    <!-- Findings Modal -->
    <div wire:ignore.self class="modal fade" id="findingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Lab Findings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveFindings">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Finding Notes</label>
                            <textarea wire:model="findings" class="form-control" rows="6" placeholder="Enter test results and findings here..." required></textarea>
                            @error('findings') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading class="spinner-border spinner-border-sm me-1"></span>
                            Save Findings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
