@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Purchase Requests</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="text-center mb-4">
        <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-lg"></i> Create New Purchase Request
        </a>
    </div>

    <table class="table table-striped table-hover table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Estimated Cost</th>
                <th>Status</th>
                <th>Comment</th>
                <th>Approved By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->item_name }}</td>
                    <td>{{ $request->quantity }}</td>
                    <td>${{ number_format($request->estimated_cost, 2) }}</td>
                    <td>
                        <span class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>{{ $request->comment ?? 'N/A' }}</td>
                    <td>{{ $request->approved_by ? \App\Models\User::find($request->approved_by)->full_name : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('purchase-requests.edit', $request) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('purchase-requests.destroy', $request) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this request?');">
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
<style>
    .table-hover tr:hover {
        background-color: #f8f9fa; /* Light grey on hover */
        cursor: pointer; /* Pointer cursor on hover */
    }
</style>
@endsection