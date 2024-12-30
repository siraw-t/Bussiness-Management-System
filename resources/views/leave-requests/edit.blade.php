@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Edit Leave Request</h1>

    <form action="{{ route('leave-requests.update', $leaveRequest) }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ $leaveRequest->start_date }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ $leaveRequest->end_date }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $leaveRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $leaveRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $leaveRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="reason">Reason</label>
            <textarea name="reason" class="form-control" rows="4" required>{{ $leaveRequest->reason }}</textarea>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100">
            <i class="bi bi-check-lg"></i> Update
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