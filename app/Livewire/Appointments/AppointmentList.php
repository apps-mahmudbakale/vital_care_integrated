<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\CheckIn;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $status_filter = '';
    public $view = 'calendar'; // 'calendar' or 'list'

    protected $listeners = [
        'refreshAppointments' => '$refresh',
        'updateAppointment' => 'updateAppointmentTime'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updateStatus($id, $status)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->update(['status' => $status]);
            $this->dispatch('notify', ['type' => 'success', 'message' => "Appointment marked as $status."]);
            $this->dispatch('refreshCalendar');
        }
    }

    public function delete($id)
    {
        Appointment::destroy($id);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Appointment deleted successfully.']);
        $this->dispatch('refreshCalendar');
    }

    public function updateAppointmentTime($id, $start, $end)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->update([
                'start_at' => $start,
                'end_at' => $end,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Appointment rescheduled.']);
        }
    }

    public function checkIn($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            CheckIn::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'clinic_id' => $appointment->clinic_id,
                'specialty_id' => $appointment->specialty_id,
                'appointment_type_id' => $appointment->appointment_type_id,
                'check_in_date' => now(),
            ]);

            $appointment->update(['status' => 'Checked-in']);
            
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Visit started! Patient checked in.']);
            $this->dispatch('refreshCalendar');
        }
    }

    public function getEvents()
    {
        return Appointment::with(['patient.user', 'appointmentType'])
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->user->firstname . ' ' . $appointment->patient->user->lastname . ' (' . $appointment->appointmentType->name . ')',
                    'start' => $appointment->start_at->toIso8601String(),
                    'end' => $appointment->end_at->toIso8601String(),
                    'allDay' => $appointment->is_all_day,
                    'extendedProps' => [
                        'status' => $appointment->status,
                        'hospital_no' => $appointment->patient->hospital_no,
                    ],
                    'backgroundColor' => $this->getStatusColor($appointment->status),
                    'borderColor' => $this->getStatusColor($appointment->status),
                    'textColor' => '#ffffff',
                ];
            })->toArray();
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'Scheduled' => '#7367f0', // primary
            'Checked-in' => '#00bad1', // info
            'In Progress' => '#ff9f43', // warning
            'Completed' => '#28c76f', // success
            'Cancelled' => '#ea5455', // danger
            default => '#a8aaae' // secondary
        };
    }

    public function toggleView($view)
    {
        $this->view = $view;
    }

    public function render()
    {
        $appointments = Appointment::with(['patient.user', 'appointmentType', 'clinic', 'specialty'])
            ->when($this->search, function($query) {
                $query->whereHas('patient', function($q) {
                    $q->whereHas('user', function($uq) {
                        $uq->where('firstname', 'like', '%' . $this->search . '%')
                          ->orWhere('lastname', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('hospital_no', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status_filter, function($query) {
                $query->where('status', $this->status_filter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.appointments.appointment-list', [
            'appointments' => $appointments
        ]);
    }
}
