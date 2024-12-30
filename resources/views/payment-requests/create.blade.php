@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Payment Requests</h1>

    @if(session('success'))
        <div class="alert alert-success fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="text-center mb-4">
        <a href="{{ route('payment-requests.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-lg"></i> Create New Payment Request
        </a>
    </div>

    <form action="{{ route('payment-requests.store') }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
        @csrf

        <div class="form-group mb-3">
            <label for="amount">Amount</label>
            <input type="number" name="amount" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" selected>Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100">
            <i class="bi bi-check-lg"></i> Submit
        </button>
    </form>
</div>

<!-- Optional: Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<!-- Optional: Add custom CSS for additional effects -->
<style>
    .form-control:focus {
        border-color: #28a745; /* Green border on focus */
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); /* Green shadow on focus */
    }

    .btn-success {
        transition: background-color 0.3s, transform 0.2s; /* Smooth transition for button */
    }

    .btn-success:hover {
        background-color: #218838; /* Darker green on hover */
        transform: scale(1.05); /* Slightly enlarge on hover */
    }

    .bg-light {
        background-color: #f8f9fa !important; /* Light background for the form */
    }
</style>
@endsection