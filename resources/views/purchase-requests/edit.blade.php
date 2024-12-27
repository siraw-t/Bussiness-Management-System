@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Purchase Request</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('purchase-requests.update', $purchaseRequest) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $purchaseRequest->item_name) }}" required>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $purchaseRequest->quantity) }}" required>
        </div>

        <div class="form-group">
            <label for="estimated_cost">Estimated Cost</label>
            <input type="number" name="estimated_cost" class="form-control" step="0.01" value="{{ old('estimated_cost', $purchaseRequest->estimated_cost) }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $purchaseRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $purchaseRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $purchaseRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3">{{ old('comment', $purchaseRequest->comment) }}</textarea>
        </div>

       

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('purchase-requests.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection