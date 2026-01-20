<div class="navbar-search-wrapper position-relative flex-grow-1 mx-md-4" style="width: 100%; max-width: 800px;">
    <div class="input-group input-group-merge shadow-none border-0 bg-label-secondary rounded-pill">
        <span class="input-group-text bg-transparent border-0"><i class="ti tabler-search text-muted"></i></span>
        <input type="text" class="form-control bg-transparent border-0 ps-0" 
               wire:model.live.debounce.300ms="query" 
               placeholder="Search Patient (Name or Hospital No)..." 
               aria-label="Search Patient">
    </div>

    @if(!empty($results))
        <div class="dropdown-menu show w-100 shadow-lg border-0 mt-2 py-2 rounded-3 animate__animated animate__fadeIn" style="z-index: 1060;">
            @foreach($results as $patient)
                <a class="dropdown-item d-flex align-items-center py-2 px-3" href="javascript:void(0)" wire:click="selectPatient({{ $patient->id }})">
                    <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            {{ substr($patient->user->firstname, 0, 1) }}{{ substr($patient->user->lastname, 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium text-heading mb-0">{{ $patient->user->firstname }} {{ $patient->user->lastname }}</div>
                        <small class="text-muted">{{ $patient->hospital_no }}</small>
                    </div>
                    <i class="ti tabler-chevron-right text-muted icon-xs"></i>
                </a>
            @endforeach
        </div>
    @elseif(strlen($query) >= 2)
        <div class="dropdown-menu show w-100 shadow-lg border-0 mt-2 py-3 text-center rounded-3 animate__animated animate__fadeIn">
            <small class="text-muted">No patients found for "{{ $query }}"</small>
        </div>
    @endif
</div>
