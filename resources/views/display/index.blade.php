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

    .qty-pill {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 5px 12px; border-radius: 999px;
        font-weight: 700; font-size: 0.82rem; min-width: 62px;
    }
    .qty-high { background: #d1fae5; color: #065f46; }
    .qty-mid  { background: #fef3c7; color: #92400e; }
    .qty-low  { background: #fee2e2; color: #991b1b; }
    [data-bs-theme="dark"] .qty-pill { filter: brightness(0.85); }

    .btn-pdf {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 7px 12px; border-radius: 10px;
        background: #dbeafe; color: #1e40af;
        font-size: 0.8rem; font-weight: 600;
        border: none; cursor: pointer; transition: var(--transition);
        text-decoration: none;
    }
    .btn-pdf:hover { background: #bfdbfe; color: #1e40af; transform: translateY(-1px); }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1200px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1800px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Inventory Lists</h1>
        <p>All inventory items, stock levels, and issued records</p>
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Tag Number</th>
                <th>Item Type</th>
                <th>Reference</th>
                <th>Country</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Description</th>
                <th>Stock In</th>
                <th>Stock Out</th>
                <th>Issued Qty</th>
                <th>Issued Unit Price</th>
                <th>Issued Total</th>
                <th>Issued Dept.</th>
                <th>Managed By</th>
                <th>Received By</th>
                <th>Balance Qty</th>
                <th>Balance Total</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $userCompanyId = auth()->user()->company_id;
                $filteredInventories = $inventories->filter(function ($inventory) use ($userCompanyId) {
                    return ($inventory->manager && $inventory->manager->company_id == $userCompanyId) ||
                        ($inventory->approver && $inventory->approver->company_id == $userCompanyId);
                });
            @endphp

            @if ($filteredInventories->isEmpty())
                <tr>
                    <td colspan="22">
                        <div class="empty-state">
                            <i class="bi bi-box-seam"></i>
                            <p class="mb-0">No inventory items found.</p>
                        </div>
                    </td>
                </tr>
            @else
                @foreach ($filteredInventories as $inventory)
                    @php
                        if ($inventory->quantity <= 3) { $qtyClass = 'qty-low'; }
                        elseif ($inventory->quantity <= 10) { $qtyClass = 'qty-mid'; }
                        else { $qtyClass = 'qty-high'; }
                    @endphp
                    <tr>
                        <td><strong>{{ $inventory->item_name }}</strong></td>
                        <td>{{ $inventory->tag_number ?? 'N/A' }}</td>
                        <td>{{ ucfirst($inventory->item_type) }}</td>
                        <td>{{ $inventory->reference_number }}</td>
                        <td>{{ $inventory->company_country }}</td>
                        <td><span class="qty-pill {{ $qtyClass }}">{{ $inventory->quantity }} {{ $inventory->unit }}</span></td>
                        <td>{{ number_format($inventory->unit_price, 2) }} {{ $inventory->currency }}</td>
                        <td>{{ number_format($inventory->total_price, 2) }} {{ $inventory->currency }}</td>
                        <td>{{ $inventory->description ?? 'N/A' }}</td>
                        <td>{{ $inventory->stock_in_date ? \Illuminate\Support\Carbon::parse($inventory->stock_in_date)->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $inventory->stock_out_date ? \Illuminate\Support\Carbon::parse($inventory->stock_out_date)->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $inventory->issued_quantity ?? 'N/A' }} {{ $inventory->issued_unit }}</td>
                        <td>{{ $inventory->issued_unit_price ? number_format($inventory->issued_unit_price, 2) : 'N/A' }} {{ $inventory->issued_currency }}</td>
                        <td>{{ $inventory->issued_total_price ? number_format($inventory->issued_total_price, 2) : 'N/A' }} {{ $inventory->issued_currency }}</td>
                        <td>{{ $inventory->issued_department ?? 'N/A' }}</td>
                        <td>{{ $inventory->manager ? $inventory->manager->full_name : 'N/A' }}</td>
                        <td>{{ $inventory->received_by ? optional($inventory->receivedBy)->full_name : 'N/A' }}</td>
                        <td>{{ $inventory->balance_quantity ?? 'N/A' }} {{ $inventory->unit }}</td>
                        <td>{{ $inventory->balance_total_price ? number_format($inventory->balance_total_price, 2) : 'N/A' }} {{ $inventory->currency }}</td>
                        <td>{{ $inventory->created_at ? \Illuminate\Support\Carbon::parse($inventory->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                        <td>{{ $inventory->updated_at ? \Illuminate\Support\Carbon::parse($inventory->updated_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                        <td style="text-align:right;">
                            <a href="{{ route('inventory.downloadPdf', $inventory->inventory_id) }}" class="btn-pdf">
                                <i class="bi bi-download"></i> PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection