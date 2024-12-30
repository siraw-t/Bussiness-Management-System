@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Edit Payment Request</h1>

    <form action="{{ route('payment-requests.update', $paymentRequest) }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="amount">Amount</label>
            <input type="number" name="amount" class="form-control" value="{{ old('amount', $paymentRequest->amount) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $paymentRequest->description) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $paymentRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $paymentRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $paymentRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3">{{ old('comment', $paymentRequest->comment) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="approved_by">Approved By (User ID)</label>
            <input type="number" name="approved_by" class="form-control" value="{{ old('approved_by', $paymentRequest->approved_by) }}" placeholder="Optional User ID">
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100">
            <i class="bi bi-check-lg"></i> Update
        </button>
        <a href="{{ route('payment-requests.index') }}" class="btn btn-secondary btn-lg w-100 mt-2">
            <i class="bi bi-x-lg"></i> Cancel
        </a>
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