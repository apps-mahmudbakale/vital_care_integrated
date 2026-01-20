<div class="card h-100">
    <div class="card-header border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Generic Drugs</h5>
            <button wire:click="cancelEdit" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#genericModal">
                <i class="ti tabler-plus me-1"></i> Add Generic
            </button>
        </div>
    </div>
    <div class="card-body pt-4">
        <div class="mb-3">
            <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                placeholder="Search generics...">
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($generics as $generic)
                        <tr>
                            <td>{{ $generic->name }}</td>
                            <td>
                                @if($generic->category)
                                    <span class="badge bg-label-primary">{{ $generic->category->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button wire:click="edit({{ $generic->id }})" class="btn btn-label-info"
                                        data-bs-toggle="modal" data-bs-target="#genericModal">
                                        <i class="ti tabler-pencil"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $generic->id }})" class="btn btn-label-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteGenericModal">
                                        <i class="ti tabler-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">
                                No generic drugs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $generics->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div wire:ignore.self class="modal fade" id="genericModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Generic' : 'Add New Generic' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cancelEdit"
                        aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select wire:model="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Generic Name <span class="text-danger">*</span></label>
                            <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                placeholder="e.g. Paracetamol">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            wire:click="cancelEdit">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">
                            {{ $editingId ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="deleteGenericModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Generic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cancelEdit"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this generic drug? This action cannot be undone.</p>
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
