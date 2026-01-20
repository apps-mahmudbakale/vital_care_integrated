@push('css')
<style>
    trix-editor {
        min-height: 250px !important;
        background-color: #fff;
    }
    .trix-container {
        border-radius: 0.375rem;
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
    }
</style>
@endpush

<div>
    <form action="{{ route('app.checkins.charting.store', $checkIn) }}" method="POST" id="chartingForm">
        @csrf
        <div wire:ignore>
            <div class="nav-align-top nav-tabs-shadow">
                <!-- TABS -->
                <ul class="nav nav-tabs nav-fill" role="tablist" id="chartingTabs">
                    <li class="nav-item">
                        <button class="nav-link active" type="button" data-bs-toggle="tab" data-bs-target="#tab-vitals">
                            <i class="ti tabler-activity me-1"></i> Vitals
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#tab-complaint">
                            <i class="ti tabler-notes me-1"></i> Complaint
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#tab-diagnosis">
                            <i class="ti tabler-report-medical me-1"></i> Diagnosis
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#tab-plan">
                            <i class="ti tabler-stethoscope me-1"></i> Plan
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#tab-investigation">
                            <i class="ti tabler-microscope me-1"></i> Investigation
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#tab-prescription">
                            <i class="ti tabler-pill me-1"></i> Prescription
                        </button>
                    </li>
                </ul>

                <!-- TAB CONTENT -->
                <div class="tab-content pt-4" style="min-height: 450px; max-height: 65vh; overflow-y: auto;">
                    <!-- TAB 1: Vitals -->
                    <div class="tab-pane fade show active" id="tab-vitals">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h5 class="mb-1">Patient Vitals</h5>
                                <p class="text-muted small">Recorded current vitals for {{ ($patient->user->firstname ?? '') . ' ' . ($patient->user->lastname ?? '') }}</p>
                            </div>
                            <div class="col-12">
                                <div class="row g-4">
                                    @forelse ($vitalReferences as $vital)
                                        <div class="col-lg-4 col-md-6">
                                            @php
                                                $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
                                                $color = $colors[$loop->index % count($colors)];
                                            @endphp
                                            <div class="card icon-card text-center mb-4 border-top border-3 border-{{ $color }}">
                                                <div class="card-body">
                                                    <p class="text-{{ $color }} text-capitalize text-truncate mb-1 fw-bold">{{ $vital->reference->name }}</p>
                                                    <h4 class="mb-1">{{ $vital->value }}</h4>
                                                    <p class="text-muted text-capitalize text-truncate mb-0 small">{{ $vital->reference->unit }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5">
                                            <i class="ti ti-info-circle fs-1 text-muted mb-3"></i>
                                            <p class="text-muted">No vital signs recorded for this patient.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-primary next-tab">Next <i class="ti tabler-chevron-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- TAB 2: Complaint -->
                    <div class="tab-pane fade" id="tab-complaint">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h5 class="mb-0">Presenting Complaint</h5>
                                <p class="text-muted small">Detail the reason for the patient's visit</p>
                            </div>
                            <div class="col-12" wire:ignore>
                                <x-trix-input id="presenting_complaint_field" name="presenting_complaint" wire:model="presenting_complaint" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-tab"><i class="ti tabler-chevron-left me-1"></i> Previous</button>
                            <button type="button" class="btn btn-primary next-tab">Next <i class="ti tabler-chevron-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- TAB 3: Diagnosis -->
                    <div class="tab-pane fade" id="tab-diagnosis">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h5 class="mb-0">Diagnosis (ICD-10)</h5>
                            </div>
                            <div class="col-12 mb-4 position-relative">
                                <input type="text" class="form-control form-control-lg" wire:model.live.debounce.500ms="diagnosis_search" placeholder="Search ICD-10...">
                                @if(!empty($search_results_diagnosis))
                                    <div class="list-group position-absolute w-100 shadow-lg mt-1" style="z-index: 1000; background: white; max-height: 300px; overflow-y: auto;">
                                        @foreach($search_results_diagnosis as $result)
                                            <button type="button" class="list-group-item list-group-item-action" wire:click="selectDiagnosis({{ count($diagnoses) }}, '{{ $result['id'] }}', '{{ addslashes($result['name']) }}', '{{ $result['number'] }}')">
                                                {{ $result['name'] }} ({{ $result['number'] }})
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                           
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-tab"><i class="ti tabler-chevron-left me-1"></i> Previous</button>
                            <button type="button" class="btn btn-primary next-tab">Next <i class="ti tabler-chevron-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- TAB 4: Plan -->
                    <div class="tab-pane fade" id="tab-plan">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h5 class="mb-0">Treatment Plan</h5>
                            </div>
                            <div class="col-12" wire:ignore>
                                <x-trix-input id="treatment_plan_field" name="treatment_plan" wire:model="treatment_plan" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-tab"><i class="ti tabler-chevron-left me-1"></i> Previous</button>
                            <button type="button" class="btn btn-primary next-tab">Next <i class="ti tabler-chevron-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- TAB 5: Investigation -->
                    <div class="tab-pane fade" id="tab-investigation">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h5 class="mb-0">Investigations</h5>
                            </div>
                            <div class="col-12 mb-4 position-relative">
                                <input type="text" class="form-control" wire:model.live.debounce.500ms="investigation_search" placeholder="Search Lab/Radiology...">
                                @if(!empty($search_results_investigation))
                                    <div class="list-group position-absolute w-100 shadow-lg mt-1" style="z-index: 1000; background: white; max-height: 200px; overflow-y: auto;">
                                        @foreach($search_results_investigation as $res)
                                            <button type="button" class="list-group-item list-group-item-action" wire:click="selectInvestigation({{ count($investigations) - 1 }}, '{{ $res['id'] }}', '{{ $res['type'] }}', '{{ addslashes($res['name']) }}')">
                                                {{ $res['name'] }} ({{ ucfirst($res['type']) }})
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Test</th>
                                            <th>Instructions</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($investigations as $idx => $inv)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="investigations[{{ $idx }}][id]" value="{{ $inv['id'] }}">
                                                    <input type="hidden" name="investigations[{{ $idx }}][type]" value="{{ $inv['type'] }}">
                                                    {{ $inv['name'] }}
                                                </td>
                                                <td><input type="text" name="investigations[{{ $idx }}][notes]" class="form-control form-control-sm" wire:model="investigations.{{ $idx }}.notes"></td>
                                                <td><button type="button" class="btn btn-sm btn-label-danger" wire:click="removeInvestigation({{ $idx }})"><i class="ti ti-trash"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-tab"><i class="ti tabler-chevron-left me-1"></i> Previous</button>
                            <button type="button" class="btn btn-primary next-tab">Next <i class="ti tabler-chevron-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- TAB 6: Prescription -->
                    <div class="tab-pane fade" id="tab-prescription">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h5 class="mb-0">Prescriptions</h5>
                            </div>
                            <div class="col-12 mb-4 position-relative">
                                <input type="text" class="form-control" wire:model.live.debounce.500ms="drug_search" placeholder="Search Drugs...">
                                @if(!empty($search_results_drug))
                                    <div class="list-group position-absolute w-100 shadow-lg mt-1" style="z-index: 1000; background: white; max-height: 200px; overflow-y: auto;">
                                        @foreach($search_results_drug as $drug)
                                            <button type="button" class="list-group-item list-group-item-action" wire:click="selectDrug({{ count($prescriptions) - 1 }}, '{{ $drug['id'] }}', '{{ addslashes($drug['name']) }}')">
                                                {{ $drug['name'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Drug</th>
                                            <th>Dosage</th>
                                            <th>Frequency</th>
                                            <th>Duration</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prescriptions as $pIdx => $pres)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="prescriptions[{{ $pIdx }}][drug_id]" value="{{ $pres['drug_id'] }}">
                                                    {{ $pres['name'] }}
                                                </td>
                                                <td><input type="text" name="prescriptions[{{ $pIdx }}][dosage]" class="form-control form-control-sm" wire:model="prescriptions.{{ $pIdx }}.dosage"></td>
                                                <td><input type="text" name="prescriptions[{{ $pIdx }}][frequency]" class="form-control form-control-sm" wire:model="prescriptions.{{ $pIdx }}.frequency"></td>
                                                <td><input type="text" name="prescriptions[{{ $pIdx }}][duration]" class="form-control form-control-sm" wire:model="prescriptions.{{ $pIdx }}.duration"></td>
                                                <td><button type="button" class="btn btn-sm btn-label-danger" wire:click="removePrescription({{ $pIdx }})"><i class="ti ti-trash"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-tab"><i class="ti tabler-chevron-left me-1"></i> Previous</button>
                            <button type="submit" class="btn btn-success"><i class="ti tabler-device-floppy me-1"></i> Complete Charting</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('click', function (e) {
        const nextBtn = e.target.closest('.next-tab');
        const prevBtn = e.target.closest('.prev-tab');

        if (!nextBtn && !prevBtn) return;

        const container = e.target.closest('.nav-align-top');
        const tabs = Array.from(container.querySelectorAll('.nav-tabs .nav-link'));
        const activeTab = container.querySelector('.nav-tabs .nav-link.active');
        const currentIndex = tabs.indexOf(activeTab);

        if (nextBtn && tabs[currentIndex + 1]) {
            bootstrap.Tab.getOrCreateInstance(tabs[currentIndex + 1]).show();
        }

        if (prevBtn && tabs[currentIndex - 1]) {
            bootstrap.Tab.getOrCreateInstance(tabs[currentIndex - 1]).show();
        }
    });

    // Handle Livewire re-renders to ensure Bootstrap tabs still work
    document.addEventListener('livewire:load', function () {
        // Any re-initialization if needed
    });
</script>
