<div>
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Users</h5>
            <div class="d-flex align-items-center">
                <input type="text" wire:model.live="search" class="form-control me-2" placeholder="Search User...">
                <a href="{{ route('app.users.create') }}" class="btn btn-primary text-nowrap">
                    <i class="ti tabler-plus me-0 me-sm-1"></i>
                    <span class="d-none d-sm-inline-block">Add New User</span>
                </a>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>S/N</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ substr($user->firstname, 0, 1) }}{{ substr($user->lastname, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $user->FullName() }}</span>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @foreach($user->roles as $role)
                            <span class="badge bg-label-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('app.users.edit', $user->id) }}" class="text-body"><i class="ti tabler-edit ti-sm me-2"></i></a>
                                <button type="button" wire:click="confirmDelete({{ $user->id }})" class="text-body border-0 bg-transparent p-0"><i class="ti tabler-trash ti-sm mx-2"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
    </div>

    @script
    <script>
        $wire.on('show-delete-confirmation', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('deleteConfirmed');
                }
            })
        })
    </script>
    @endscript
</div>
