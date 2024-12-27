@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Purchase Request</h1>

    <form action="{{ route('purchase-requests.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="estimated_cost">Estimated Cost</label>
            <input type="number" name="estimated_cost" class="form-control" step="0.01" required>
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
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3" placeholder="Optional comments..."></textarea>
        </div>
      
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endsection