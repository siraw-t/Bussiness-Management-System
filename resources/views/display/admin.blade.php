{{-- @extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h2">Admin Users</h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                    <tr>
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->full_name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->role }}</td>
                        <td>{{ $admin->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection --}}


@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h2 text-center mb-10">Admin Users</h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr class="table-row">
                        <td>{{ $admin->user_id }}</td>
                        <td>{{ $admin->full_name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->role }}</td>
                        <td>{{ $admin->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No admin users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-responsive {
        margin: 20px auto; /* Margin around the table */
        padding: 20px; /* Padding inside the table container */
        border: 1px solid #ddd; /* Add border to the container */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        background-color: #f9f9f9; /* Light background color */
    }

    .table {
        margin-bottom: 0; /* Remove default margin */
    }

    .table-row {
        transition: background-color 0.3s ease; /* Animation for row hover */
    }

    .table-row:hover {
        background-color: #f1f1f1; /* Change background on hover */
        cursor: pointer; /* Pointer cursor on hover */
    }

    th, td {
        padding: 15px; /* Padding inside table cells */
        text-align: left; /* Align text to the left */
    }

    th {
        background-color: #343a40; /* Dark background for headers */
        color: #fff; /* White text for headers */
    }

    .mb-4 {
        margin-bottom: 1.5rem; /* Margin bottom for the heading */
    }
</style>
@endsection