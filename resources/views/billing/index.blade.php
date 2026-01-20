@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Billing & Transactions</h5>
            <!-- Optional: Add filters or export buttons here -->
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Service</th>
                        <th>Payer / Patient</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($billings as $billing)
                    <tr>
                        <td>{{ $billing->created_at->format('d M Y') }}</td>
                        <td>{{ Str::limit($billing->service, 30) }}</td>
                        <td>
                            @if($billing->patient)
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $billing->patient->user->firstname ?? '' }} {{ $billing->patient->user->lastname ?? '' }}</span>
                                    <small class="text-muted">{{ $billing->patient->hospital_no }}</small>
                                </div>
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </td>
                        <td>₦ {{ number_format($billing->amount, 2) }}</td>
                        <td>
                            @if($billing->status == 1)
                                <span class="badge bg-label-success me-1">Paid</span>
                            @else
                                <span class="badge bg-label-warning me-1">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if($billing->bill_ref)
                                    <a class="dropdown-item" href="javascript:void(0);" 
                                       data-url="{{ route('app.billing.show', $billing->bill_ref) }}"
                                       data-bs-toggle="modal"
                                       data-bs-target="#global-modal">
                                        <i class="ti tabler-eye me-1"></i> View Details
                                    </a>
                                    @endif
                                    <!-- Add Payment Action -->
                                    @if($billing->status == 0)
                                    <a class="dropdown-item" href="javascript:void(0);"
                                       data-url="{{ route('app.billing.payment_form', $billing->bill_ref) }}"
                                       data-bs-toggle="modal"
                                       data-bs-target="#global-modal">
                                        <i class="ti tabler-credit-card me-1"></i> Make Payment
                                    </a>
                                    @else
                                    <a class="dropdown-item" href="{{ route('app.billing.receipt', $billing->bill_ref) }}" target="_blank">
                                        <i class="ti tabler-printer me-1"></i> Print Receipt
                                    </a>
                                    @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted mb-2">No billing records found.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $billings->links() }}
        </div>
    </div>
</div>
@endsection

