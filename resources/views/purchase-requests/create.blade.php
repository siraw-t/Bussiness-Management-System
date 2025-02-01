@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Create Purchase Request</h1>

        <form action="{{ route('purchase-requests.store') }}" method="POST" enctype="multipart/form-data"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf

            <div class="form-group mb-3">
                <label for="item_info">Item Info</label>
                <input type="text" name="item_info" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="quantity_with_unit">Quantity with Unit</label>
                <input type="text" name="quantity_with_unit" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="price_with_currency">Price with Currency</label>
                <input type="text" name="price_with_currency" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="attachments">Attachments (At least 3 files required)</label>
                <input type="file" name="attachments[]" class="form-control" multiple required>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> Submit
            </button>
        </form>
    </div>
@endsection
