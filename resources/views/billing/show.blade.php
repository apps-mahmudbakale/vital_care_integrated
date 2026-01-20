<div class="modal-header">
    <h5 class="modal-title" id="modalTitle">Billing Details #{{ $ref }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row mb-4">
        <div class="col-sm-6">
            <h6 class="mb-1">Payer:</h6>
            @if($billing->patient)
                <p class="mb-1 fw-bold">{{ $billing->patient->user->firstname }} {{ $billing->patient->user->lastname }}</p>
                <p class="mb-1 text-muted">{{ $billing->patient->hospital_no }}</p>
            @else
                <p class="text-muted">Unknown</p>
            @endif
        </div>
        <div class="col-sm-6 text-sm-end">
            <h6 class="mb-1">Date:</h6>
            <p class="mb-1">{{ $billing->created_at->format('d M Y, h:i A') }}</p>
            <div>
                @if($billing->status == 1)
                    <span class="badge bg-label-success">Paid</span>
                @else
                    <span class="badge bg-label-warning">Unpaid</span>
                @endif
            </div>
        </div>
    </div>

    <div class="table-responsive border rounded mb-3">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="fw-bold">Service Description</th>
                    <th class="text-center fw-bold">Qty</th>
                    <th class="text-end fw-bold">Amount</th>
                </tr>
            </thead>
            <tbody>
                {{-- Assuming multiple items sharing the same ref might be passed, or just this one --}}
                {{-- Since we fetched a single billing record in controller but grouped by ref logic suggests typically multiple --}}
                {{-- For now, let's display the single passed billing item, but querying by ref might return multiple in reality --}}
                @php
                    $items = \App\Models\Billing::where('bill_ref', $ref)->get();
                @endphp
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->service }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">₦ {{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total Amount:</td>
                    <td class="text-end fw-bold">₦ {{ number_format($amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
    <a href="{{ route('app.billing.receipt', $ref) }}" target="_blank" class="btn btn-primary">
        <i class="ti tabler-printer me-1"></i> Print Receipt
    </a>
</div>
