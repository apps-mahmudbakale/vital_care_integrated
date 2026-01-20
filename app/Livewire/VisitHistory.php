<?php

namespace App\Livewire;

use Livewire\Component;

class VisitHistory extends Component
{
    public $patient;

    protected $listeners = ['refreshVisits' => '$refresh'];

    public function mount(\App\Models\Patient $patient)
    {
        $this->patient = $patient;
    }

    public function render()
    {
        $checkIns = $this->patient->checkIns()->with(['clinic', 'appointmentType', 'specialty', 'appointment'])->get()->map(function($item) {
            $item->type = 'Visit';
            $item->date = $item->check_in_date;
            return $item;
        });

        $appointments = $this->patient->appointments()->with(['clinic', 'appointmentType', 'specialty'])->whereNotIn('status', ['Checked-in'])->get()->map(function($item) {
            $item->type = 'Appointment';
            $item->date = $item->start_at;
            return $item;
        });

        $history = $checkIns->concat($appointments)->sortByDesc('date');

        return view('livewire.visit-history', [
            'history' => $history
        ]);
    }
}
