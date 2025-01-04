@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-warning mb-4"><i class="fas fa-edit"></i> Edit Inventory Item</h1>

    <form action="{{ route('inventory.update', $inventory->inventory_id) }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" class="form-control" value="{{ $inventory->item_name }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ $inventory->quantity }}" required min="0">
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control">{{ $inventory->description }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="stock_in_date">Stock In Date</label>
            <input type="date" name="stock_in_date" class="form-control" value="{{ $inventory->stock_in_date }}">
        </div>
        <div class="form-group mb-3">
            <label for="stock_out_date">Stock Out Date</label>
            <input type="date" name="stock_out_date" class="form-control" value="{{ $inventory->stock_out_date }}">
        </div>
        {{-- <div class="form-group mb-3">
            <label for="approved_by">Approved By</label>
            <select name="approved_by" class="form-control">
                <option value="">-- Select --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $inventory->approved_by ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group mb-3">
            <label for="managed_by">Managed By</label>
            <input type="text" class="form-control" value="{{ $inventory->managed_by }}" readonly>
        </div>
        
        <button type="submit" class="btn btn-warning w-100">
            <i class="fas fa-sync-alt"></i> Update Item
        </button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary w-100 mt-2">
            <i class="fas fa-times-circle"></i> Cancel
        </a>
    </form>
</div>
@endsection