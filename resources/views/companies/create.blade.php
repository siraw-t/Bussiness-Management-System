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
<div class="container">
    <h1>Create Company and Admin</h1>
    <form action="{{ route('companies.store') }}" method="POST">
        @csrf

        <h3>Company Information</h3>
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

        <h3>Admin Information</h3>
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
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="employee">Employee</option>
                <option value="manager">Manager</option>
                <option value="finance">Finance</option>
                <option value="storekeeper">Storekeeper</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Company and Admin</button>
    </form>
</div>
@endsection