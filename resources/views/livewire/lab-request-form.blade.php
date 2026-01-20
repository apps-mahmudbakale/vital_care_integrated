<div>
    <form wire:submit.prevent="save">
        <div id="lab-request-repeater">
            @foreach($rows as $index => $row)
            <div class="card mb-3 border border-label-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Test #{{ $index + 1 }}</h6>
                        @if(count($rows) > 1)
                        <button type="button" class="btn btn-sm btn-label-danger" wire:click="removeRow({{ $index }})">
                            <i class="ti tabler-trash icon-xs me-1"></i> Remove
                        </button>
                        @endif
                    </div>
                    
                    <div class="row g-3">
                        <!-- Lab Test Select2 -->
                        <div class="col-md-6" wire:ignore>
                            <label class="form-label">Select Lab Test <span class="text-danger">*</span></label>
                            <select class="form-select select2" 
                                data-index="{{ $index }}" 
                                id="test-select-{{ $index }}"
                                data-placeholder="Select a test">
                                <option value=""></option>
                                @foreach($labTests as $test)
                                <option value="{{ $test->id }}" {{ $row['test_id'] == $test->id ? 'selected' : '' }}>
                                    {{ $test->name }} (₦{{ number_format($test->price, 2) }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select wire:model="rows.{{ $index }}.priority" class="form-select">
                                <option value="Regular">Regular</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                        </div>

                        <!-- Request Note -->
                        <div class="col-12">
                            <label class="form-label">Request Note (Optional)</label>
                            <textarea wire:model="rows.{{ $index }}.request_note" class="form-control" rows="1" placeholder="Instructions..."></textarea>
                        </div>
                    </div>
                    @error("rows.$index.test_id") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <button type="button" class="btn btn-label-primary" wire:click="addRow">
                <i class="ti tabler-plus icon-xs me-1"></i> Add Another Test
            </button>
            <div class="text-end">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary ms-2" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm me-1"></span>
                    Submit Requests
                </button>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            initSelect2();

            // Re-init on Livewire update
            Livewire.on('rowAdded', () => {
                setTimeout(() => initSelect2(), 50);
            });
        });

        function initSelect2() {
            $('.select2').each(function() {
                if (!$(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2({
                        dropdownParent: $('#global-modal'),
                        width: '100%',
                        allowClear: true
                    }).on('change', function (e) {
                        let index = $(this).data('index');
                        let value = $(this).val();
                        @this.set(`rows.${index}.test_id`, value);
                    });
                }
            });
        }
    </script>
</div>
