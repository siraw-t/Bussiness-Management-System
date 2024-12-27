@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Payment Request</h1>

    <form action="{{ route('payment-requests.update', $paymentRequest) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" class="form-control" value="{{ old('amount', $paymentRequest->amount) }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required>{{ old('description', $paymentRequest->description) }}</textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $paymentRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $paymentRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $paymentRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3">{{ old('comment', $paymentRequest->comment) }}</textarea>
        </div>
        <div class="form-group">
            <label for="approved_by">Approved By (User ID)</label>
            <input type="number" name="approved_by" class="form-control" value="{{ old('approved_by', $paymentRequest->approved_by) }}" placeholder="Optional User ID">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('payment-requests.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection