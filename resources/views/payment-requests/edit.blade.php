{{-- @extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Edit Payment Request</h1>

        <form action="{{ route('payment-requests.update', $paymentRequest) }}" method="POST"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="department">Department</label>
                <input type="text" name="department" class="form-control"
                    value="{{ old('department', $paymentRequest->department) }}">
            </div>

            <div class="form-group mb-3">
                <label for="item_type">Item Type</label>
                <select name="item_type" class="form-control" required>
                    <option value="service" {{ $paymentRequest->item_type === 'service' ? 'selected' : '' }}>Service
                    </option>
                    <option value="purchase" {{ $paymentRequest->item_type === 'purchase' ? 'selected' : '' }}>Purchase
                    </option>
                </select>
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
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" id="quantity"
                    value="{{ old('quantity', $paymentRequest->quantity) }}" min="1" required>
            </div>

            <div class="form-group mb-3">
                <label for="unit_price">Unit Price</label>
                <input type="number" name="unit_price" class="form-control" id="unit_price"
                    value="{{ old('unit_price', $paymentRequest->unit_price) }}" required step="0.01">
            </div>

            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <input type="text" name="unit" class="form-control" value="{{ old('unit', $paymentRequest->unit) }}">
            </div>

            <div class="form-group mb-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" class="form-control" id="amount"
                    value="{{ old('amount', $paymentRequest->amount) }}" required readonly>
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
            <div class="form-group mb-3">
                <label for="approved_by">Select Manager (Approve)</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}"
                            {{ $manager->user_id == $paymentRequest->approved_by ? 'selected' : '' }}>
                            {{ $manager->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="checked_by">Select Finance (Check)</label>
                <select name="checked_by" class="form-control" required>
                    <option value="" disabled>Select a finance person</option>
                    @foreach ($finances as $finance)
                        <option value="{{ $finance->user_id }}"
                            {{ $finance->user_id == $paymentRequest->reviewed_by ? 'selected' : '' }}>
                            {{ $finance->full_name }}
                        </option>
                    @endforeach
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unitPriceInput = document.getElementById('unit_price');
            const quantityInput = document.getElementById('quantity');
            const amountInput = document.getElementById('amount');

            function calculateAmount() {
                const quantity = parseFloat(quantityInput.value) || 0; // Default to 0 if NaN
                const unitPrice = parseFloat(unitPriceInput.value) || 0; // Default to 0 if NaN
                const amount = quantity * unitPrice;
                amountInput.value = amount.toFixed(2); // Update amount with two decimal places
            }

            quantityInput.addEventListener('input', calculateAmount);
            unitPriceInput.addEventListener('input', calculateAmount);

            // Initial calculation on page load
            calculateAmount();
        });
    </script>
@endsection --}}

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Edit Payment Request</h1>

        {{-- Display validation errors if any --}}
        @if ($errors->any())
            <div class="alert alert-danger" style="max-width: 600px; margin: auto; margin-bottom: 1rem;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('payment-requests.update', $paymentRequest) }}" method="POST"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="department">Department</label>
                <input type="text" name="department" class="form-control"
                    value="{{ old('department', $paymentRequest->department) }}">
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
                    {{-- Add disabled option for Uganda service if needed --}}
                    <option value="service"
                        {{ old('item_type', $paymentRequest->item_type) === 'service' ? 'selected' : '' }}
                        @if (Auth::user()->company->country === 'Uganda') disabled @endif>Service</option>
                    <option value="purchase"
                        {{ old('item_type', $paymentRequest->item_type) === 'purchase' ? 'selected' : '' }}>Purchase
                    </option>
                </select>
            </div>

            {{-- Item Name Dropdown (for Purchase) --}}
            <div class="form-group mb-3" id="item_name_dropdown"
                style="{{ old('item_type', $paymentRequest->item_type) === 'purchase' ? '' : 'display: none;' }}">
                <label for="item_name">Item Name (Select from dropdown)</label>
                <select name="item_name" class="form-control" id="item_name_select"> {{-- Changed ID slightly to avoid conflict --}}
                    <option value="">Select Item</option>
                    @foreach ($approvedPurchaseItems as $purchaseItem)
                        <option value="{{ $purchaseItem->item_name }}" {{-- Use old() first, then check against model for selection --}}
                            {{ old('item_name', $paymentRequest->item_name) == $purchaseItem->item_name ? 'selected' : '' }}>
                            {{ $purchaseItem->item_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Item Name Manual Input (for Service) --}}
            <div class="form-group mb-3" id="item_name_input"
                style="{{ old('item_type', $paymentRequest->item_type) === 'service' ? '' : 'display: none;' }}">
                <label for="item_name_manual">Item Name (Type manually)</label>
                {{-- *** CORRECTED name and value attributes *** --}}
                <input type="text" name="item_name_manual" id="item_name_manual_input" class="form-control"
                    value="{{ old('item_name_manual', $paymentRequest->item_name_manual) }}">
            </div>

            <div class="form-group mb-3">
                <label for="item_description">Item Description</label>
                <textarea name="item_description" class="form-control" rows="3" required>{{ old('item_description', $paymentRequest->item_description) }}</textarea>
            </div>

            <div class="row"> {{-- Use row for better layout --}}
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" class="form-control" id="quantity"
                            value="{{ old('quantity', $paymentRequest->quantity) }}" min="1" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="unit">Unit</label>
                        <input type="text" name="unit" class="form-control" placeholder="e.g., pcs, kg, ltr"
                            value="{{ old('unit', $paymentRequest->unit) }}">
                    </div>
                </div>
            </div>


            <div class="row"> {{-- Use row for better layout --}}
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="unit_price">Unit Price</label>
                        <input type="number" name="unit_price" class="form-control" id="unit_price"
                            value="{{ old('unit_price', $paymentRequest->unit_price) }}" required step="0.01">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" class="form-control" id="amount" {{-- Display calculated amount, ensure it uses old quantity/price if validation fails --}}
                            value="{{ old('quantity', $paymentRequest->quantity) * old('unit_price', $paymentRequest->unit_price) }}"
                            required readonly>
                    </div>
                </div>
            </div>


            <div class="form-group mb-3">
                <label for="currency">Currency</label>
                <input type="text" name="currency" class="form-control" placeholder="e.g., ETB, UGX, USD"
                    value="{{ old('currency', $paymentRequest->currency) }}">
            </div>

            <div class="form-group mb-3">
                <label for="vat_inclusive">Include VAT?</label>
                <select name="vat_inclusive" class="form-control" required>
                    {{-- Use old() first for sticky selection --}}
                    <option value="1"
                        {{ old('vat_inclusive', $paymentRequest->vat_inclusive) == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0"
                        {{ old('vat_inclusive', $paymentRequest->vat_inclusive) == '0' ? 'selected' : '' }}>No</option>
                </select>
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
                <label for="approved_by">Select Manager (Approve)</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}" {{-- Use old() first for sticky selection --}}
                            {{ old('approved_by', $paymentRequest->approved_by) == $manager->user_id ? 'selected' : '' }}>
                            {{ $manager->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="checked_by">Select Finance (Check)</label>
                <select name="checked_by" class="form-control" required>
                    <option value="" disabled>Select a finance person</option>
                    @foreach ($finances as $finance)
                        <option value="{{ $finance->user_id }}" {{-- Use old() first for sticky selection --}}
                            {{ old('checked_by', $paymentRequest->reviewed_by) == $finance->user_id ? 'selected' : '' }}>
                            {{ $finance->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-lg"></i> Update Request
            </button>
            <a href="{{ route('payment-requests.index') }}" class="btn btn-secondary btn-lg w-100 mt-2">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unitPriceInput = document.getElementById('unit_price');
            const quantityInput = document.getElementById('quantity');
            const amountInput = document.getElementById('amount');
            const itemTypeSelect = document.getElementById('item_type');
            const itemNameDropdown = document.getElementById('item_name_dropdown');
            const itemNameInput = document.getElementById('item_name_input');
            // Get the specific input/select elements within the divs
            const itemNameSelect = document.getElementById('item_name_select');
            const itemNameManualInput = document.getElementById('item_name_manual_input');

            function calculateAmount() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                const amount = quantity * unitPrice;
                amountInput.value = amount.toFixed(2); // Ensure 2 decimal places
            }

            function toggleItemNameFields() {
                if (itemTypeSelect.value === 'purchase') {
                    itemNameDropdown.style.display = 'block';
                    itemNameInput.style.display = 'none';
                    // Optionally clear the manual input when switching to purchase
                    if (itemNameManualInput) itemNameManualInput.value = '';
                } else if (itemTypeSelect.value === 'service') {
                    itemNameDropdown.style.display = 'none';
                    itemNameInput.style.display = 'block';
                    // Optionally clear the dropdown selection when switching to service
                    if (itemNameSelect) itemNameSelect.value = '';
                } else {
                    itemNameDropdown.style.display = 'none';
                    itemNameInput.style.display = 'none';
                }
            }

            // Add event listeners
            quantityInput.addEventListener('input', calculateAmount);
            unitPriceInput.addEventListener('input', calculateAmount);
            itemTypeSelect.addEventListener('change', toggleItemNameFields);

            // Initial setup on page load
            toggleItemNameFields(); // Set initial visibility based on current selection
            calculateAmount(); // Calculate initial amount
        });
    </script>
@endsection
