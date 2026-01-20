<?php

namespace App\Livewire;

use Livewire\Component;

class ChartComplaint extends Component
{
    public $checkIn;
    public $complaint;

    public function mount(\App\Models\CheckIn $checkIn)
    {
        $this->checkIn = $checkIn;
        $this->complaint = $checkIn->presenting_complaint;
    }

    public function save()
    {
        $this->checkIn->update([
            'presenting_complaint' => $this->complaint
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Complaint updated successfully.']);
        $this->dispatch('close-modal');
        $this->dispatch('refreshVisits'); // We might need to refresh the visits tab
    }

    public function render()
    {
        return view('livewire.chart-complaint');
    }
}
