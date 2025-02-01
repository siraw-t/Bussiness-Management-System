{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Companies</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('companies.create') }}" class="btn btn-primary mb-3">Add New Company</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Company Name</th>
                <th>Country</th>
                <th>City</th>
                <th>Contact Email</th>
                <th>Contact Phone</th>
                <th>Company Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td>{{ $company->company_name }}</td>
                    <td>{{ $company->country }}</td>
                    <td>{{ $company->city }}</td>
                    <td>{{ $company->contact_email }}</td>
                    <td>{{ $company->contact_phone }}</td>
                    <td>{{ optional($company->admin)->full_name }}</td>
                    <td>
                        <a href="{{ route('companies.edit', ['company' => $company->company_id]) }}" class="btn btn-warning btn-sm">Edit</a>

            <form action="{{ route('companies.destroy', ['company' => $company->company_id]) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this company?');">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 
 --}}


@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Companies</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="text-right mb-3">
            <a href="{{ route('companies.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Company
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Company Name</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Contact Email</th>
                        <th>Contact Phone</th>
                        <th>Company Admin</th>
                        <th>Logo</th> <!-- New Logo Column -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr class="table-light">
                            <td>{{ $company->company_name }}</td>
                            <td>{{ $company->country }}</td>
                            <td>{{ $company->city }}</td>
                            <td>{{ $company->contact_email }}</td>
                            <td>{{ $company->contact_phone }}</td>
                            <td>{{ optional($company->admin)->full_name }}</td>
                            <td>
                                @if ($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo"
                                        style="width: 50px; height: auto;">
                                @else
                                    No Logo
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('companies.edit', ['company' => $company->company_id]) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('companies.destroy', ['company' => $company->company_id]) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this company?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            /* Light gray on row hover */
            transition: background-color 0.3s ease;
            /* Smooth transition */
        }

        /* Additional styles for a more attractive UI */
        h1 {
            font-size: 2.5rem;
            color: #343a40;
            /* Dark gray */
        }

        .alert {
            font-weight: bold;
        }

        .table {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .table th {
            background-color: #007bff;
            /* Blue */
            color: white;
        }

        .table td {
            vertical-align: middle;
            /* Center align table cell content */
        }

        .table-light {
            background-color: #ffffff;
            /* White for rows */
        }

        .table-bordered {
            border: 2px solid #007bff;
            /* Blue border */
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #007bff;
            /* Blue border for cells */
        }
    </style>

    <!-- Include Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
