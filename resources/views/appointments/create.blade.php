<div class="modal-header border-bottom">
    <h5 class="modal-title fw-bold">Schedule New Appointment</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('app.appointments.store') }}" method="POST" id="appointmentForm">
    @csrf
    <div class="modal-body">
        <div class="row">
            <!-- Patient Selection -->
            <div class="form-group col-md-12 mb-3">
                <label class="form-label">
                    Patient <span class="text-danger">*</span>
                </label>
                <select name="patient_id" class="select2 form-select" required>
                    <option value="">Select Patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->user->firstname }} {{ $patient->user->lastname }} ({{ $patient->hospital_no }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Appointment Type -->
            <div class="form-group col-md-6 mb-3">
                <label class="form-label">
                    Appointment Type <span class="text-danger">*</span>
                </label>
                <select name="appointment_type_id" class="select2 form-select" required>
                    <option value="">Select Type</option>
                    @foreach($appointmentTypes as $type)
                        <option value="{{ $type->id }}">{{ strtoupper($type->name) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clinic -->
            <div class="form-group col-md-6 mb-3">
                <label class="form-label">
                    Clinic <span class="text-danger">*</span>
                </label>
                <select name="clinic_id" class="select2 form-select" required>
                    <option value="">Select Clinic</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}">{{ strtoupper($clinic->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <!-- Specialty -->
            <div class="form-group col-md-12 mb-3">
                <label class="form-label">
                    Specialty <span class="text-danger">*</span>
                </label>
                <select name="specialty_id" class="select2 form-select" required>
                    <option value="">Select Specialty</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ strtoupper($specialty->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <!-- Follow Up -->
            <div class="form-group col-md-6 mb-3">
                <div class="form-check mt-md-4">
                    <input class="form-check-input" type="checkbox" name="is_follow_up" value="1" id="id_follow_up">
                    <label class="form-check-label" for="id_follow_up">
                        Follow Up Appointment?
                    </label>
                </div>
            </div>

            <!-- All Day -->
            <div class="form-group col-md-6 mb-3">
                <div class="form-check mt-md-4">
                    <input class="form-check-input" type="checkbox" name="is_all_day" value="1" id="id_all_day">
                    <label class="form-check-label" for="id_all_day">
                        Is All Day
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Start Time -->
            <div class="form-group col-md-6 mb-3">
                <label class="form-label">
                    Start <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control" name="start_at" required>
            </div>

            <!-- End Time -->
            <div class="form-group col-md-6 mb-3">
                <label class="form-label">
                    End <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control" name="end_at" required>
            </div>
        </div>

        <div class="row">
            <!-- Notes -->
            <div class="form-group col-md-12 mb-3">
                <label class="form-label">Notes (Optional)</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Any additional information..."></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer border-top">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti tabler-calendar-plus me-1"></i> Schedule
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.post(url, data, function(response) {
            $('#global-modal').modal('hide');
            
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('refreshAppointments');
                
                if (response.check_in_prompt) {
                    // Trigger the confirm-checkin event that AppointmentList listens for
                    Livewire.dispatch('confirm-checkin', {
                        id: response.appointment_id,
                        patient: response.patient_name
                    });
                } else {
                    Livewire.dispatch('notify', {type: 'success', message: response.message});
                }
            } else {
                Swal.fire('Success', response.message, 'success').then(() => {
                    location.reload();
                });
            }
        }).fail(function(xhr) {
            let errors = xhr.responseJSON.errors;
            let errorMsg = '';
            for (let field in errors) {
                errorMsg += errors[field][0] + '<br>';
            }
            Swal.fire('Error', errorMsg, 'error');
        });
    });

    if ($.fn.select2) {
        $('.select2').select2({
            dropdownParent: $('#global-modal')
        });
    }
});
</script>
