@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Create Purchase Request</h1>

    <form action="{{ route('purchase-requests.store') }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
        @csrf
        
        <div class="form-group mb-3">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>
        
        <div class="form-group mb-3">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        
        <div class="form-group mb-3">
            <label for="estimated_cost">Estimated Cost</label>
            <input type="number" name="estimated_cost" class="form-control" step="0.01" required>
        </div>
        
        <div class="form-group mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" selected>Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        
        <div class="form-group mb-3">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3" placeholder="Optional comments..."></textarea>
        </div>
      
        <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-check-lg"></i> Submit
        </button>
    </form>
</div>
@endsection