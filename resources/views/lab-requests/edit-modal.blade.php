<div class="modal-header border-bottom">
    <h5 class="modal-title">Edit Lab Request</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('app.lab-requests.update', $labRequest->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Patient</label>
                <input type="text" class="form-control" value="{{ $labRequest->patient->user->FullName() }}" readonly disabled>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select" required>
                    <option value="Regular" {{ $labRequest->priority == 'Regular' ? 'selected' : '' }}>Regular</option>
                    <option value="Urgent" {{ $labRequest->priority == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="Emergency" {{ $labRequest->priority == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Pending" {{ $labRequest->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Processing" {{ $labRequest->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                    <option value="Completed" {{ $labRequest->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ $labRequest->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Request Note</label>
                <textarea name="request_note" class="form-control" rows="3">{{ $labRequest->request_note }}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer border-top">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update Request</button>
    </div>
</form>
