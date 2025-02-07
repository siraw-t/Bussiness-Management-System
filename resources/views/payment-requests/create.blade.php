@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Create Payment Request</h1>

        <form action="{{ route('payment-requests.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 600px; margin: auto;">
            @csrf

            <div class="form-group mb-3">
                <label for="ref_no">Reference No</label>
                <input type="text" name="ref_no" class="form-control" placeholder="ref_no">
            </div>

            <div class="form-group mb-3">
                <label for="from">Department</label>
                <input type="text" name="from" class="form-control" placeholder="dpt">
            </div>

            <div class="form-group mb-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="unit_and_qty">Unit and Quantity</label>
                <input type="text" name="unit_and_qty" class="form-control" placeholder="e.g., 10 pcs">
            </div>

            <div class="form-group mb-3">
                <label for="cost_center">Cost Center</label>
                <input type="text" name="cost_center" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="account_code">Account Code</label>
                <input type="text" name="account_code" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="country_cost_recharge">Country Cost Recharge</label>
                <input type="number" name="country_cost_recharge" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="allocation_for_country">Allocation for Country</label>
                <input type="number" name="allocation_for_country" class="form-control">
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-lg"></i> Submit
            </button>
        </form>
    </div>
@endsection
