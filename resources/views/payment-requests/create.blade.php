{{-- @extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Create Payment Request</h1>

        <form action="{{ route('payment-requests.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 600px; margin: auto;">
            @csrf

            <div class="form-group mb-3">
                <label for="department">Department</label>
                <input type="text" name="department" class="form-control" placeholder="Department">
            </div>

            <div class="form-group mb-3">
                <label for="item_type">Item Type</label>
                <select name="item_type" class="form-control" required>
                    <option value="">Select Item Type</option>
                    <option value="service" @if (Auth::user()->company->country === 'Uganda') disabled @endif>
                        Service
                    </option>
                    <option value="purchase">Purchase</option>
                </select>
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
                <input type="number" name="quantity" class="form-control" id="quantity" min="1" value="1"
                    required>
            </div>

            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <input type="text" name="unit" class="form-control" placeholder="e.g., pcs, kg, ltr">
            </div>

            <div class="form-group mb-3">
                <label for="unit_price">Unit Price</label>
                <input type="number" name="unit_price" class="form-control" id="unit_price" step="0.01" required>
            </div>

            <div class="form-group mb-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" id="amount" required readonly>
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
            <div class="form-group mb-3">
                <label for="approved_by">Select Manager (Approve)</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled selected>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}">{{ $manager->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="checked_by">Select Finance (Check)</label>
                <select name="checked_by" class="form-control" required>
                    <option value="" disabled selected>Select a finance person</option>
                    @foreach ($finances as $finance)
                        <option value="{{ $finance->user_id }}">{{ $finance->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-lg"></i> Submit
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const unitPriceInput = document.getElementById('unit_price');
            const amountInput = document.getElementById('amount');

            function calculateAmount() {
                const quantity = parseFloat(quantityInput.value) || 0; // Default to 0 if NaN
                const unitPrice = parseFloat(unitPriceInput.value) || 0; // Default to 0 if NaN
                const amount = quantity * unitPrice;
                amountInput.value = amount.toFixed(2); // Display amount with two decimal places
            }

            quantityInput.addEventListener('input', calculateAmount);
            unitPriceInput.addEventListener('input', calculateAmount);
        });
    </script>
@endsection --}}

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Create Payment Request</h1>

        <form action="{{ route('payment-requests.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 600px; margin: auto;">
            @csrf

            <div class="form-group mb-3">
                <label for="department">Department</label>
                <input type="text" name="department" class="form-control" placeholder="Department">
            </div>
            <div class="form-group mb-3">
                <label for="cash_type">Cash Type</label>
                <select name="cash_type" class="form-control" required>
                    <option value="account_payable">Account Payable</option>
                    <option value="petty_cash">Petty Cash</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="item_type">Item Type</label>
                <select name="item_type" class="form-control" id="item_type" required>
                    <option value="">Select Item Type</option>
                    <option value="service" @if (Auth::user()->company->country === 'Uganda') disabled @endif>Service</option>
                    <option value="purchase">Purchase</option>
                </select>
            </div>

            <div class="form-group mb-3" id="item_name_dropdown" style="display: none;">
                <label for="item_name">Item Name (Select from dropdown)</label>
                <select name="item_name" class="form-control" id="item_name">
                    <option value="">Select Item</option>
                    @foreach ($approvedPurchaseItems as $purchaseItem)
                        <option value="{{ $purchaseItem->item_name }}">{{ $purchaseItem->item_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3" id="item_name_input" style="display: none;">
                <label for="item_name_manual">Item Name (Type manually)</label>
                <input type="text" name="item_name_manual" class="form-control" placeholder="Item Name">
            </div>


            <div class="form-group mb-3">
                <label for="item_description">Item Description</label>
                <textarea name="item_description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" id="quantity" min="1" value="1"
                    required>
            </div>

            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <input type="text" name="unit" class="form-control" placeholder="e.g., pcs, kg, ltr">
            </div>

            <div class="form-group mb-3">
                <label for="unit_price">Unit Price</label>
                <input type="number" name="unit_price" class="form-control" id="unit_price" step="0.01" required>
            </div>

            <div class="form-group mb-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" id="amount" required readonly>
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

            <div class="form-group mb-3">
                <label for="approved_by">Select Manager (Approve)</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled selected>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}">{{ $manager->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="checked_by">Select Finance (Check)</label>
                <select name="checked_by" class="form-control" required>
                    <option value="" disabled selected>Select a finance person</option>
                    @foreach ($finances as $finance)
                        <option value="{{ $finance->user_id }}">{{ $finance->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-lg"></i> Submit
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemTypeSelect = document.getElementById('item_type');
            const itemNameDropdown = document.getElementById('item_name_dropdown');
            const itemNameInput = document.getElementById('item_name_input');

            itemTypeSelect.addEventListener('change', function() {
                if (this.value === 'purchase') {
                    itemNameDropdown.style.display = 'block';
                    itemNameInput.style.display = 'none';
                } else if (this.value === 'service') {
                    itemNameDropdown.style.display = 'none';
                    itemNameInput.style.display = 'block';
                } else {
                    itemNameDropdown.style.display = 'none';
                    itemNameInput.style.display = 'none';
                }
            });

            const quantityInput = document.getElementById('quantity');
            const unitPriceInput = document.getElementById('unit_price');
            const amountInput = document.getElementById('amount');

            function calculateAmount() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                const amount = quantity * unitPrice;
                amountInput.value = amount.toFixed(2);
            }

            quantityInput.addEventListener('input', calculateAmount);
            unitPriceInput.addEventListener('input', calculateAmount);
        });
    </script>
@endsection
