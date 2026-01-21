<div>
     <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4" wire:ignore>
                    <label class="form-label">Search Patient</label>
                    <select id="radiology-patient-select" class="form-select select2" data-placeholder="Select a patient">
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

    <script>
        document.addEventListener('livewire:navigated', function() {
            initRadiologySelect2();
        });

        // For initial load
        $(document).ready(function() {
            initRadiologySelect2();
        });

        function initRadiologySelect2() {
            $('#radiology-patient-select').select2({
                placeholder: 'Search Patient...',
                allowClear: true,
                width: '100%'
            }).on('change', function (e) {
                @this.set('patientId', $(this).val());
            });
        }

        Livewire.on('filtersReset', () => {
            $('#radiology-patient-select').val(null).trigger('change');
        });
    </script>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Radiology Requests</h5>
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
                        <th>Requested By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($radiologyRequests as $request)
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
                        <td>{{ $request->user->firstname }} {{ $request->user->lastname }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-dots-vertical icon-md"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @if($request->status !== 'Completed')
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        data-bs-toggle="modal"
                                        data-bs-target="#global-modal"
                                        data-url="{{ route('app.radiology-requests.findings', $request->id) }}">
                                        <i class="icon-base ti tabler-notebook icon-xs me-2"></i> Record Result / Findings
                                    </a>
                                    @endif

                                    @if($request->status === 'Completed' && !$request->result?->is_approved)
                                    <form action="{{ route('app.radiology-requests.approve', $request->id) }}" method="POST" class="approve-form">
                                        @csrf
                                        <button type="button" class="dropdown-item text-success confirm-approve">
                                            <i class="icon-base ti tabler-check icon-xs me-2"></i> Approve Result
                                        </button>
                                    </form>
                                    @endif

                                    <a class="dropdown-item" href="javascript:void(0)" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#global-modal" 
                                        data-url="{{ route('app.radiology-requests.show', $request->id) }}">
                                        <i class="icon-base ti tabler-eye icon-xs me-2"></i> View Request
                                    </a>

                                    @if($request->result && $request->result->image)
                                        @php
                                            $imagePath = $request->result->image;
                                            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                                            $url = asset('storage/' . $imagePath);
                                          
                                        @endphp

                                        @if(empty($extension) || in_array(strtolower($extension), ['dcm']))
                                            <a href="weasis://?%24dicom%3Aget+-r+{{ urlencode($url) }}" class="dropdown-item">
                                                <i class="icon-base ti tabler-photo-scan icon-xs me-2"></i> Launch Image Viewer
                                            </a>
                                        @else
                                            <a href="{{ $url }}" target="_blank" class="dropdown-item">
                                                <i class="icon-base ti tabler-eye icon-xs me-2"></i> View Image/Document
                                            </a>
                                        @endif
                                    @endif

                                    @if($request->result?->is_approved)
                                    <a class="dropdown-item" href="{{ route('app.radiology-requests.print', $request->id) }}" target="_blank">
                                        <i class="icon-base ti tabler-printer icon-xs me-2"></i> Print Result
                                    </a>
                                    @endif

                                    @if($request->status !== 'Completed' && !$request->result)
                                    <a class="dropdown-item" href="javascript:void(0)" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#global-modal" 
                                        data-url="{{ route('app.radiology-requests.edit', $request->id) }}">
                                        <i class="icon-base ti tabler-edit icon-xs me-2"></i> Edit
                                    </a>
                                    <form action="{{ route('app.radiology-requests.destroy', $request->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger confirm-delete">
                                            <i class="icon-base ti tabler-trash icon-xs me-2"></i> Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">No radiology requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer px-3 py-2">
            {{ $radiologyRequests->links() }}
        </div>
    </div>

    <!-- Findings Modal -->
    <div wire:ignore.self class="modal fade" id="findingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Radiology Finding Notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveFindings">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Finding Notes</label>
                            <textarea wire:model="findings" class="form-control" rows="10" placeholder="Enter findings here..."></textarea>
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

    @push('scripts')
    <script>
        (function() {
            function initializeComponent() {
                if (typeof jQuery === 'undefined' || typeof Livewire === 'undefined') {
                    setTimeout(initializeComponent, 50);
                    return;
                }

                initRadiologySelect2();

                Livewire.on('filtersReset', () => {
                    $('#radiology-patient-select').val(null).trigger('change');
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
                        text: 'Radiology findings have been recorded successfully.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
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

                $(document).on('click', '.confirm-approve', function(e) {
                    e.preventDefault();
                    let form = $(this).closest('.approve-form');
                    Swal.fire({
                        title: 'Approve Result?',
                        text: "This will finalize the result and allow printing.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Approve',
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
            }

            function initRadiologySelect2() {
                if (typeof jQuery !== 'undefined' && $('#radiology-patient-select').length) {
                    $('#radiology-patient-select').select2({
                        placeholder: 'Search Patient...',
                        allowClear: true,
                        width: '100%'
                    }).on('change', function (e) {
                        @this.set('patientId', $(this).val());
                    });
                }
            }

            // Initialize on load
            initializeComponent();

            // Re-initialize on Livewire navigation
            document.addEventListener('livewire:navigated', function() {
                if (typeof jQuery !== 'undefined') {
                    initRadiologySelect2();
                }
            });
        })();
    </script>
    @endpush
</div>
