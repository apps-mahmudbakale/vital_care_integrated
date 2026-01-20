<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RadiologyRequest;

class RadiologyRequests extends Base
{
    use WithPagination;

    public $patientId;
    public $status;
    public $priority;
    public $perPage = 10;

    public $selectedRequestId;
    public $findings;

    public function checkPayment($requestId)
    {
        $request = RadiologyRequest::find($requestId);
        if ($request->isPaid()) {
            $this->selectedRequestId = $requestId;
            $this->findings = $request->findings;
            $this->dispatch('openFindingsModal');
        } else {
            $this->dispatch('showPaymentRestrictedAlert');
        }
    }

    public function saveFindings()
    {
        $this->validate([
            'findings' => 'required|string'
        ]);

        $request = RadiologyRequest::find($this->selectedRequestId);
        $request->update([
            'findings' => $this->findings,
            'status' => 'Processing' // Auto move to processing when findings added? Or keep?
        ]);

        $this->dispatch('findingsSaved');
        $this->dispatch('closeFindingsModal');
        $this->reset(['selectedRequestId', 'findings']);
    }

    public function updating($field)
    {
        if (in_array($field, ['status', 'priority', 'patientId'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['patientId', 'status', 'priority']);
        $this->dispatch('filtersReset');
    }

    public function render()
    {
        $patients = \App\Models\Patient::with('user')->get();
        $radiologyRequests = RadiologyRequest::query()
            ->with(['patient.user', 'test', 'user', 'result'])
            ->when($this->patientId, function ($query) {
                $query->where('patient_id', $this->patientId);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->priority, function ($query) {
                $query->where('priority', $this->priority);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.radiology-requests', compact('radiologyRequests', 'patients'));
    }
}
