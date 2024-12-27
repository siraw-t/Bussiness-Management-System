@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Leave Request</h1>

    <form action="{{ route('leave-requests.update', $leaveRequest) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ $leaveRequest->start_date }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ $leaveRequest->end_date }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" selected>Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea name="reason" class="form-control" required>{{ $leaveRequest->reason }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection