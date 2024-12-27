@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Inventory Item</h1>

    <form action="{{ route('inventory.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" required min="0">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="stock_in_date">Stock In Date</label>
            <input type="date" name="stock_in_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="stock_out_date">Stock Out Date</label>
            <input type="date" name="stock_out_date" class="form-control">
        </div>
        {{-- <div class="form-group">
            <label for="approved_by">Approved By</label>
            <select name="approved_by" class="form-control">
                <option value="">-- Select --</option>
                @foreach($users as $user)
                    <option value="{{ $user->user_id }}">{{ $user->username }}</option>
                @endforeach
            </select>
        </div>
        <!-- Managed By Field (hidden, automatically set) -->
        <input type="hidden" name="managed_by" value="{{ Auth::id() }}"> --}}
        
        <button type="submit" class="btn btn-primary">Add Item</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection