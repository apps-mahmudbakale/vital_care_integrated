@extends('layouts.app')

@section('title', 'Edit User')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@push('scripts')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#roles').select2();
    });
</script>
@endpush

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Users /</span> Edit User</h4>

<div class="card">
    <h5 class="card-header">User Details</h5>
    <div class="card-body">
        <form id="editUserForm" class="row g-3" action="{{ route('app.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="col-12 col-md-6">
                <label class="form-label" for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" value="{{ $user->firstname }}" class="form-control" placeholder="John" required />
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="{{ $user->lastname }}" class="form-control" placeholder="Doe" required />
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="john.doe@example.com" required />
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label" for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ $user->phone }}" class="form-control" placeholder="202 555 0111" />
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password" />
            </div>
            
            <div class="col-12 col-md-6">
                <label class="form-label" for="roles">Role</label>
                <select id="roles" name="roles[]" class="form-select" multiple aria-label="Default select example" required>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <div class="form-text">Hold Ctrl/Cmd to select multiple roles.</div>
            </div>

            <div class="col-12 text-center mt-4">
                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                <a href="{{ route('app.users.index') }}" class="btn btn-label-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
