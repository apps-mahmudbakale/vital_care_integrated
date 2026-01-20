<div>
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Radiology Tests & Templates</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="input-group input-group-merge" style="width: 250px;">
                    <span class="input-group-text"><i class="ti tabler-search"></i></span>
                    <input type="text" class="form-control" wire:model.live="search" placeholder="Search tests...">
                </div>
                <div class="dropdown">
                    <button class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti tabler-dots-vertical icon-md"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0)" wire:click="create">
                            <i class="ti tabler-plus me-1"></i> Add Radiology Test
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
                <table class="table table-hover border-top">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Category</th>
                            <th>Template Preview</th>
                            <th>Price</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $test)
                            <tr>
                                <td class="fw-medium text-heading">{{ $test->name }}</td>
                                <td><span class="badge bg-label-info">{{ $test->category->name }}</span></td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($test->template), 30) }}
                                    </small>
                                </td>
                                <td>{{ number_format($test->price, 2) }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button wire:click="edit({{ $test->id }})" class="btn btn-sm btn-icon btn-label-primary">
                                            <i class="ti tabler-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $test->id }})" class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="modal" data-bs-target="#deleteRadiologyTestModal">
                                            <i class="ti tabler-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No radiology tests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tests->links() }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div wire:ignore.self class="modal fade" id="radiologyTestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Add' }} Radiology Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select @error('radiology_category_id') is-invalid @enderror" wire:model="radiology_category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('radiology_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Test Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       wire:model="name" placeholder="e.g. Chest X-Ray (AP/Lateral)">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ config('app.currency', '₦') }}</span>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           wire:model="price" placeholder="0.00">
                                </div>
                                @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Description (Optional)</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                       wire:model="description">
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Report Template (Quill Editor)</label>
                            <div wire:ignore>
                                <div id="radiology-quill-editor" style="height: 300px;"></div>
                            </div>
                            @error('template') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
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
    <div wire:ignore.self class="modal fade" id="deleteRadiologyTestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Radiology Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this radiology test?</p>
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
            const radiologyTestModal = new bootstrap.Modal(document.getElementById('radiologyTestModal'));
            
            // Quill Initialization
            const quill = new Quill('#radiology-quill-editor', {
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Compose radiology report template...',
                theme: 'snow'
            });

            // Update Livewire on Quill change
            quill.on('text-change', function() {
                @this.set('template', quill.root.innerHTML);
            });

            Livewire.on('show-test-modal', (event) => {
                const template = @this.get('template') || '';
                quill.root.innerHTML = template;
                radiologyTestModal.show();
            });

            Livewire.on('hide-test-modal', () => radiologyTestModal.hide());
        });
    </script>
</div>

@push('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
<style>
    .ql-container {
        border-bottom-left-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
    .ql-toolbar {
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endpush
