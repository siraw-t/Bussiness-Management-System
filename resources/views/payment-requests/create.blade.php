@extends('layouts.app')

@section('content')
<style>
    .form-page-head { text-align: center; margin-bottom: 26px; }
    .form-page-head h1 {
        font-size: 1.7rem; font-weight: 800; margin: 0 0 6px; color: var(--text-primary);
    }
    .form-page-head p { color: var(--text-secondary); font-size: 0.9rem; margin: 0; }

    .form-card {
        max-width: 780px; margin: 0 auto;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .form-header {
        padding: 24px 28px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        display: flex; align-items: center; gap: 14px;
    }
    .form-header .hdr-icon {
        width: 46px; height: 46px; border-radius: 14px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .form-header h5 { margin: 0; font-size: 1.05rem; font-weight: 700; }
    .form-header p { margin: 2px 0 0; font-size: 0.82rem; opacity: 0.9; }

    .form-section {
        padding: 22px 28px;
        border-bottom: 1px solid var(--border-color);
    }
    .form-section:last-of-type { border-bottom: none; }
    .form-section h6 {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-secondary);
        font-weight: 700;
        margin: 0 0 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-section h6 .step-num {
        width: 22px; height: 22px; border-radius: 6px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.72rem; font-weight: 700;
    }

    .form-actions {
        padding: 20px 28px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        display: flex; justify-content: flex-end; gap: 10px;
    }
    [data-bs-theme="dark"] .form-actions { background: #0b1120; }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 26px; border-radius: 12px;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: #fff; border: none; font-weight: 600; font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(16,185,129,0.3);
        transition: var(--transition);
    }
    .btn-submit:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(16,185,129,0.45); }

    .amount-box {
        background: var(--primary-soft);
        border: 1px solid var(--primary-light);
        border-radius: 12px;
        padding: 14px 16px;
    }
    .amount-box input {
        background: transparent !important;
        border: none !important;
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        color: var(--primary-dark) !important;
        padding: 0 !important;
    }
    [data-bs-theme="dark"] .amount-box input { color: var(--primary-light) !important; }

    @media (max-width: 576px) {
        .form-section { padding: 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Create Payment Request</h1>
    <p>Submit a new payment request for approval</p>
</div>

<form action="{{ route('payment-requests.store') }}" method="POST">
    @csrf
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-cash-coin"></i></div>
            <div>
                <h5>Payment Request Details</h5>
                <p>Fill in the information below</p>
            </div>
        </div>

        {{-- Section 1: Basic --}}
        <div class="form-section">
            <h6><span class="step-num">1</span> Basic Information</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" placeholder="Department">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cash_type" class="form-label">Cash Type</label>
                    <select name="cash_type" class="form-select" required>
                        <option value="account_payable">Account Payable</option>
                        <option value="petty_cash">Petty Cash</option>
                    </select>
                </div>
            </div>

            <div class="mb-0">
                <label for="item_type" class="form-label">Item Type</label>
                <select name="item_type" class="form-select" id="item_type" required>
                    <option value="">Select Item Type</option>
                    <option value="service" @if (Auth::user()->company->country === 'Uganda') disabled @endif>Service</option>
                    <option value="purchase">Purchase</option>
                </select>
            </div>
        </div>

        {{-- Section 2: Item --}}
        <div class="form-section">
            <h6><span class="step-num">2</span> Item Details</h6>

            <div class="mb-3" id="item_name_dropdown" style="display: none;">
                <label for="item_name" class="form-label">Item Name (Select from dropdown)</label>
                <select name="item_name" class="form-select" id="item_name">
                    <option value="">Select Item</option>
                    @foreach ($approvedPurchaseItems as $purchaseItem)
                        <option value="{{ $purchaseItem->item_name }}">{{ $purchaseItem->item_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3" id="item_name_input" style="display: none;">
                <label for="item_name_manual" class="form-label">Item Name (Type manually)</label>
                <input type="text" name="item_name_manual" class="form-control" placeholder="Item Name">
            </div>

            <div class="mb-3">
                <label for="item_description" class="form-label">Item Description</label>
                <textarea name="item_description" class="form-control" rows="3" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" id="quantity" min="1" value="1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Unit</label>
                    <input type="text" name="unit" class="form-control" placeholder="e.g., pcs, kg, ltr">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="unit_price" class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" class="form-control" id="unit_price" step="0.01" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="amount" class="form-label">Amount (Auto-calculated)</label>
                    <div class="amount-box">
                        <input type="number" name="amount" id="amount" required readonly placeholder="0.00">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <input type="text" name="currency" class="form-control" placeholder="e.g., ETB, UGX, USD">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="vat_inclusive" class="form-label">Include VAT?</label>
                    <select name="vat_inclusive" class="form-select" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cost_center" class="form-label">Cost Center</label>
                    <input type="text" name="cost_center" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="account_code" class="form-label">Account Code</label>
                    <input type="text" name="account_code" class="form-control">
                </div>
            </div>
        </div>

        {{-- Section 3: Approvers --}}
        <div class="form-section">
            <h6><span class="step-num">3</span> Approvers</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="approved_by" class="form-label">Select Manager (Approve)</label>
                    <select name="approved_by" class="form-select" required>
                        <option value="" disabled selected>Select a manager</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->user_id }}">{{ $manager->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="checked_by" class="form-label">Select Finance (Check)</label>
                    <select name="checked_by" class="form-select" required>
                        <option value="" disabled selected>Select a finance person</option>
                        @foreach ($finances as $finance)
                            <option value="{{ $finance->user_id }}">{{ $finance->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('payment-requests.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-check2-circle"></i> Submit Request
            </button>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemTypeSelect = document.getElementById('item_type');
        const itemNameDropdown = document.getElementById('item_name_dropdown');
        const itemNameInput = document.getElementById('item_name_input');
        const quantityInput = document.getElementById('quantity');
        const unitPriceInput = document.getElementById('unit_price');
        const amountInput = document.getElementById('amount');

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