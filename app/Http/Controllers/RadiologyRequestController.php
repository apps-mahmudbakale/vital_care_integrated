<?php

namespace App\Http\Controllers;

use App\Models\RadiologyRequest;
use App\Models\RadiologyResult;
use App\Settings\SystemSettings;
use Illuminate\Http\Request;

class RadiologyRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = RadiologyRequest::query()
            ->with(['patient.user', 'test', 'user', 'result']);

        // Search by Patient Name or Test Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient.user', function ($sub) use ($search) {
                    $sub->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%");
                })->orWhereHas('test', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $radiologyRequests = $query->latest()->paginate(20)->withQueryString();

        return view('radiology-requests.index', compact('radiologyRequests'));
    }

    public function create(\App\Models\Patient $patient)
    {
        return view('radiology-requests.create', compact('patient'));
    }

    public function store(Request $request)
    {
        // Handled by Livewire for now
    }

    public function show(RadiologyRequest $radiologyRequest)
    {
        if (request()->ajax()) {
            return view('radiology-requests.show-modal', compact('radiologyRequest'));
        }
        return view('radiology-requests.show', compact('radiologyRequest'));
    }

    public function edit(RadiologyRequest $radiologyRequest)
    {
        $radiologyTests = \App\Models\RadiologyTest::orderBy('name')->get();
        if (request()->ajax()) {
            return view('radiology-requests.edit-modal', compact('radiologyRequest', 'radiologyTests'));
        }
        return view('radiology-requests.edit', compact('radiologyRequest', 'radiologyTests'));
    }

    public function update(Request $request, RadiologyRequest $radiologyRequest)
    {
        $request->validate([
            'priority' => 'required',
            'status' => 'required',
        ]);

        $radiologyRequest->update($request->only('priority', 'status', 'request_note'));

        return redirect()->route('app.radiology-requests.index')->with('success', 'Radiology request updated successfully.');
    }

    public function destroy(RadiologyRequest $radiologyRequest)
    {
        $radiologyRequest->delete();
        return back()->with('success', 'Radiology request deleted successfully.');
    }

    public function findings(RadiologyRequest $radiologyRequest)
    {
        if (!$radiologyRequest->isPaid()) {
            return "<script>
                $('#global-modal').modal('hide');
                Swal.fire({
                    title: 'Payment Required!',
                    text: 'This service has not been paid for. Please ensure payment is confirmed before adding findings.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Understood'
                });
            </script>";
        }
        
        $radiologyRequest->load(['patient.user', 'test', 'result']);
        return view('radiology-requests.findings', compact('radiologyRequest'));
    }

    public function addFindings(Request $request)
    {
        $request->validate([
            'radiology_request_id' => 'required|exists:radiology_requests,id',
            'findings' => 'required',
        ]);

        $radiologyRequest = RadiologyRequest::findOrFail($request->radiology_request_id);

        if (!$radiologyRequest->isPaid()) {
            return back()->with('error', 'Cannot save findings. Payment is required.');
        }

        RadiologyResult::updateOrCreate(
            ['radiology_request_id' => $radiologyRequest->id],
            [
                'patient_id' => $radiologyRequest->patient_id,
                'radiology_test_id' => $radiologyRequest->radiology_test_id,
                'user_id' => auth()->id(),
                'findings' => $request->findings,
            ]
        );

        $radiologyRequest->update([
            'status' => 'Completed',
            'findings' => $request->findings
        ]);

        return redirect()->route('app.radiology-requests.index')->with('success', 'Radiology findings saved successfully.');
    }

    public function print(RadiologyRequest $radiologyRequest, SystemSettings $settings)
    {
        $radiologyRequest->load(['patient.user', 'test']);
        
        $result = RadiologyResult::where('radiology_request_id', $radiologyRequest->id)
            ->with(['approvedBy', 'user'])
            ->first();

        if (!$result) {
            return back()->with('error', 'Result not found.');
        }

        if (!$result->is_approved) {
             return back()->with('error', 'Result must be approved before printing.');
        }

        return view('radiology-requests.print', compact('radiologyRequest', 'result', 'settings'));
    }

    public function approve(RadiologyRequest $radiologyRequest)
    {
        $result = RadiologyResult::where('radiology_request_id', $radiologyRequest->id)->first();

        if ($result) {
            $result->update([
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
            
            return back()->with('success', 'Radiology result approved successfully.');
        }

        return back()->with('error', 'No result found to approve.');
    }
}
