<div>
    <form wire:submit.prevent="save">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label" for="allergy_name">Allergy Name <span class="text-danger">*</span></label>
                <input wire:model="allergy_name" type="text" id="allergy_name" class="form-control @error('allergy_name') is-invalid @enderror" placeholder="e.g. Penicillin, Peanuts">
                @error('allergy_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label" for="reaction">Reaction</label>
                <input wire:model="reaction" type="text" id="reaction" class="form-control @error('reaction') is-invalid @enderror" placeholder="e.g. Skin rash, Swelling">
                @error('reaction') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Severity</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input wire:model="severity" class="form-check-input" type="radio" name="severity" id="severityMild" value="mild">
                        <label class="form-check-label" for="severityMild">Mild</label>
                    </div>
                    <div class="form-check">
                        <input wire:model="severity" class="form-check-input" type="radio" name="severity" id="severityModerate" value="moderate">
                        <label class="form-check-label" for="severityModerate">Moderate</label>
                    </div>
                    <div class="form-check">
                        <input wire:model="severity" class="form-check-input" type="radio" name="severity" id="severitySevere" value="severe">
                        <label class="form-check-label" for="severitySevere">Severe</label>
                    </div>
                </div>
                @error('severity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 text-center mt-4">
                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
        </div>
    </form>
</div>
