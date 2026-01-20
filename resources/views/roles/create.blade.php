@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Roles /</span> Create Role</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Add New Role</h5>
            <div class="card-body">
                <form id="addRoleForm" class="row g-3" action="{{ route('app.roles.store') }}" method="POST">
                    @csrf
                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalRoleName">Role Name</label>
                        <input type="text" id="modalRoleName" name="name" class="form-control" placeholder="Enter a role name" tabindex="-1" required />
                    </div>

                    <div class="col-12">
                        <h5>Role Permissions</h5>
                        
                        @php
                            $groupedPermissions = $permissions->groupBy(function($permission) {
                                $parts = explode('-', $permission->name);
                                return count($parts) > 1 ? ucfirst(end($parts)) : 'Others';
                            });
                        @endphp

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="selectAll" />
                                    <label class="form-check-label" for="selectAll">Select All Permissions</label>
                                </div>
                            </div>
                            
                            @foreach($groupedPermissions as $group => $perms)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-header border-bottom py-2">
                                        <h6 class="mb-0 text-capitalize">{{ $group }}</h6>
                                    </div>
                                    <div class="card-body pt-3">
                                        @foreach($perms as $permission)
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" />
                                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                {{ ucfirst(explode('-', $permission->name)[0]) }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <a href="{{ route('app.roles.index') }}" class="btn btn-label-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(chk => {
                chk.checked = selectAll.checked;
            });
        });
    });
</script>
@endpush
@endsection
