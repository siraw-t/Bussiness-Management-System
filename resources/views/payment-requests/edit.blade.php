@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Edit Payment Request</h1>

        <form action="{{ route('payment-requests.update', $paymentRequest) }}" method="POST"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" value="{{ old('amount', $paymentRequest->amount) }}"
                    required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $paymentRequest->description) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="unit_and_qty">Unit and Quantity</label>
                <input type="text" name="unit_and_qty" class="form-control"
                    value="{{ old('unit_and_qty', $paymentRequest->unit_and_qty) }}">
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
                <label for="country_cost_recharge">Country Cost Recharge</label>
                <input type="number" name="country_cost_recharge" class="form-control"
                    value="{{ old('country_cost_recharge', $paymentRequest->country_cost_recharge) }}">
            </div>

            <div class="form-group mb-3">
                <label for="allocation_for_country">Allocation for Country</label>
                <input type="number" name="allocation_for_country" class="form-control"
                    value="{{ old('allocation_for_country', $paymentRequest->allocation_for_country) }}">
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
