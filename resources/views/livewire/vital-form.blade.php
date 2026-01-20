<div>
    <div class="card shadow-none border-0">
        <div class="card-body p-0">
            <form wire:submit.prevent="save">
                @foreach($readings as $index => $reading)
                <div class="row g-3 mb-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Vital Parameter</label>
                        <select class="form-select" wire:model="readings.{{ $index }}.vital_reference_id">
                            <option value="">Select Vital...</option>
                            @foreach($references as $ref)
                                <option value="{{ $ref->id }}">{{ $ref->name }} ({{ $ref->unit }})</option>
                            @endforeach
                        </select>
                        @error("readings.$index.vital_reference_id") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Value</label>
                        <input type="number" step="any" class="form-control" wire:model="readings.{{ $index }}.value" placeholder="Enter value">
                        @error("readings.$index.value") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-2">
                        @if(count($readings) > 1)
                            <button type="button" class="btn btn-danger btn-icon" wire:click="removeReading({{ $index }})">
                                <i class="ti tabler-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="mb-3">
                    <button type="button" class="btn btn-label-primary btn-sm" wire:click="addReading">
                        <i class="ti tabler-plus me-1"></i> Add Another Parameter
                    </button>
                </div>

                <div class="col-md-12 d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-label-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Vitals</button>
                </div>
            </form>
        </div>
    </div>
</div>
