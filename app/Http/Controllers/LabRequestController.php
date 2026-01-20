<?php

namespace App\Http\Controllers;

use App\Models\LabRequest;
use App\Models\LabResult;
use App\Models\LabResultItem;
use App\Settings\SystemSettings;
use Illuminate\Http\Request;

class LabRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LabRequest::query()
            ->with(['patient.user', 'test', 'user']);

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

        $labRequests = $query->latest()->paginate(20)->withQueryString();

        return view('lab-requests.index', compact('labRequests'));
    }

    public function create(\App\Models\Patient $patient)
    {
        return view('lab-requests.create', compact('patient'));
    }

    public function store(Request $request)
    {
        // Handled by Livewire for now, but keeping for completeness if needed
    }

    public function addFindings(Request $request)
    {
        $result = LabResult::updateOrCreate(
        ['lab_request_id' => $request->lab_id],
        array_merge($request->all(), [
            'user_id' => auth()->user()->id,
        ])
    );

    // Ensure we start with a clean list of items for this result
    $result->items()->delete();

    foreach ($request->items as $templateItemId => $value) {
      LabResultItem::create([
        'lab_result_id' => $result->id,
        'lab_template_item_id' => $templateItemId,
        'value' => $value,
      ]);
    }

        $labRequest = LabRequest::findOrFail($request->lab_id);
        $labRequest->update([
            'status' => 'Completed',
        ]);
    
    return redirect()->route('app.lab-requests.index')->with('success', 'Lab findings added successfully.');
    }
    

    public function show(LabRequest $labRequest)
    {
        if (request()->ajax()) {
            return view('lab-requests.show-modal', compact('labRequest'));
        }
        return view('lab-requests.show', compact('labRequest'));
    }

    public function edit(LabRequest $labRequest)
    {
        $labTests = \App\Models\LabTest::orderBy('name')->get();
        if (request()->ajax()) {
            return view('lab-requests.edit-modal', compact('labRequest', 'labTests'));
        }
        return view('lab-requests.edit', compact('labRequest', 'labTests'));
    }

    public function findings(LabRequest $labRequest)
    {
        // dd($labRequest->test->template->items);
        return view('lab-requests.findings', compact('labRequest'));
    }

    public function print(LabRequest $labRequest, SystemSettings $settings)
    {
        $labRequest->load(['patient.user', 'test.template', 'user']);
        $result = LabResult::where('lab_request_id', $labRequest->id)
            ->with(['items.templateItem.parameter', 'approvedBy', 'user'])
            ->first();

        if (!$result) {
            return back()->with('error', 'No results found for this laboratory request.');
        }

        if (!$result->is_approved) {
            return back()->with('error', 'This result has not been approved and cannot be printed.');
        }

        return view('lab-requests.print', compact('labRequest', 'result', 'settings'));
    }

    public function approve(LabRequest $labRequest)
    {
        $result = LabResult::where('lab_request_id', $labRequest->id)->first();
        
        if (!$result) {
            return back()->with('error', 'Result entry not found.');
        }

        $result->update([
            'is_approved' => true,
            'approved_by' => auth()->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Lab result approved successfully.');
    }

    public function update(Request $request, LabRequest $labRequest)
    {
        $request->validate([
            'priority' => 'required',
            'status' => 'required',
        ]);

        $labRequest->update($request->only('priority', 'status', 'request_note'));

        return redirect()->route('app.lab-requests.index')->with('success', 'Lab request updated successfully.');
    }

    public function destroy(LabRequest $labRequest)
    {
        $labRequest->delete();
        return back()->with('success', 'Lab request deleted successfully.');
    }
}
