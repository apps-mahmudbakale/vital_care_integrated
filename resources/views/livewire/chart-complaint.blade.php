<div>
    <div class="card shadow-none border-0">
        <div class="card-body p-0">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Presenting Complaint</label>
                        <textarea class="form-control" wire:model="complaint" rows="5" placeholder="Enter patient presenting complaint..."></textarea>
                        @error('complaint') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-12 d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-label-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Complaint</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
