<div>
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Lab Templates</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="input-group input-group-merge" style="width: 200px;">
                    <span class="input-group-text"><i class="ti tabler-search"></i></span>
                    <input type="text" class="form-control" wire:model.live="search" placeholder="Search...">
                </div>
                <button class="btn btn-primary btn-icon" wire:click="create">
                    <i class="ti tabler-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover border-top">
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Parameters</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td class="fw-medium text-heading">{{ $template->name }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $template->items->count() }} parameters
                                    </small>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button wire:click="edit({{ $template->id }})" class="btn btn-sm btn-icon btn-label-primary">
                                            <i class="ti tabler-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $template->id }})" class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="modal" data-bs-target="#deleteTemplateModal">
                                            <i class="ti tabler-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No templates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $templates->links() }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div wire:ignore.self class="modal fade" id="templateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Add' }} Lab Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label">Template Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   wire:model="name" placeholder="e.g. FBC Template, Lipid Profile">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Parameters</h6>
                            <button type="button" class="btn btn-sm btn-label-primary" wire:click="addItem">
                                <i class="ti tabler-plus me-1"></i> Add Parameter
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm border">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Parameter</th>
                                        <th>Reference Range</th>
                                        <th>Unit</th>
                                        <th style="width: 50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $index => $item)
                                        <tr>
                                            <td>
                                                <select class="form-select form-select-sm @error('items.'.$index.'.lab_parameter_id') is-invalid @enderror" 
                                                        wire:model="items.{{ $index }}.lab_parameter_id">
                                                    <option value="">Select...</option>
                                                    @foreach($parameters as $param)
                                                        <option value="{{ $param->id }}">{{ $param->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('items.'.$index.'.lab_parameter_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" 
                                                       wire:model="items.{{ $index }}.reference" placeholder="e.g. 4.0 - 10.0">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" 
                                                       wire:model="items.{{ $index }}.unit" placeholder="e.g. x10^9/L">
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-icon text-danger" wire:click="removeItem({{ $index }})">
                                                    <i class="ti tabler-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if(empty($items))
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No parameters added. Click "Add Parameter" above.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" wire:click="resetForm">Cancel</button>
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
    <div wire:ignore.self class="modal fade" id="deleteTemplateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this template?</p>
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
            const templateModal = new bootstrap.Modal(document.getElementById('templateModal'));
            Livewire.on('show-template-modal', () => templateModal.show());
            Livewire.on('hide-template-modal', () => templateModal.hide());
        });
    </script>
</div>
