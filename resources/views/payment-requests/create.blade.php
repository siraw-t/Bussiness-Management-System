@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Create Payment Request</h1>

        <form action="{{ route('payment-requests.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 600px; margin: auto;">
            @csrf

            {{-- <div class="form-group mb-3">
                <label for="reference_no">Reference No</label>
                <input type="text" name="reference_no" class="form-control" placeholder="Reference No">
            </div> --}}

            <div class="form-group mb-3">
                <label for="department">Department</label>
                <input type="text" name="department" class="form-control" placeholder="Department">
            </div>



            <div class="form-group mb-3">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control" placeholder="Item Name">
            </div>

            <div class="form-group mb-3">
                <label for="item_description">Item Description</label>
                <textarea name="item_description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <input type="text" name="unit" class="form-control" placeholder="e.g., pcs, kg, ltr">
            </div>

            <div class="form-group mb-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="currency">Currency</label>
                <input type="text" name="currency" class="form-control" placeholder="e.g., ETB, UGX, USD">
            </div>


            <div class="form-group mb-3">
                <label for="vat_inclusive">Include VAT?</label>
                <select name="vat_inclusive" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="cost_center">Cost Center</label>
                <input type="text" name="cost_center" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="account_code">Account Code</label>
                <input type="text" name="account_code" class="form-control">
            </div>



            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-lg"></i> Submit
            </button>
        </form>
    </div>
@endsection
