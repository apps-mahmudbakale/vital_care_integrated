<div wire:ignore.self>
    <div class="card h-100 shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-md-center flex-column flex-md-row gap-3">
            <h5 class="card-title mb-0 fw-bold">Appointments Management</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm {{ $view === 'calendar' ? 'active' : '' }}" wire:click="toggleView('calendar')">
                        <i class="ti tabler-calendar me-1"></i> Calendar
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm {{ $view === 'list' ? 'active' : '' }}" wire:click="toggleView('list')">
                        <i class="ti tabler-list me-1"></i> List
                    </button>
                </div>
                <button class="btn btn-primary btn-sm waves-effect waves-light" 
                        data-bs-toggle="modal" 
                        data-bs-target="#global-modal" 
                        data-url="{{ route('app.appointments.create') }}">
                    <i class="ti tabler-plus me-1"></i> New Appointment
                </button>
            </div>
        </div>

        <div class="card-body pt-0">
            <!-- Calendar View - FULL SPACE -->
            @if($view === 'calendar')
                <div wire:ignore id="calendar-container" class="mt-4" style="min-height: 700px;">
                    <div id="calendar"></div>
                </div>
            @endif

            <!-- List View - FULL SPACE -->
            @if($view === 'list')
                <div class="row g-3 mb-4 mt-2">
                    <div class="col-md-8">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti tabler-search"></i></span>
                            <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Search by Patient Name or Hospital Number...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" wire:model.live="status_filter">
                            <option value="">All Statuses</option>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Checked-in">Checked-in</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover border-top">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Timing</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ substr($appointment->patient->user->firstname, 0, 1) }}{{ substr($appointment->patient->user->lastname, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-medium text-heading">{{ $appointment->patient->user->firstname }} {{ $appointment->patient->user->lastname }}</div>
                                                <small class="text-muted">{{ $appointment->patient->hospital_no }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($appointment->is_all_day)
                                            <span class="badge bg-label-info mb-1">All Day</span><br>
                                        @endif
                                        <div class="small fw-medium">{{ $appointment->start_at->format('M j, Y') }}</div>
                                        <div class="small text-muted">{{ $appointment->start_at->format('h:i A') }} - {{ $appointment->end_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-medium">{{ $appointment->appointmentType->name }}</div>
                                        <div class="small text-muted">{{ $appointment->clinic->name }} | {{ $appointment->specialty->name }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($appointment->status) {
                                                'Scheduled' => 'bg-label-primary',
                                                'Checked-in' => 'bg-label-info',
                                                'In Progress' => 'bg-label-warning',
                                                'Completed' => 'bg-label-success',
                                                'Cancelled' => 'bg-label-danger',
                                                default => 'bg-label-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $appointment->status }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                                                <i class="ti tabler-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:void(0)" wire:click="updateStatus({{ $appointment->id }}, 'Checked-in')">
                                                    <i class="ti tabler-user-check me-1"></i> Check-in
                                                </a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0)" wire:click="updateStatus({{ $appointment->id }}, 'Completed')">
                                                    <i class="ti tabler-check me-1"></i> Mark Completed
                                                </a></li>
                                                <li><a class="dropdown-item text-warning" href="javascript:void(0)" wire:click="updateStatus({{ $appointment->id }}, 'Cancelled')">
                                                    <i class="ti tabler-x me-1"></i> Cancel
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delete({{ $appointment->id }})">
                                                    <i class="ti tabler-trash me-1"></i> Delete
                                                </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <div class="mb-3"><i class="ti tabler-calendar-off icon-lg opacity-50"></i></div>
                                        No appointments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>



    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" />
        <style>
            .fc .fc-button-primary {
                background-color: #7367f0 !important;
                border-color: #7367f0 !important;
            }
            .fc .fc-button-primary:hover {
                background-color: #6e6b7b !important;
                border-color: #6e6b7b !important;
            }
            .fc .fc-toolbar-title {
                font-size: 1.25rem !important;
                font-weight: 600;
            }
            .fc .fc-event {
                cursor: pointer;
                padding: 2px 5px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

        <script>
            document.addEventListener('livewire:init', () => {
                let calendarEl = document.getElementById('calendar');
                let calendar = null;

                function initCalendar() {
                    if (!calendarEl) return;
                    if (typeof FullCalendar === 'undefined') {
                        console.error('FullCalendar not loaded');
                        return;
                    }

                    if (calendar) {
                        calendar.destroy();
                    }

                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        height: 'auto', // Takes full available height
                        editable: true,
                        selectable: true,
                        events: @json($this->getEvents()),
                        eventClick: function(info) {
                            info.jsEvent.preventDefault();
                            // Optional: open edit modal here later
                        },
                        eventDrop: function(info) {
                            @this.dispatch('updateAppointment', {
                                id: info.event.id,
                                start: info.event.startStr,
                                end: info.event.endStr || info.event.startStr
                            });
                        },
                        eventResize: function(info) {
                            @this.dispatch('updateAppointment', {
                                id: info.event.id,
                                start: info.event.startStr,
                                end: info.event.endStr
                            });
                        }
                    });

                    calendar.render();
                }

                // Init on page load if calendar is active
                if (@js($view) === 'calendar') {
                    setTimeout(initCalendar, 200);
                }

                // Handle view switching
                Livewire.on('toggle-view', (view) => {
                    if (view === 'calendar') {
                        setTimeout(() => {
                            initCalendar();
                            window.dispatchEvent(new Event('resize'));
                        }, 200);
                    }
                    // No action needed for list view - calendar is hidden by Blade
                });

                Livewire.on('refreshCalendar', () => {
                    if (calendar) {
                        calendar.refetchEvents();
                    } else if (@js($view) === 'calendar') {
                        setTimeout(initCalendar, 200);
                    }
                });

                Livewire.on('hide-appointment-modal', () => {
                    const modalEl = document.getElementById('appointmentModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    if (calendar) calendar.refetchEvents();
                });

                Livewire.on('confirm-checkin', (data) => {
                    Swal.fire({
                        title: 'Start Visit?',
                        text: `Would you like to start the visit for ${data.patient} now?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Start Visit',
                        customClass: {
                            confirmButton: 'btn btn-primary me-3',
                            cancelButton: 'btn btn-label-secondary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('checkIn', data.id);
                        }
                    });
                });
            });
        </script>
    @endpush
</div>@