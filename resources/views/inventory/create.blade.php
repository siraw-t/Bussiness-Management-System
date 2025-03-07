@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Add Inventory Item</h1>

        <form action="{{ route('inventory.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 500px; margin: auto;">
            @csrf
            <div class="form-group mb-3">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="tag_number">Tag Number</label>
                <input type="text" name="tag_number" class="form-control" placeholder="Optional">
            </div>
            <div class="form-group mb-3">
                <label for="item_type">Item Type</label>
                <select name="item_type" class="form-control" required>
                    <option value="fixed asset">Fixed Asset</option>
                    <option value="consumable">Consumable</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" required min="0">
            </div>
            <div class="form-group mb-3">
                <label for="unit_price">Unit Price</label>
                <input type="number" name="unit_price" class="form-control" required step="0.01" min="0">
            </div>
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="stock_in_date">Stock In Date</label>
                <input type="date" name="stock_in_date" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="currency">Currency</label>
                <select name="currency" class="form-control" required>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">UGX</option>
                    <option value="JPY">ETB</option>
                    <option value="AUD">AUD</option>
                    <!-- Add more currencies as needed -->
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <select name="unit" class="form-control" required>
                    <option value="pcs">Pieces</option>
                    <option value="kg">Kilograms</option>
                    <option value="liters">Liters</option>
                    <option value="m">Meters</option>
                    <option value="box">Boxes</option>
                    <!-- Add more units as needed -->
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-plus-circle"></i> Add Item
            </button>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary w-100 mt-2">
                <i class="fas fa-times-circle"></i> Cancel
            </a>
        </form>
    </div>
@endsection
