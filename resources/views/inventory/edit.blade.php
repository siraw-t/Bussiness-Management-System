@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-warning mb-4"><i class="fas fa-edit"></i> Edit Inventory Item</h1>

        <form action="{{ route('inventory.update', $inventory->inventory_id) }}" method="POST"
            class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control" value="{{ $inventory->item_name }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="tag_number">Tag Number</label>
                <input type="text" name="tag_number" class="form-control" value="{{ $inventory->tag_number }}"
                    placeholder="Optional">
            </div>
            <div class="form-group mb-3">
                <label for="item_type">Item Type</label>
                <select name="item_type" class="form-control" required>
                    <option value="fixed asset" {{ $inventory->item_type == 'fixed asset' ? 'selected' : '' }}>
                        Fixed Asset
                    </option>
                    <option value="consumable" {{ $inventory->item_type == 'consumable' ? 'selected' : '' }}>
                        Consumable
                    </option>
                </select>
            </div>
            {{-- <div class="form-group mb-3">
                <label for="reference_number">Reference Number</label>
                <input type="text" name="reference_number" class="form-control"
                    value="{{ $inventory->reference_number }}" readonly>
            </div> --}}
            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ $inventory->quantity }}" required
                    min="0">
            </div>
            <div class="form-group mb-3">
                <label for="unit_price">Unit Price</label>
                <input type="number" name="unit_price" class="form-control" value="{{ $inventory->unit_price }}" required
                    step="0.01" min="0">
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
                <label for="currency">Currency</label>
                <select name="currency" class="form-control" required>
                    <option value="USD" {{ $inventory->currency == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ $inventory->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="UGX" {{ $inventory->currency == 'UGX' ? 'selected' : '' }}>UGX</option>
                    <option value="ETB" {{ $inventory->currency == 'ETB' ? 'selected' : '' }}>ETB</option>
                    <option value="AUD" {{ $inventory->currency == 'AUD' ? 'selected' : '' }}>AUD</option>
                    <!-- Add more currencies as needed -->
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <select name="unit" class="form-control" required>
                    <option value="pcs" {{ $inventory->unit == 'pcs' ? 'selected' : '' }}>Pieces</option>
                    <option value="kg" {{ $inventory->unit == 'kg' ? 'selected' : '' }}>Kilograms</option>
                    <option value="liters" {{ $inventory->unit == 'liters' ? 'selected' : '' }}>Liters</option>
                    <option value="m" {{ $inventory->unit == 'm' ? 'selected' : '' }}>Meters</option>
                    <option value="box" {{ $inventory->unit == 'box' ? 'selected' : '' }}>Boxes</option>
                    <!-- Add more units as needed -->
                </select>
            </div>
            {{-- <div class="form-group mb-3">
                <label for="company_country">Company Country</label>
                <input type="text" name="company_country" class="form-control" value="{{ $inventory->company_country }}"
                    required>
            </div> --}}
            <button type="submit" class="btn btn-warning w-100">
                <i class="fas fa-sync-alt"></i> Update Item
            </button>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary w-100 mt-2">
                <i class="fas fa-times-circle"></i> Cancel
            </a>
        </form>
    </div>
@endsection
