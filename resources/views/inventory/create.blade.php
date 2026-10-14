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
        background: linear-gradient(135deg, #10b981, #34d399);
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
        font-size: 0.78rem; text-transform: uppercase;
        letter-spacing: 0.1em; color: var(--text-secondary);
        font-weight: 700; margin: 0 0 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-section h6 .step-num {
        width: 22px; height: 22px; border-radius: 6px;
        background: linear-gradient(135deg, #10b981, #34d399);
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

    @media (max-width: 576px) {
        .form-section { padding: 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Add Inventory Item</h1>
    <p>Add a new item to your inventory</p>
</div>

<form action="{{ route('inventory.store') }}" method="POST">
    @csrf
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-box-seam"></i></div>
            <div>
                <h5>Inventory Item Details</h5>
                <p>Fill in the information below</p>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">1</span> Item Information</h6>

            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <select name="item_name" class="form-select" required>
                    <option value="" disabled selected>Select an item</option>
                    @foreach ($approvedPaymentItems as $paymentItem)
                        <option value="{{ $paymentItem->item_name }}">
                            {{ $paymentItem->item_name }} — {{ $paymentItem->description }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tag_number" class="form-label">Tag Number <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="text" name="tag_number" class="form-control" placeholder="e.g., RTSP/2025/01">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="item_type" class="form-label">Item Type</label>
                    <select name="item_type" class="form-select" required>
                        <option value="fixed asset">Fixed Asset</option>
                        <option value="consumable">Consumable</option>
                    </select>
                </div>
            </div>

            <div class="mb-0">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">2</span> Quantity & Pricing</h6>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" required min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="unit_price" class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" class="form-control" required step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <select name="currency" class="form-select" required>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="UGX">UGX</option>
                        <option value="ETB">ETB</option>
                        <option value="AUD">AUD</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-0">
                    <label for="unit" class="form-label">Unit</label>
                    <select name="unit" class="form-select" required>
                        <option value="pcs">Pieces</option>
                        <option value="kg">Kilograms</option>
                        <option value="liters">Liters</option>
                        <option value="m">Meters</option>
                        <option value="box">Boxes</option>
                    </select>
                </div>
                <div class="col-md-6 mb-0">
                    <label for="stock_in_date" class="form-label">Stock In Date</label>
                    <input type="date" name="stock_in_date" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-plus-circle"></i> Add Item
            </button>
        </div>
    </div>
</form>
@endsection