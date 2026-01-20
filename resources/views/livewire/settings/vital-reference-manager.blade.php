<div>
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Vital Reference Ranges</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="input-group input-group-merge" style="width: 200px;">
                    <span class="input-group-text"><i class="ti tabler-search"></i></span>
                    <input type="text" class="form-control" wire:model.live="search" placeholder="Search...">
                </div>
                <div class="dropdown">
                    <button class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti tabler-dots-vertical icon-md"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0)" wire:click="create">
                            <i class="ti tabler-plus me-1"></i> Add Reference
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="location.reload()">
                            <i class="ti tabler-refresh me-1"></i> Refresh
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Reference ranges List -->
            <div class="table-responsive">
                <table class="table table-hover border-top text-nowrap">
                    <thead>
                        <tr>
                            <th>Vital</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Unit</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($references as $ref)
                            <tr>
                                <td class="fw-medium text-heading">{{ $ref->name }}</td>
                                <td>{{ $ref->min_value ?? 'N/A' }}</td>
                                <td>{{ $ref->max_value ?? 'N/A' }}</td>
                                <td><span class="badge bg-label-info">{{ $ref->unit ?? 'N/A' }}</span></td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button wire:click="edit({{ $ref->id }})" class="btn btn-sm btn-icon btn-label-primary">
                                            <i class="ti tabler-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $ref->id }})" class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="modal" data-bs-target="#deleteRefModal">
                                            <i class="ti tabler-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No reference ranges found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $references->links() }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div wire:ignore.self class="modal fade" id="refModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Add' }} Reference Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="cancelEdit"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Vital Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   wire:model="name" placeholder="e.g. Temperature">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Min Value</label>
                                <input type="text" class="form-control @error('min_value') is-invalid @enderror" 
                                       wire:model="min_value" placeholder="e.g. 36.5">
                                @error('min_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max Value</label>
                                <input type="text" class="form-control @error('max_value') is-invalid @enderror" 
                                       wire:model="max_value" placeholder="e.g. 37.5">
                                @error('max_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                   wire:model="unit" placeholder="e.g. °C, mmHg, bpm">
                            @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" wire:click="cancelEdit">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti tabler-{{ $editingId ? 'check' : 'plus' }} me-1"></i>
                            {{ $editingId ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="deleteRefModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Reference Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this reference range? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="delete" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const refModal = new bootstrap.Modal(document.getElementById('refModal'));
            
            Livewire.on('show-ref-modal', () => {
                refModal.show();
            });
            
            Livewire.on('hide-ref-modal', () => {
                refModal.hide();
            });
        });
    </script>
</div>
