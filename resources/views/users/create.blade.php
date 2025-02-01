@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Create User</h2>
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white text-center">
                <h5>Create a New User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback">Please enter a username.</div>
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                        <div class="invalid-feedback">Please enter your full name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">Please enter a password.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            required>
                        <div class="invalid-feedback">Please confirm your password.</div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="employee">Employee</option>
                            <option value="finance">Finance</option>
                            <option value="manager">Manager</option>
                            <option value="storekeeper">Storekeeper</option>
                        </select>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company ID</label>
                        <input type="number" class="form-control" id="company_id" name="company_id"
                            value="{{ Auth::user()->company_id }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Example of Bootstrap's custom validation
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
        })();
    </script>
@endsection
