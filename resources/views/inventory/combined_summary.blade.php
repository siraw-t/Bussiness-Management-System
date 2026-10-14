@extends('layouts.app')

@section('content')
<style>
    .page-head {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .page-head h1 {
        font-size: 1.6rem; font-weight: 800; margin: 0;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .page-head p { color: var(--text-secondary); margin: 4px 0 0; font-size: 0.88rem; }

    .total-banner {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        border-radius: var(--radius-lg);
        padding: 22px 28px;
        color: #fff;
        margin-bottom: 22px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
        box-shadow: 0 18px 40px rgba(16,185,129,0.28);
        position: relative; overflow: hidden;
    }
    .total-banner::after {
        content: ''; position: absolute; top: -30px; right: -30px;
        width: 150px; height: 150px; border-radius: 50%;
        background: rgba(255,255,255,0.12);
    }
    .total-banner .tb-label {
        font-size: 0.82rem; text-transform: uppercase;
        letter-spacing: 0.1em; opacity: 0.9; font-weight: 600;
    }
    .total-banner .tb-value {
        font-size: 2rem; font-weight: 800; line-height: 1; margin-top: 6px;
    }
    .total-banner .tb-icon {
        width: 56px; height: 56px; border-radius: 16px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; flex-shrink: 0;
    }

    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 18px; border-radius: 12px;
        background: var(--bg-card); color: var(--text-primary);
        border: 1px solid var(--border-color);
        font-weight: 600; font-size: 0.88rem;
        text-decoration: none; transition: var(--transition);
    }
    .btn-back:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-1px); }

    .table-card {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);
    }
    .table-modern { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; }
    .table-modern thead th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        color: var(--text-secondary);
        font-weight: 700; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.08em;
        padding: 14px 16px; text-align: left;
        border-bottom: 1px solid var(--border-color); white-space: nowrap;
    }
    [data-bs-theme="dark"] .table-modern thead th { background: #0b1120; }
    .table-modern tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.85rem; color: var(--text-primary); vertical-align: middle;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .amount-cell { font-weight: 500; white-space: nowrap; }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1200px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1400px; }
    }
    @media (max-width: 576px) {
        .total-banner .tb-value { font-size: 1.5rem; }
        .page-head h1 { font-size: 1.35rem; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Combined Inventory Summary</h1>
        <p>Inventory items and stock requisitions combined</p>
    </div>
</div>

@if (!empty($combinedData))
    <div class="total-banner">
        <div>
            <div class="tb-label">Total Value</div>
            <div class="tb-value">{{ number_format($totalValue, 2) }} {{ $combinedData[0]['currency'] ?? '' }}</div>
        </div>
        <div class="tb-icon"><i class="bi bi-bar-chart-line"></i></div>
    </div>
@endif

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Stock In</th>
                <th>Currency</th>
                <th>Unit</th>
                <th>Qty Req.</th>
                <th>Qty Appr.</th>
                <th>Stock Out</th>
                <th>Balance Qty</th>
                <th>Balance Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($combinedData as $data)
                <tr>
                    <td><strong>{{ $data['item_name'] }}</strong></td>
                    <td>{{ $data['item_type'] ?? '' }}</td>
                    <td>{{ $data['quantity'] ?? 0 }}</td>
                    <td class="amount-cell">{{ isset($data['unit_price']) ? number_format($data['unit_price'], 2) : '' }}</td>
                    <td class="amount-cell">{{ isset($data['total_price']) ? number_format($data['total_price'], 2) : '' }}</td>
                    <td>{{ $data['stock_in_date'] ? \Carbon\Carbon::parse($data['stock_in_date'])->format('Y-m-d') : '' }}</td>
                    <td>{{ $data['currency'] ?? '' }}</td>
                    <td>{{ $data['unit'] ?? '' }}</td>
                    <td>{{ $data['quantity_requested'] ?? 0 }}</td>
                    <td>{{ $data['quantity_approved'] ?? 0 }}</td>
                    <td>{{ $data['stock_out_date'] ? \Carbon\Carbon::parse($data['stock_out_date'])->format('Y-m-d') : '' }}</td>
                    <td>{{ $data['balance_quantity'] ?? '' }}</td>
                    <td class="amount-cell">{{ isset($data['balance_total_price']) ? number_format($data['balance_total_price'], 2) : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13">
                        <div class="empty-state">
                            <i class="bi bi-bar-chart-line"></i>
                            <p class="mb-0">No combined data available.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    <a href="{{ route('inventory.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Inventory
    </a>
</div>
@endsection