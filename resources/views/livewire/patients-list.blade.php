<div>
    <div class="card mb-4">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h3 class="mb-1">{{ \App\Models\Patient::count() }}</h3>
                                <p class="mb-0">Total Patients</p>
                            </div>
                            <span class="avatar me-sm-4">
                                <span class="avatar-initial bg-label-secondary rounded">
                                    <i class="ti tabler-user ti-md"></i>
                                </span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                         <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                            <div>
                                <h3 class="mb-1">{{ \App\Models\Patient::whereDate('created_at', today())->count() }}</h3>
                                <p class="mb-0">New Patients Today</p>
                            </div>
                            <span class="avatar me-lg-4">
                                <span class="avatar-initial bg-label-secondary rounded">
                                    <i class="ti tabler-user-plus ti-md"></i>
                                </span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group input-group-merge">
                <span class="input-group-text" id="basic-addon-search31"><i class="ti tabler-search"></i></span>
                <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Search patients..." aria-label="Search..." aria-describedby="basic-addon-search31">
            </div>
        </div>
         <div class="col-md-8 text-md-end mt-3 mt-md-0">
            <a href="{{ route('app.patients.create') }}" class="btn btn-primary">
                <i class="ti tabler-plus me-1"></i> Add Patient
            </a>
         </div>
    </div>

    <!-- Cards Grid -->
    <div class="row g-4">
        @forelse($patients as $patient)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mx-auto mb-3">
                            @if($patient->user && $patient->user->profile_photo_url)
                                 <img src="{{ $patient->user->profile_photo_url }}" alt="Avatar" class="rounded-circle w-px-100 h-px-100 mb-3">
                            @else
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ substr($patient->user->firstname ?? 'P', 0, 1) }}{{ substr($patient->user->lastname ?? '', 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <h4 class="mb-1 card-title">{{ $patient->user->firstname ?? '' }} {{ $patient->user->lastname ?? '' }}</h4>
                        <span class="pb-1 d-block mb-1">{{ $settings->hospital_number_prefix }}{{ $patient->hospital_no }}</span>
                        <small class="text-muted d-block mb-2">{{ $patient->phone }}</small>
                        
                         <div class="d-flex align-items-center justify-content-center mb-3">
                            @if($patient->hmo_id)
                                <span class="badge bg-label-info">Insurance</span>
                            @else
                                <span class="badge bg-label-secondary">Self Pay</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                             <span class="badge bg-label-success">{{ $patient->gender ?? 'N/A' }}</span>
                             <span class="badge bg-label-info">{{ $patient->getAge() ?? 'N/A' }}</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-center">
                            <a href="{{ route('app.patients.show', $patient->id) }}" class="btn btn-primary d-flex align-items-center me-3"><i class="ti-xs me-1 tabler-user-check me-1"></i>View Folder</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>No patients found.</h4>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="row mt-4">
        <div class="col-12">
            {{ $patients->links() }}
        </div>
    </div>
</div>
