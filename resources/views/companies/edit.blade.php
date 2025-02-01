@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Company</h1>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="{{ route('companies.update', $company->company_id) }}" method="POST"
                    class="p-4 border rounded shadow-sm bg-light" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name"
                            value="{{ $company->company_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ $company->country }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ $company->city }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email"
                            value="{{ $company->contact_email }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                            value="{{ $company->contact_phone }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Company Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        @if ($company->logo)
                            <small>Current Logo: <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo"
                                    style="width: 100px; height: auto;"></small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Company</button>
                </form>
            </div>
        </div>
    </div>
@endsection
