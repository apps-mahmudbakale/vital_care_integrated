<div>
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Consultation Templates</h5>
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
                            <i class="ti tabler-plus me-1"></i> Add Template
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
                            <th>Template Name</th>
                            <th>Specialty</th>
                            <th>Preview</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td class="fw-medium text-heading">{{ $template->name }}</td>
                                <td>{{ $template->specialty->name }}</td>
                                <td class="small text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($template->content), 30) }}</td>
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
                                <td colspan="4" class="text-center text-muted py-4">No templates found.</td>
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
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Add' }} Consultation Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="cancelEdit"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Specialty</label>
                            <select class="form-select @error('specialty_id') is-invalid @enderror" wire:model="specialty_id">
                                <option value="">Select Specialty</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                @endforeach
                            </select>
                            @error('specialty_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Template Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   wire:model="name" placeholder="e.g. Cardiology Assessment">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Template Content</label>
                            <div wire:ignore>
                                <div id="quill-editor" style="height: 300px;"></div>
                            </div>
                            @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
            
            // Quill Initialization
            const quill = new Quill('#quill-editor', {
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['clean']
                    ]
                },
                placeholder: 'Compose your consultation template...',
                theme: 'snow'
            });

            // Update Livewire on Quill change
            quill.on('text-change', function() {
                @this.set('content', quill.root.innerHTML);
            });

            Livewire.on('show-template-modal', (event) => {
                // If we are editing, set the initial content
                const content = @this.get('content') || '';
                quill.root.innerHTML = content;
                templateModal.show();
            });

            Livewire.on('hide-template-modal', () => templateModal.hide());
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
