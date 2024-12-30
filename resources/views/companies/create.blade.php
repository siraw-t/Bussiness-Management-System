{{-- {{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Company</h1>

    <form action="{{ route('companies.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="company_name" name="company_name" required>
        </div>
        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" class="form-control" id="country" name="country" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <div class="mb-3">
            <label for="contact_email" class="form-label">Contact Email</label>
            <input type="email" class="form-control" id="contact_email" name="contact_email" required>
        </div>
        <div class="mb-3">
            <label for="contact_phone" class="form-label">Contact Phone</label>
            <input type="text" class="form-control" id="contact_phone" name="contact_phone" required>
        </div>

        <h3>Company Admin Details</h3>
        <div class="mb-3">
            <label for="admin_username" class="form-label">Admin Username</label>
            <input type="text" class="form-control" id="admin_username" name="admin_username" required>
        </div>
        <div class="mb-3">
            <label for="admin_full_name" class="form-label">Admin Full Name</label>
            <input type="text" class="form-control" id="admin_full_name" name="admin_full_name" required>
        </div>
        <div class="mb-3">
            <label for="admin_email" class="form-label">Admin Email</label>
            <input type="email" class="form-control" id="admin_email" name="admin_email" required>
        </div>
        <div class="mb-3">
            <label for="admin_password" class="form-label">Admin Password</label>
            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
        </div>
        <div class="mb-3">
            <label for="admin_password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="admin_password_confirmation" name="admin_password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Company and Admin</button>
    </form>
</div>
@endsection 
 --}}

 
 
 
 @extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Create Company and Admin</h1>
    <form action="{{ route('companies.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Company Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" required>
                            <div class="invalid-feedback">Please enter a company name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                            <div class="invalid-feedback">Please enter the country.</div>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                            <div class="invalid-feedback">Please enter the city.</div>
                        </div>
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" required>
                            <div class="invalid-feedback">Please enter a contact phone number.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Admin Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="admin_username" class="form-label">Admin Username</label>
                            <input type="text" class="form-control" id="admin_username" name="admin_username" required>
                            <div class="invalid-feedback">Please enter an admin username.</div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_full_name" class="form-label">Admin Full Name</label>
                            <input type="text" class="form-control" id="admin_full_name" name="admin_full_name" required>
                            <div class="invalid-feedback">Please enter the admin's full name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Admin Email</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                            <div class="invalid-feedback">Please enter a valid admin email address.</div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_password" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                            <div class="invalid-feedback">Please enter a password.</div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="admin_password_confirmation" name="admin_password_confirmation" required>
                            <div class="invalid-feedback">Please confirm your password.</div>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                <option value="finance">Finance</option>
                                <option value="storekeeper">Storekeeper</option>
                            </select>
                            <div class="invalid-feedback">Please select a role.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4"> <!-- Center the button -->
            <button type="submit" class="btn btn-primary btn-md" style="max-width: 250px;">Create Company and Admin</button>
        </div>
    </form>
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