@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Company</h1>

    <form action="{{ route('companies.update', $company->company_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company->company_name }}" required>
        </div>

        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" class="form-control" id="country" name="country" value="{{ $company->country }}" required>
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ $company->city }}" required>
        </div>

        <div class="mb-3">
            <label for="contact_email" class="form-label">Contact Email</label>
            <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ $company->contact_email }}" required>
        </div>

        <div class="mb-3">
            <label for="contact_phone" class="form-label">Contact Phone</label>
            <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ $company->contact_phone }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Company</button>
    </form>
</div>
@endsection

