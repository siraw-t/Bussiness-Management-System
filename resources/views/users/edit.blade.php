{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $user->user_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $user->full_name }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password (leave blank to keep current password)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee</option>
                <option value="finance" {{ $user->role == 'finance' ? 'selected' : '' }}>Finance</option>
                <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="storekeeper" {{ $user->role == 'storekeeper' ? 'selected' : '' }}>Storekeeper</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="company_id" class="form-label">Company ID</label>
            <input type="number" class="form-control" id="company_id" name="company_id" value="{{ $user->company_id }}" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
@endsection --}}

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit User</h2>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="{{ route('users.update', $user->user_id) }}" method="POST"
                    class="p-4 border rounded shadow-sm bg-light" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ $user->username }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            value="{{ $user->full_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}"
                            required>
                    </div>

                    @if (Auth::user()->role === 'admin' && Auth::user()->user_id === $user->user_id)
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password (leave blank to keep current
                                password)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>
                    @else
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (not editable)</label>
                            <input type="text" class="form-control" id="password" name="password" value="Not editable"
                                readonly>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="{{ $user->role }}" selected>{{ ucfirst($user->role) }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company ID</label>
                        <input type="number" class="form-control" id="company_id" name="company_id"
                            value="{{ $user->company_id }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update User</button>
                </form>
            </div>
        </div>
    </div>
@endsection
