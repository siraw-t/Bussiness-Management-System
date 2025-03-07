@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Create Purchase Request</h1>

        <form action="{{ route('purchase-requests.store') }}" method="POST" enctype="multipart/form-data"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf

            <div class="form-group mb-3">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="item_description">Item Description</label>
                <textarea name="item_description" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <select name="unit" class="form-control" required>
                    <option value="kg">KG</option>
                    <option value="ltr">LTR</option>
                    <option value="pcs">Pcs</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="price">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="currency">Currency</label>
                <select name="currency" class="form-control" required>
                    <option value="USD">USD</option>
                    <option value="ETB">ETB</option>
                    <option value="UGX">UGX</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="attachments">Attachments</label>
                <input type="file" name="attachments[]" class="form-control" multiple required>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> Submit
            </button>
        </form>
    </div>
@endsection
