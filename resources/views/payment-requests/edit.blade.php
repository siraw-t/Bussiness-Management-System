@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Edit Payment Request</h1>

        <form action="{{ route('payment-requests.update', $paymentRequest) }}" method="POST"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf
            @method('PUT')

            {{-- <div class="form-group mb-3">
                <label for="reference_no">Reference No</label>
                <input type="text" name="reference_no" class="form-control"
                    value="{{ old('reference_no', $paymentRequest->reference_no) }}">
            </div> --}}

            <div class="form-group mb-3">
                <label for="department">Department</label>
                <input type="text" name="department" class="form-control"
                    value="{{ old('department', $paymentRequest->department) }}">
            </div>

            <div class="form-group mb-3">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control"
                    value="{{ old('item_name', $paymentRequest->item_name) }}">
            </div>

            <div class="form-group mb-3">
                <label for="item_description">Item Description</label>
                <textarea name="item_description" class="form-control" rows="4" required>{{ old('item_description', $paymentRequest->item_description) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control"
                    value="{{ old('amount', $paymentRequest->amount) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control"
                    value="{{ old('quantity', $paymentRequest->quantity) }}">
            </div>

            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <input type="text" name="unit" class="form-control" value="{{ old('unit', $paymentRequest->unit) }}">
            </div>

            <div class="form-group mb-3">
                <label for="cost_center">Cost Center</label>
                <input type="text" name="cost_center" class="form-control"
                    value="{{ old('cost_center', $paymentRequest->cost_center) }}">
            </div>

            <div class="form-group mb-3">
                <label for="account_code">Account Code</label>
                <input type="text" name="account_code" class="form-control"
                    value="{{ old('account_code', $paymentRequest->account_code) }}">
            </div>

            <div class="form-group mb-3">
                <label for="currency">Currency</label>
                <input type="text" name="currency" class="form-control"
                    value="{{ old('currency', $paymentRequest->currency) }}">
            </div>

            <div class="form-group mb-3">
                <label for="vat_inclusive">Include VAT?</label>
                <select name="vat_inclusive" class="form-control" required>
                    <option value="1" {{ $paymentRequest->vat_inclusive ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$paymentRequest->vat_inclusive ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-lg"></i> Update
            </button>
            <a href="{{ route('payment-requests.index') }}" class="btn btn-secondary btn-lg w-100 mt-2">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
        </form>
    </div>
@endsection
