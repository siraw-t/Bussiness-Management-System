@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-warning mb-4"><i class="fas fa-edit"></i> Edit Stock Requisition</h1>

        <form action="{{ route('stock_requisitions.update', $stockRequisition->stock_requisition_id) }}" method="POST"
            class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control" value="{{ $stockRequisition->item_name }}"
                    required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $stockRequisition->description }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="quantity_requested">Quantity Requested</label>
                <input type="number" name="quantity_requested" class="form-control"
                    value="{{ $stockRequisition->quantity_requested }}" required min="1">
            </div>

            <div class="form-group mb-3">
                <label for="unit_requested">Unit Requested</label>
                <input type="text" name="unit_requested" class="form-control"
                    value="{{ $stockRequisition->unit_requested }}" placeholder="e.g., pcs, kg">
            </div>
            <div class="form-group mb-3">
                <label for="approved_by">Select Manager</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled selected>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}"
                            {{ $manager->user_id == $stockRequisition->approved_by ? 'selected' : '' }}>
                            {{ $manager->full_name }}
                        </option>
                    @endforeach
                </select>
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
