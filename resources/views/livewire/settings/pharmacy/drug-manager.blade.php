<div>
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Drug Database</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="input-group input-group-merge" style="width: 250px;">
                    <span class="input-group-text"><i class="ti tabler-search"></i></span>
                    <input type="text" class="form-control" wire:model.live="search" placeholder="Search drug/generic...">
                </div>
                <div class="dropdown">
                    <button class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti tabler-dots-vertical icon-md"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0)" wire:click="create">
                            <i class="ti tabler-plus me-1"></i> Add Drug
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
                            <th>Drug Name</th>
                            <th>Generic Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drugs as $drug)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium text-heading">{{ $drug->name }}</span>
                                            <small class="text-muted">{{ $drug->strength }} {{ $drug->unit }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $drug->generic_name ?? 'N/A' }}</td>
                                <td><span class="badge bg-label-info">{{ $drug->category->name }}</span></td>
                                <td>
                                    <span class="badge {{ $drug->stock_quantity <= $drug->reorder_level ? 'bg-label-danger' : 'bg-label-success' }}">
                                        {{ $drug->stock_quantity }}
                                    </span>
                                </td>
                                <td>{{ number_format($drug->selling_price, 2) }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button wire:click="edit({{ $drug->id }})" class="btn btn-sm btn-icon btn-label-primary">
                                            <i class="ti tabler-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $drug->id }})" class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="modal" data-bs-target="#deleteDrugModal">
                                            <i class="ti tabler-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No drugs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $drugs->links() }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div wire:ignore.self class="modal fade" id="drugModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Add' }} Drug Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="cancelEdit"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Category and Generic -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Drug Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" wire:model="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Generic Name</label>
                                <select class="form-select @error('generic_id') is-invalid @enderror" wire:model="generic_id">
                                    <option value="">Select Generic</option>
                                    @foreach($generics as $generic)
                                        <option value="{{ $generic->id }}">{{ $generic->name }}</option>
                                    @endforeach
                                </select>
                                @error('generic_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Name and Weight -->
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Drug Name (Brand) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       wire:model="name" placeholder="e.g. Panadol">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Weight/Strength</label>
                                <input type="text" class="form-control @error('weight') is-invalid @enderror" 
                                       wire:model="weight" placeholder="e.g. 500mg">
                                @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Price and Threshold -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ config('app.currency', '₦') }}</span>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           wire:model="price" placeholder="0.00">
                                </div>
                                @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('threshold') is-invalid @enderror" 
                                       wire:model="threshold" placeholder="10">
                                @error('threshold') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" wire:model="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
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
    <div wire:ignore.self class="modal fade" id="deleteDrugModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Drug</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this drug from the database?</p>
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
            const drugModal = new bootstrap.Modal(document.getElementById('drugModal'));
            Livewire.on('show-drug-modal', () => drugModal.show());
            Livewire.on('hide-drug-modal', () => drugModal.hide());
        });
    </script>
</div>
