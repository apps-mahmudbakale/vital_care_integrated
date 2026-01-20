<div>
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Clinics</h5>
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
                            <i class="ti tabler-plus me-1"></i> Add Clinic
                        </a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="location.reload()">
                            <i class="ti tabler-refresh me-1"></i> Refresh
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover border-top text-nowrap">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clinics as $clinic)
                            <tr>
                                <td class="fw-medium text-heading">{{ $clinic->name }}</td>
                                <td>{{ $clinic->location ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button wire:click="edit({{ $clinic->id }})" class="btn btn-sm btn-icon btn-label-primary">
                                            <i class="ti tabler-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $clinic->id }})" class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="modal" data-bs-target="#deleteClinicModal">
                                            <i class="ti tabler-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No clinics found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $clinics->links() }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div wire:ignore.self class="modal fade" id="clinicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Add' }} Clinic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="cancelEdit"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Clinic Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   wire:model="name" placeholder="e.g. General Practice Clinic">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   wire:model="location" placeholder="e.g. Wing A, Ground Floor">
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

    <!-- Delete Modal -->
    <div wire:ignore.self class="modal fade" id="deleteClinicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Clinic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure? All associated rooms will be deleted.</p>
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
            const clinicModal = new bootstrap.Modal(document.getElementById('clinicModal'));
            Livewire.on('show-clinic-modal', () => clinicModal.show());
            Livewire.on('hide-clinic-modal', () => clinicModal.hide());
        });
    </script>
</div>
