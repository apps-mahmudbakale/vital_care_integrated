<div class="card h-100">
    <div class="card-header border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Drug Batches</h5>
            <button wire:click="cancelEdit" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#batchModal">
                <i class="ti tabler-plus me-1"></i> Add Batch
            </button>
        </div>
    </div>
    <div class="card-body pt-4">
        <div class="mb-3">
            <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                placeholder="Search batches or drugs...">
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Store</th>
                        <th>Batch #</th>
                        <th>Drug Name</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                        <tr>
                            <td>{{ $batch->store->name ?? 'N/A' }}</td>
                            <td>{{ $batch->batch_number }}</td>
                            <td>{{ $batch->drug->name ?? 'Unknown Drug' }}</td>
                            <td>{{ $batch->quantity }}</td>
                            <td>{{ $batch->expiry_date }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button wire:click="edit({{ $batch->id }})" class="btn btn-label-info"
                                        data-bs-toggle="modal" data-bs-target="#batchModal">
                                        <i class="ti tabler-pencil"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $batch->id }})" class="btn btn-label-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteBatchModal">
                                        <i class="ti tabler-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                No batches found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $batches->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div wire:ignore.self class="modal fade" id="batchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Batch Entry' : 'Add New Batches' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cancelEdit"
                        aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <!-- Header Fields -->
                        <div class="row g-3 mb-4 bg-light p-3 rounded">
                            <div class="col-md-6">
                                <label class="form-label">Store <span class="text-danger">*</span></label>
                                <select wire:model="store_id" class="form-select @error('store_id') is-invalid @enderror">
                                    <option value="">Select Store</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                                @error('store_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Batch Number <span class="text-danger">*</span></label>
                                <input wire:model="batch_number" type="text" class="form-control @error('batch_number') is-invalid @enderror"
                                    placeholder="Batch-001">
                                @error('batch_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Repeater Items -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Drugs in Batch</label>
                            @foreach($items as $index => $item)
                                <div class="row g-2 align-items-end mb-2">
                                    <div class="col-md-5">
                                        <label class="form-label small">Drug</label>
                                        <select wire:model="items.{{ $index }}.drug_id" class="form-select form-select-sm @error('items.'.$index.'.drug_id') is-invalid @enderror">
                                            <option value="">Select Drug</option>
                                            @foreach($drugs as $drug)
                                                <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">Quantity</label>
                                        <input wire:model="items.{{ $index }}.quantity" type="number" min="0" class="form-control form-control-sm @error('items.'.$index.'.quantity') is-invalid @enderror" placeholder="Qty">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">Expiry</label>
                                        <input wire:model="items.{{ $index }}.expiry_date" type="date" class="form-control form-control-sm @error('items.'.$index.'.expiry_date') is-invalid @enderror">
                                    </div>
                                    <div class="col-md-1">
                                        @if(!$editingId && count($items) > 1)
                                            <button type="button" class="btn btn-label-danger btn-sm w-100" wire:click="removeItem({{ $index }})">
                                                <i class="ti tabler-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            
                            @if(!$editingId)
                                <button type="button" class="btn btn-sm btn-label-primary mt-2" wire:click="addItem">
                                    <i class="ti tabler-plus me-1"></i> Add Another Drug
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            wire:click="cancelEdit">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">
                            {{ $editingId ? 'Update' : 'Save Batches' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="deleteBatchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Batch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cancelEdit"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this batch entry? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                        wire:click="cancelEdit">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete"
                        data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
