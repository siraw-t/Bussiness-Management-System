@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Create Stock Requisition</h1>

        <form action="{{ route('stock_requisitions.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 500px; margin: auto;">
            @csrf
            <div class="form-group mb-3">
                <label for="item_name">Item Name</label>
                <select name="item_name" class="form-control" required>
                    <option value="" disabled selected>Select an item</option>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->item_name }}">{{ $inventory->item_name }} -
                            {{ $inventory->description }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional"></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="quantity_requested">Quantity Requested</label>
                <input type="number" name="quantity_requested" class="form-control" required min="1">
            </div>

            <div class="form-group mb-3">
                <label for="unit_requested">Unit Requested</label>
                <input type="text" name="unit_requested" class="form-control" placeholder="e.g., pcs, kg">
            </div>
            <div class="form-group mb-3">
                <label for="approved_by">Select Manager</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled selected>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}">{{ $manager->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-plus-circle"></i> Submit Requisition
            </button>
            <a href="{{ route('stock_requisitions.index') }}" class="btn btn-secondary w-100 mt-2">
                <i class="fas fa-times-circle"></i> Cancel
            </a>
        </form>
    </div>
@endsection
