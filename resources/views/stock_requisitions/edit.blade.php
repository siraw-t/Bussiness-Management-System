@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-warning mb-4"><i class="fas fa-edit"></i> Edit Stock Requisition</h1>

        <form action="{{ route('stock_requisitions.update', $stockRequisition->stock_requisition_id) }}" method="POST"
            class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="item_name_with_description">Item Name & Description</label>
                <select name="item_name_with_description" class="form-control" required>
                    <option value="" disabled>Select an item</option>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->item_name }}"
                            {{ $inventory->item_name === $stockRequisition->item_name_with_description ? 'selected' : '' }}>
                            {{ $inventory->item_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="quantity_requested">Quantity Requested</label>
                <input type="number" name="quantity_requested" class="form-control"
                    value="{{ $stockRequisition->quantity_requested }}" required min="1">
            </div>

            <button type="submit" class="btn btn-warning w-100">
                <i class="fas fa-sync-alt"></i> Update Requisition
            </button>
            <a href="{{ route('stock_requisitions.index') }}" class="btn btn-secondary w-100 mt-2">
                <i class="fas fa-times-circle"></i> Cancel
            </a>
        </form>
    </div>
@endsection
