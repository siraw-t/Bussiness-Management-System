@extends('layouts.app')

@section('content')
<style>
    .form-page-head { text-align: center; margin-bottom: 26px; }
    .form-page-head h1 {
        font-size: 1.7rem; font-weight: 800; margin: 0 0 6px; color: var(--text-primary);
    }
    .form-page-head p { color: var(--text-secondary); font-size: 0.9rem; margin: 0; }

    .form-card {
        max-width: 720px; margin: 0 auto;
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
        font-size: 0.78rem; text-transform: uppercase;
        letter-spacing: 0.1em; color: var(--text-secondary);
        font-weight: 700; margin: 0 0 16px;
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
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; border: none; font-weight: 600; font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        transition: var(--transition);
    }
    .btn-submit:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99,102,241,0.45); }

    .current-attachments {
        background: var(--primary-soft);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 16px;
        margin-top: 12px;
    }
    .current-attachments .ca-title {
        font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.08em; color: var(--text-secondary); margin: 0 0 10px;
    }
    .current-attachments ul { list-style: none; padding: 0; margin: 0; }
    .current-attachments li {
        display: flex; align-items: center; gap: 6px;
        padding: 5px 0; font-size: 0.85rem;
        border-bottom: 1px dashed var(--border-color);
    }
    .current-attachments li:last-child { border-bottom: none; }
    .current-attachments a { color: var(--primary); text-decoration: none; font-weight: 500; }
    .current-attachments a:hover { text-decoration: underline; }

    @media (max-width: 576px) {
        .form-section { padding: 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Edit Purchase Request</h1>
    <p>Update the details of your purchase request</p>
</div>

@if (session('success'))
    <div class="alert alert-success" style="max-width:720px;margin:0 auto 20px;">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
    </div>
@endif

<form action="{{ route('purchase-requests.update', $purchaseRequest) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h5>Purchase Request Details</h5>
                <p>Modify the fields you want to change</p>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">1</span> Item Information</h6>

            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $purchaseRequest->item_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="item_description" class="form-label">Item Description</label>
                <textarea name="item_description" class="form-control" rows="3">{{ old('item_description', $purchaseRequest->item_description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $purchaseRequest->quantity) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Unit</label>
                    <select name="unit" class="form-select" required>
                        <option value="kg" {{ $purchaseRequest->unit == 'kg' ? 'selected' : '' }}>KG</option>
                        <option value="ltr" {{ $purchaseRequest->unit == 'ltr' ? 'selected' : '' }}>LTR</option>
                        <option value="pcs" {{ $purchaseRequest->unit == 'pcs' ? 'selected' : '' }}>Pcs</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $purchaseRequest->price) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <select name="currency" class="form-select" required>
                        <option value="USD" {{ $purchaseRequest->currency == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="ETB" {{ $purchaseRequest->currency == 'ETB' ? 'selected' : '' }}>ETB</option>
                        <option value="UGX" {{ $purchaseRequest->currency == 'UGX' ? 'selected' : '' }}>UGX</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">2</span> Attachments</h6>

            <div class="mb-0">
                <label for="attachments" class="form-label">Attachments</label>
                <input type="file" name="attachments[]" class="form-control" multiple>
                @if ($purchaseRequest->attachments)
                    <div class="current-attachments">
                        <p class="ca-title"><i class="bi bi-paperclip"></i> Current Attachments</p>
                        <ul>
                            @foreach (json_decode($purchaseRequest->attachments) as $attachment)
                                <li>
                                    <i class="bi bi-file-earmark-text text-primary"></i>
                                    <a href="{{ asset($attachment) }}" target="_blank">{{ basename($attachment) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">3</span> Approvers</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="approved_by" class="form-label">Select Manager (Approve)</label>
                    <select name="approved_by" class="form-select" required>
                        <option value="" disabled>Select a manager</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->user_id }}" {{ $manager->user_id == $purchaseRequest->approved_by ? 'selected' : '' }}>
                                {{ $manager->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="checked_by" class="form-label">Select Finance (Check)</label>
                    <select name="checked_by" class="form-select" required>
                        <option value="" disabled>Select a finance person</option>
                        @foreach ($finances as $finance)
                            <option value="{{ $finance->user_id }}" {{ $finance->user_id == $purchaseRequest->checked_by ? 'selected' : '' }}>
                                {{ $finance->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('purchase-requests.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-save2"></i> Update Request
            </button>
        </div>
    </div>
</form>
@endsection