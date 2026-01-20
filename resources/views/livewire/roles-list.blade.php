<div>
    <h4 class="py-3 mb-4">Roles List</h4>

    <p class="mb-4">
        A role provided access to predefined menus and features so that depending on <br>
        assigned role an administrator can have access to what user needs.
    </p>

    <!-- Role Cards -->
    <div class="row g-6 mb-4">
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-search"></i></span>
                <input type="text" wire:model.live="search" class="form-control" placeholder="Search Role...">
            </div>
        </div>
    </div>

    <div class="row g-6">
        @foreach($roles as $role)
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-normal mb-0 text-body">Total {{ $role->users()->count() }} users</h6>
                        <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            @foreach($role->users()->limit(4)->get() as $user)
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar avatar-sm pull-up">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    {{ substr($user->firstname, 0, 1) }}{{ substr($user->lastname, 0, 1) }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                            <div class="role-heading">
                                <h5 class="mb-1">{{ $role->name }}</h5>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('app.roles.edit', $role->id) }}" class="role-edit-modal me-2">
                                        <span>Edit Role</span>
                                    </a>
                                    <a href="javascript:;" wire:click="confirmDelete({{ $role->id }})" class="text-danger">
                                        <span>Delete Role</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
                <div class="row h-100">
                    <div class="col-sm-5">
                        <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4">
                            <img src="{{ asset('assets/img/illustrations/add-new-roles.png') }}" class="img-fluid" alt="add-new-roles" width="83">
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body text-sm-end text-center ps-sm-0">
                            <a href="{{ route('app.roles.create') }}" class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">Add New Role</a>
                            <p class="mb-0">Add role, if it does not exist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $roles->links() }}
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
    <!--/ Role Cards -->
</div>
