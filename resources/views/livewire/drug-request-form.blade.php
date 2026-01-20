<div>
    <form wire:submit.prevent="save">
        <div class="modal-header">
            <h5 class="modal-title">New Pharmacy Request - {{ $patient->user->firstname }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="selected_drug_id">Select Drug</label>
                    <select wire:model="selected_drug_id" id="selected_drug_id" class="form-select @error('selected_drug_id') is-invalid @enderror">
                        <option value="">-- Choose Drug --</option>
                        @foreach($drugs as $drug)
                            <option value="{{ $drug->id }}">{{ $drug->name }} (Stock: {{ $drug->stock_quantity }})</option>
                        @endforeach
                    </select>
                    @error('selected_drug_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="quantity">Quantity</label>
                    <input type="number" wire:model="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1">
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="dosage">Dosage (e.g. 500mg)</label>
                    <input type="text" wire:model="dosage" id="dosage" class="form-control" placeholder="1x2 daily">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label" for="instruction">Instruction</label>
                    <textarea wire:model="instruction" id="instruction" class="form-control" rows="2" placeholder="Before meal, etc."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Submit Request</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </form>
</div>
