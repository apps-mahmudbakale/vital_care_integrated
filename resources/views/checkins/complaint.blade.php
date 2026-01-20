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
@php
    $patient = $checkIn->patient;
@endphp

<div>
    <form action="{{ route('app.checkins.charting.store', $checkIn) }}" method="POST" id="chartingForm">
        @csrf

        <!-- Patient Info Card -->
        <div class="card mb-4 border-0 shadow-none bg-label-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <div class="avatar avatar-xl">
                            <span class="avatar-initial rounded bg-primary shadow-sm"><i class="ti tabler-user fs-1"></i></span>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="d-flex flex-wrap align-items-center mb-1">
                            <h4 class="mb-0 me-2 fw-bold text-primary">{{ $patient->user->FullName() }}</h4>
                            <span class="badge bg-label-secondary border">{{ $patient->hospital_no }}</span>
                        </div>
                        <div class="d-flex flex-wrap gap-3">
                            <span class="d-flex align-items-center gap-1"><i class="ti tabler-calendar fs-6"></i> {{ $patient->getAge() }}</span>
                            <span class="d-flex align-items-center gap-1"><i class="ti tabler-{{ strtolower($patient->gender) == 'male' ? 'gender-male' : (strtolower($patient->gender) == 'female' ? 'gender-female' : 'gender-intergender') }} fs-6"></i> {{ ucfirst($patient->gender) }}</span>
                            <span class="d-flex align-items-center gap-1"><i class="ti tabler-phone fs-6"></i> {{ $patient->phone }}</span>
                            <span class="d-flex align-items-center gap-1"><i class="ti tabler-shield-check fs-6"></i> {{ $patient->hmo->name ?? 'Self Pay' }}</span>
                        </div>
                    </div>
                    <div class="col-md-auto mt-3 mt-md-0 d-flex gap-2">
                        <div class="text-md-end">
                            <small class="text-muted d-block">Visit Date</small>
                            <span class="fw-bold fs-6">{{ $checkIn->created_at->format('d M, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                            <i class="ti ti-activity fs-1 text-muted mb-3"></i>
                                            <p class="text-muted">No vital signs recorded for this patient.</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vital-modal">
                                                <i class="ti tabler-plus me-1"></i> Record Vitals Now
                                            </button>
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
                        <div id="diagnoses-container">
                            <div class="diagnosis-entry mb-4">
                                <fieldset class="border p-3 rounded position-relative">
                                    <legend class="float-none w-auto px-2 fs-6 fw-bold text-primary">Diagnosis</legend>
                                    <button type="button" class="btn btn-sm btn-icon btn-label-danger position-absolute top-0 end-0 mt-n2 me-2 remove-diagnosis" style="display: none;">
                                        <i class="ti tabler-trash"></i>
                                    </button>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">ICD-10 Code</label>
                                            <select name="diagnoses[0][icd_10_id]" class="form-select select2">
                                                <option value="">Select Diagnosis Code</option>
                                                @foreach($icds as $icd)
                                                <option value="{{$icd->id}}">{{$icd->number}} - {{$icd->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="diagnoses[0][status]" class="form-select select2">
                                                <option value="">Select Status</option>
                                                <option value="confirmed">Confirmed</option>
                                                <option value="suspected">Suspected</option>
                                                <option value="rule_out">Rule Out</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Comments</label>
                                            <textarea name="diagnoses[0][notes]" class="form-control" rows="1" placeholder="Comments"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <button type="button" class="btn btn-sm btn-label-primary w-100" id="add-diagnosis">
                                <i class="ti tabler-plus me-1"></i> Add Another Diagnosis
                            </button>
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
                            <div class="col-12 mb-3">
                                <h5 class="mb-0">Procedures</h5>
                            </div>
                            <div class="col-12 mb-4">
                                <div id="procedures-container">
                                    <div class="procedure-entry mb-3">
                                        <fieldset class="border p-3 rounded position-relative">
                                            <legend class="float-none w-auto px-2 fs-7 fw-bold text-primary">Procedure</legend>
                                            <button type="button" class="btn btn-sm btn-icon btn-label-danger position-absolute top-0 end-0 mt-n2 me-2 remove-procedure" style="display: none;">
                                                <i class="ti tabler-trash"></i>
                                            </button>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Procedure</label>
                                                    <select name="procedures[0][procedure_id]" class="form-select select2">
                                                        <option value="">Select Procedure</option>
                                                        @foreach(\App\Models\Procedure::all() as $procedure)
                                                        <option value="{{$procedure->id}}">{{$procedure->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">ICD-10</label>
                                                    <select name="procedures[0][icd_10_id]" class="form-select select2">
                                                        <option value="">Select ICD-10 Code</option>
                                                        @foreach($icds as $icd)
                                                        <option value="{{$icd->id}}">{{$icd->number}} - {{$icd->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <textarea name="procedures[0][notes]" class="form-control" rows="1" placeholder="Notes/Comments"></textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-label-primary w-100 mt-2" id="add-procedure">
                                    <i class="ti tabler-plus me-1"></i> Add Another Procedure
                                </button>
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
                            
                            <!-- Lab Investigations Section -->
                            <div class="col-12 mb-4">
                                <h6 class="fw-bold text-secondary mb-3"><i class="ti tabler-flask me-1"></i> Lab Investigations</h6>
                                <div id="lab-investigations-container">
                                    <div class="lab-entry mb-3">
                                        <fieldset class="border p-3 rounded position-relative">
                                            <legend class="float-none w-auto px-2 fs-7 fw-bold text-primary">Lab Test</legend>
                                            <button type="button" class="btn btn-sm btn-icon btn-label-danger position-absolute top-0 end-0 mt-n2 me-2 remove-investigation" style="display: none;">
                                                <i class="ti tabler-trash"></i>
                                            </button>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Search Lab Test</label>
                                                    <input type="hidden" name="investigations[0][type]" value="lab">
                                                    <select name="investigations[0][id]" class="form-select select2">
                                                        <option value="">Select Lab Investigation</option>
                                                        @foreach(\App\Models\LabTest::all() as $lab_investigation)
                                                        <option value="{{$lab_investigation->id}}">{{$lab_investigation->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Notes</label>
                                                    <textarea name="investigations[0][notes]" class="form-control" rows="1" placeholder="Instructions/Notes"></textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-label-primary w-100 mt-2" id="add-lab-investigation">
                                    <i class="ti tabler-plus me-1"></i> Add Another Lab Investigation
                                </button>
                            </div>

                            <hr class="my-4">

                            <!-- Radiology Investigations Section -->
                            <div class="col-12 mb-4">
                                <h6 class="fw-bold text-secondary mb-3"><i class="ti tabler-scan me-1"></i> Radiology Investigations</h6>
                                <div id="radiology-investigations-container">
                                    <div class="radiology-entry mb-3">
                                        <fieldset class="border p-3 rounded position-relative">
                                            <legend class="float-none w-auto px-2 fs-7 fw-bold text-primary">Radiology Test</legend>
                                            <button type="button" class="btn btn-sm btn-icon btn-label-danger position-absolute top-0 end-0 mt-n2 me-2 remove-investigation" style="display: none;">
                                                <i class="ti tabler-trash"></i>
                                            </button>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Search Radiology Test</label>
                                                    <input type="hidden" name="investigations[100][type]" value="radiology">
                                                    <select name="investigations[100][id]" class="form-select select2">
                                                        <option value="">Select Radiology Investigation</option>
                                                        @foreach(\App\Models\RadiologyTest::all() as $radiology_investigation)
                                                        <option value="{{$radiology_investigation->id}}">{{$radiology_investigation->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Notes</label>
                                                    <textarea name="investigations[100][notes]" class="form-control" rows="1" placeholder="Instructions/Notes"></textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-label-primary w-100 mt-2" id="add-radiology-investigation">
                                    <i class="ti tabler-plus me-1"></i> Add Another Radiology Investigation
                                </button>
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
                            <div class="col-12 mb-4">
                                <h6 class="fw-bold text-secondary mb-3"><i class="ti tabler-pill me-1"></i> Drug Prescriptions</h6>
                                <div id="prescriptions-container">
                                    <!-- Dynamic Prescription Entry -->
                                    <div class="prescription-entry mb-3">
                                        <fieldset class="border p-3 rounded position-relative">
                                            <legend class="float-none w-auto px-2 fs-7 fw-bold text-primary">Prescription</legend>
                                            <button type="button" class="btn btn-sm btn-icon btn-label-danger position-absolute top-0 end-0 mt-n2 me-2 remove-prescription" style="display: none;">
                                                <i class="ti tabler-trash"></i>
                                            </button>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Pharmacy Store</label>
                                                    <select name="prescriptions[0][store_id]" class="form-select select2 location-select">
                                                        <option value="">Select Location</option>
                                                        @foreach(\App\Models\PharmacyStore::all() as $store)
                                                        <option value="{{$store->id}}">{{$store->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Generic Name</label>
                                                    <select name="prescriptions[0][generic_name]" class="form-select select2 generic-select" disabled>
                                                        <option value="">Select Generic</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Brand Name</label>
                                                    <select name="prescriptions[0][drug_id]" class="form-select select2 brand-select" disabled>
                                                        <option value="">Select Brand</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <label class="form-label">Dose</label>
                                                            <input type="text" name="prescriptions[0][dosage]" class="form-control" placeholder="e.g 500mg">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label">Frequency</label>
                                                            <input type="text" name="prescriptions[0][frequency]" class="form-control" placeholder="e.g 2 times daily">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <label class="form-label">Duration</label>
                                                            <input type="number" name="prescriptions[0][duration]" class="form-control" placeholder="e.g 5">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label">Unit</label>
                                                            <select name="prescriptions[0][duration_unit]" class="form-select select2">
                                                                <option value="">Select Unit</option>
                                                                <option value="minutes">Minutes</option>
                                                                <option value="hours">Hours</option>
                                                                <option value="days">Days</option>
                                                                <option value="weeks">Weeks</option>
                                                                <option value="months">Months</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <textarea name="prescriptions[0][notes]" class="form-control" rows="1" placeholder="Instructions/Notes"></textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-label-primary w-100 mt-2" id="add-prescription">
                                    <i class="ti tabler-plus me-1"></i> Add Another Prescription
                                </button>
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
    $(document).ready(function() {
        function initSelect2(el) {
            if ($.fn.select2) {
                $(el).select2({
                    placeholder: $(el).find('option:first').text() || 'Select Option',
                    allowClear: true,
                    dropdownParent: $(el).closest('.modal').length ? $(el).closest('.modal') : $(document.body)
                });
            }
        }

        // Initialize initial Select2 elements
        $('.select2').each(function() {
            initSelect2(this);
        });

        // Diagnosis Repeater Logic
        let diagnosisCount = $('#diagnoses-container .diagnosis-entry').length;
        $('#add-diagnosis').on('click', function() {
            let container = $('#diagnoses-container');
            let firstEntry = container.find('.diagnosis-entry').first();
            let newEntry = firstEntry.clone();
            newEntry.find('[name]').each(function() {
                let name = $(this).attr('name');
                if (name) $(this).attr('name', name.replace(/\[\d+\]/, '[' + diagnosisCount + ']'));
            });
            newEntry.find('select').val('');
            newEntry.find('textarea').val('');
            newEntry.find('.select2-container').remove();
            newEntry.find('.select2').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').find('option').removeAttr('data-select2-id');
            newEntry.find('.remove-diagnosis').show();
            container.append(newEntry);
            newEntry.find('.select2').each(function() { initSelect2(this); });
            diagnosisCount++;
        });

        $(document).on('click', '.remove-diagnosis', function() {
            $(this).closest('.diagnosis-entry').remove();
        });

        // Investigations Repeater Logic
        let labCount = $('#lab-investigations-container .lab-entry').length;
        let radiologyCount = 100 + $('#radiology-investigations-container .radiology-entry').length;

        function addInvestigationRow(containerId, entryClass, counter, type) {
            let container = $('#' + containerId);
            let firstEntry = container.find('.' + entryClass).first();
            let newEntry = firstEntry.clone();
            
            newEntry.find('[name]').each(function() {
                let name = $(this).attr('name');
                if (name) $(this).attr('name', name.replace(/\[\d+\]/, '[' + counter + ']'));
            });

            newEntry.find('select').val('');
            newEntry.find('textarea').val('');
            newEntry.find('.select2-container').remove();
            newEntry.find('.select2').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').find('option').removeAttr('data-select2-id');
            newEntry.find('.remove-investigation').show();
            
            container.append(newEntry);
            newEntry.find('.select2').each(function() { initSelect2(this); });
            return counter + 1;
        }

        $('#add-lab-investigation').on('click', function() {
            labCount = addInvestigationRow('lab-investigations-container', 'lab-entry', labCount, 'lab');
        });

        $('#add-radiology-investigation').on('click', function() {
            radiologyCount = addInvestigationRow('radiology-investigations-container', 'radiology-entry', radiologyCount, 'radiology');
        });

        $(document).on('click', '.remove-investigation', function() {
            $(this).closest('.lab-entry, .radiology-entry').remove();
        });

        // Procedure Repeater Logic
        let procedureCount = $('#procedures-container .procedure-entry').length;

        $('#add-procedure').on('click', function() {
            let container = $('#procedures-container');
            let firstEntry = container.find('.procedure-entry').first();
            let newEntry = firstEntry.clone();
            
            newEntry.find('[name]').each(function() {
                let name = $(this).attr('name');
                if (name) $(this).attr('name', name.replace(/\[\d+\]/, '[' + procedureCount + ']'));
            });

            newEntry.find('select').val('');
            newEntry.find('textarea').val('');
            
            newEntry.find('.select2-container').remove();
            newEntry.find('.select2').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').find('option').removeAttr('data-select2-id');

            newEntry.find('.remove-procedure').show();
            
            container.append(newEntry);
            newEntry.find('.select2').each(function() { initSelect2(this); });
            procedureCount++;
        });

        $(document).on('click', '.remove-procedure', function() {
            $(this).closest('.procedure-entry').remove();
        });

        // Prescription Repeater Logic
        let prescriptionCount = $('#prescriptions-container .prescription-entry').length;

        $('#add-prescription').on('click', function() {
            let container = $('#prescriptions-container');
            let firstEntry = container.find('.prescription-entry').first();
            let newEntry = firstEntry.clone();
            
            newEntry.find('[name]').each(function() {
                let name = $(this).attr('name');
                if (name) $(this).attr('name', name.replace(/\[\d+\]/, '[' + prescriptionCount + ']'));
            });

            newEntry.find('select').val('').prop('disabled', function(i, v) { return $(this).hasClass('generic-select') || $(this).hasClass('brand-select'); });
            newEntry.find('input, textarea').val('');
            
            // Remove Select2 artifacts
            newEntry.find('.select2-container').remove();
            newEntry.find('.select2').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').find('option').removeAttr('data-select2-id').end().find('optgroup').removeAttr('data-select2-id');
            newEntry.find('.generic-select').empty().append('<option value="">Select Generic</option>');
            newEntry.find('.brand-select').empty().append('<option value="">Select Brand</option>');

            newEntry.find('.remove-prescription').show();
            
            container.append(newEntry);
            newEntry.find('.select2').each(function() { initSelect2(this); });
            prescriptionCount++;
        });

        $(document).on('click', '.remove-prescription', function() {
            $(this).closest('.prescription-entry').remove();
        });

        // Dynamic Drug Fetching
        $(document).on('change', '.location-select', function() {
            let storeId = $(this).val();
            let container = $(this).closest('.prescription-entry');
            let genericSelect = container.find('.generic-select');
            let brandSelect = container.find('.brand-select');

            genericSelect.prop('disabled', true).empty().append('<option value="">Loading...</option>');
            brandSelect.prop('disabled', true).empty().append('<option value="">Loading...</option>');

            if (storeId) {
                $.ajax({
                    url: '/app/checkins/charting/drugs/' + storeId,
                    type: 'GET',
                    success: function(response) {
                        genericSelect.empty().append('<option value="">Select Generic</option>');
                        brandSelect.empty().append('<option value="">Select Brand</option>');

                        if (response.generics.length > 0) {
                            $.each(response.generics, function(key, value) {
                                genericSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            genericSelect.prop('disabled', false);
                        }

                        if (response.brands.length > 0) {
                            $.each(response.brands, function(key, value) {
                                brandSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            brandSelect.prop('disabled', false);
                        }
                    },
                    error: function() {
                        genericSelect.empty().append('<option value="">Error loading</option>');
                        brandSelect.empty().append('<option value="">Error loading</option>');
                    }
                });
            } else {
                genericSelect.empty().append('<option value="">Select Generic</option>');
                brandSelect.empty().append('<option value="">Select Brand</option>');
            }
        });
    });

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

    window.addEventListener('refreshVitals', event => {
        window.location.reload();
    });
</script>

<!-- Record Vitals Modal -->
<div class="modal fade" id="vital-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="mb-2">Record Patient Vitals</h3>
                    <p class="text-muted small">Capture current vital signs for {{ $patient->user->FullName() }}</p>
                </div>
                @livewire('vital-form', ['patient' => $patient])
            </div>
        </div>
    </div>
</div>
