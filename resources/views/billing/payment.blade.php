<div class="modal-header">
    <h5 class="modal-title" id="modalTitle">Make Payment for Bill #{{ $ref }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('app.billing.process_payment') }}" method="POST">
    @csrf
    <div class="modal-body">
        <input type="hidden" name="bill_ref" value="{{ $ref }}">
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="ti tabler-info-circle me-2"></i>
            <div>
                Total Due: <strong>₦ {{ number_format($amount, 2) }} {{$billing->user_id}}</strong>
            </div>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Cash Point</label>
            <select id="cashpoint" name="cashpoint_id" class="form-select" required>
                <option value="">Select Cash Point</option>
               @foreach(\App\Models\CashPoint::all() as $point)
               <option value="{{$point->id}}">{{$point->name}}</option>
               @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select id="payment_method" name="payment_method" class="form-select" required>
                <option value="">Select Method</option>
                @foreach(\App\Models\PaymentMethod::all() as $method)
                <option value="{{$method->id}}">{{$method->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="amount_paid" class="form-label">Amount to Pay</label>
            <div class="input-group input-group-merge">
                <span class="input-group-text">₦</span>
                <input type="number" step="0.01" class="form-control" id="amount_paid" name="amount_paid" value="{{ $amount }}" max="{{ $amount }}" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="notes" class="form-label">Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti tabler-credit-card me-1"></i> Process Payment
        </button>
    </div>
</form>
