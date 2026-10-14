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
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
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
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
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
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: #fff; border: none; font-weight: 600; font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(245,158,11,0.3);
        transition: var(--transition);
    }
    .btn-submit:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(245,158,11,0.45); }

    @media (max-width: 576px) {
        .form-section { padding: 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Edit Inventory Item</h1>
    <p>Update item <strong>{{ $inventory->item_name }}</strong></p>
</div>

<form action="{{ route('inventory.update', $inventory->inventory_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h5>Inventory Item Details</h5>
                <p>Modify the fields you want to change</p>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">1</span> Item Information</h6>

            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" name="item_name" class="form-control" value="{{ $inventory->item_name }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tag_number" class="form-label">Tag Number <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="text" name="tag_number" class="form-control" value="{{ $inventory->tag_number }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="item_type" class="form-label">Item Type</label>
                    <select name="item_type" class="form-select" required>
                        <option value="fixed asset" {{ $inventory->item_type == 'fixed asset' ? 'selected' : '' }}>Fixed Asset</option>
                        <option value="consumable" {{ $inventory->item_type == 'consumable' ? 'selected' : '' }}>Consumable</option>
                    </select>
                </div>
            </div>

            <div class="mb-0">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $inventory->description }}</textarea>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">2</span> Quantity & Pricing</h6>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="{{ $inventory->quantity }}" required min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="unit_price" class="form-label">Unit Price</label>
                    <input type="number" name="unit_price" class="form-control" value="{{ $inventory->unit_price }}" required step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <select name="currency" class="form-select" required>
                        <option value="USD" {{ $inventory->currency == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ $inventory->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="UGX" {{ $inventory->currency == 'UGX' ? 'selected' : '' }}>UGX</option>
                        <option value="ETB" {{ $inventory->currency == 'ETB' ? 'selected' : '' }}>ETB</option>
                        <option value="AUD" {{ $inventory->currency == 'AUD' ? 'selected' : '' }}>AUD</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-0">
                    <label for="unit" class="form-label">Unit</label>
                    <select name="unit" class="form-select" required>
                        <option value="pcs" {{ $inventory->unit == 'pcs' ? 'selected' : '' }}>Pieces</option>
                        <option value="kg" {{ $inventory->unit == 'kg' ? 'selected' : '' }}>Kilograms</option>
                        <option value="liters" {{ $inventory->unit == 'liters' ? 'selected' : '' }}>Liters</option>
                        <option value="m" {{ $inventory->unit == 'm' ? 'selected' : '' }}>Meters</option>
                        <option value="box" {{ $inventory->unit == 'box' ? 'selected' : '' }}>Boxes</option>
                    </select>
                </div>
                <div class="col-md-6 mb-0">
                    <label for="stock_in_date" class="form-label">Stock In Date</label>
                    <input type="date" name="stock_in_date" class="form-control" value="{{ $inventory->stock_in_date }}">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-arrow-clockwise"></i> Update Item
            </button>
        </div>
    </div>
</form>
@endsection