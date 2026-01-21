<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LabRequest;
use App\Services\ServiceRequestHandler;

class LabRequests extends Base
{
    use WithPagination;


    public $patientId;
    public $categoryId;
    public $startDate;
    public $endDate;
    public $status;
    public $priority;
    public $perPage = 10;

    public $selectedRequestId;
    public $findings;

    public function checkPayment($requestId)
    {
        $request = LabRequest::find($requestId);
        if (!$request->isPaid()) {
            $this->dispatch('showPaymentRestrictedAlert');
            return;
        }

        if (!$request->isSpecimenReceived()) {
            $this->dispatch('showSpecimenRestrictedAlert');
            return;
        }

        $this->selectedRequestId = $requestId;
        $this->findings = $request->findings;
        $this->dispatch('openFindingsModal');
    }

    public function receiveSpecimen($requestId)
    {
        $lab = LabRequest::find($requestId);
        $serviceHandler = new ServiceRequestHandler();
        $service = "Laboratory:" . $lab->test->name;
        $paid = $serviceHandler->isBilled($lab->test_id, $service, $lab->request_ref);
        if (!$paid) {
            $this->dispatch('showPaymentRestrictedAlert');
            return;
        }
        $lab->update([
            'specimen_received_at' => now(),
            'status' => 'Processing'
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Specimen received successfully. You can now add findings.'
        ]);
    }

    public function saveFindings()
    {
        $this->validate([
            'findings' => 'required|string'
        ]);

        $request = LabRequest::find($this->selectedRequestId);
        $request->update([
            'findings' => $this->findings,
            'status' => 'Processing'
        ]);

        $this->dispatch('findingsSaved');
        $this->dispatch('closeFindingsModal');
        $this->reset(['selectedRequestId', 'findings']);
    }

    public function updating($field)
    {
        if (in_array($field, ['category_id', 'status', 'startDate', 'endDate', 'patientId', 'priority'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['patientId', 'categoryId', 'startDate', 'endDate', 'status', 'priority']);
        $this->dispatch('filtersReset');
    }

    public function render()
    {
        $patients = \App\Models\Patient::with('user')->get();
        $labRequests = LabRequest::query()
            ->when(auth()->user()->hasRole('patient'), function ($query) {
                $query->whereHas('patient', function ($q) {
                    $q->where('user_id', auth()->id());
                });
            })
            ->when($this->patientId, function ($query) {
                $query->where('patient_id', $this->patientId);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->priority, function ($query) {
                $query->where('priority', $this->priority);
            })
            ->when($this->categoryId, function ($query) {
                $query->where('category_id', $this->categoryId);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->latest()
            ->paginate($this->perPage);
        return view('livewire.lab-requests', compact('labRequests', 'patients'));
    }
}
