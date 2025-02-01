@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Payment Requests</h1>

        @if (session('success'))
            <div class="alert alert-success fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-4">
            <a href="{{ route('payment-requests.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-lg"></i> Create New Payment Request
            </a>
        </div>

        <table class="table table-striped table-hover table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Full Name</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Comment</th>
                    <th>Approved By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr class="align-middle" style="transition: background-color 0.3s;">
                        <td>{{ Auth::user()->full_name }}</td>
                        <td>${{ number_format($request->amount, 2) }}</td>
                        <td>{{ $request->description }}</td>
                        <td>
                            <span
                                class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>{{ $request->comment ?? 'N/A' }}</td>
                        <td>
                            @if ($request->approved_by)
                                {{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('payment-requests.edit', $request) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('payment-requests.destroy', $request) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this request?');">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Optional: Add Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Optional: Add custom CSS for additional effects -->
    <style>
        .table-hover tr:hover {
            background-color: #f8f9fa;
            /* Light grey on hover */
            cursor: pointer;
            /* Pointer cursor on hover */
        }
    </style>
@endsection
