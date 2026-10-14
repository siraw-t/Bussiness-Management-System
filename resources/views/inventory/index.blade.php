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

    .head-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 18px; border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; font-weight: 600; font-size: 0.88rem;
        border: none; box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        transition: var(--transition); text-decoration: none;
    }
    .btn-add:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99,102,241,0.45); }
    .btn-add.secondary {
        background: linear-gradient(135deg, #10b981, #34d399);
        box-shadow: 0 8px 20px rgba(16,185,129,0.3);
    }
    .btn-add.secondary:hover { box-shadow: 0 12px 26px rgba(16,185,129,0.45); }

    .legend-bar {
        display: flex; align-items: center; gap: 18px;
        flex-wrap: wrap;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 12px 20px;
        margin-bottom: 18px;
        font-size: 0.82rem;
    }
    .legend-bar .legend-label {
        font-weight: 700; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--text-secondary);
    }
    .legend-item { display: inline-flex; align-items: center; gap: 8px; font-weight: 500; }
    .legend-dot {
        width: 12px; height: 12px; border-radius: 50%;
        display: inline-block;
    }
    .legend-dot.critical { background: #ef4444; }
    .legend-dot.low      { background: #f59e0b; }
    .legend-dot.good     { background: #10b981; }

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
        font-weight: 700; font-size: 0.8rem; min-width: 58px;
    }
    .qty-critical { background: #fee2e2; color: #991b1b; }
    .qty-low      { background: #fef3c7; color: #92400e; }
    .qty-good     { background: #d1fae5; color: #065f46; }
    [data-bs-theme="dark"] .qty-pill { filter: brightness(0.85); }

    .amount-cell { font-weight: 500; white-space: nowrap; }
    .amount-total { font-weight: 700; white-space: nowrap; }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 5px;
        padding: 7px 11px; border-radius: 9px;
        font-size: 0.78rem; font-weight: 600; border: none; cursor: pointer;
        transition: var(--transition); text-decoration: none; white-space: nowrap;
    }
    .action-btn.stockout { background: #dbeafe; color: #1e40af; }
    .action-btn.stockout:hover { background: #bfdbfe; transform: translateY(-1px); color: #1e40af; }
    .action-btn.balance  { background: #e0e7ff; color: #4338ca; }
    .action-btn.balance:hover { background: #c7d2fe; transform: translateY(-1px); color: #4338ca; }
    .action-btn.edit     { background: #fef3c7; color: #92400e; }
    .action-btn.edit:hover { background: #fde68a; transform: translateY(-1px); }
    .action-btn.delete   { background: #fee2e2; color: #991b1b; }
    .action-btn.delete:hover { background: #fecaca; transform: translateY(-1px); }

    .action-group { display: flex; gap: 5px; flex-wrap: wrap; justify-content: flex-end; }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1200px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1500px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Inventory List</h1>
        <p>All items in your company's inventory</p>
    </div>
    <div class="head-actions">
        <a href="{{ route('inventory.create') }}" class="btn-add">
            <i class="bi bi-plus-lg"></i> Add New Item
        </a>
        <a href="{{ route('inventory.combinedSummary') }}" class="btn-add secondary">
            <i class="bi bi-bar-chart-line"></i> Combined Summary
        </a>
    </div>
</div>

<div class="legend-bar">
    <span class="legend-label">Quantity Legend:</span>
    <span class="legend-item"><span class="legend-dot critical"></span> Critical (≤ 3)</span>
    <span class="legend-item"><span class="legend-dot low"></span> Low (≤ 10)</span>
    <span class="legend-item"><span class="legend-dot good"></span> Sufficient (&gt; 10)</span>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Tag Number</th>
                <th>Item Type</th>
                <th>Reference</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Description</th>
                <th>Stock In</th>
                <th>Managed By</th>
                <th>Country</th>
                <th>Issued Stocks</th>
                <th>Balance</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventories as $inventory)
                @php
                    if ($inventory->quantity <= 3) { $qtyClass = 'qty-critical'; }
                    elseif ($inventory->quantity <= 10) { $qtyClass = 'qty-low'; }
                    else { $qtyClass = 'qty-good'; }
                @endphp
                <tr>
                    <td><strong>{{ $inventory->item_name }}</strong></td>
                    <td>{{ $inventory->tag_number }}</td>
                    <td>{{ ucfirst($inventory->item_type) }}</td>
                    <td>{{ $inventory->reference_number }}</td>
                    <td><span class="qty-pill {{ $qtyClass }}">{{ $inventory->quantity }} {{ $inventory->unit }}</span></td>
                    <td class="amount-cell">{{ number_format($inventory->unit_price, 2) }} {{ $inventory->currency }}</td>
                    <td class="amount-total">{{ number_format($inventory->total_price, 2) }} {{ $inventory->currency }}</td>
                    <td>{{ $inventory->description }}</td>
                    <td>{{ $inventory->stock_in_date }}</td>
                    <td>{{ optional(\App\Models\User::find($inventory->managed_by))->full_name ?? 'N/A' }}</td>
                    <td>{{ $inventory->company_country }}</td>
                    <td>
                        <button type="button" class="action-btn stockout" data-bs-toggle="modal" data-bs-target="#stockOutModal{{ $inventory->inventory_id }}">
                            <i class="bi bi-box-arrow-right"></i> Stock Out
                        </button>
                    </td>
                    <td>
                        <button type="button" class="action-btn balance" data-bs-toggle="modal" data-bs-target="#balanceModal{{ $inventory->inventory_id }}">
                            <i class="bi bi-eye"></i> Balance
                        </button>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('inventory.edit', $inventory->inventory_id) }}" class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('inventory.destroy', $inventory->inventory_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete"
                                    onclick="return confirm('Are you sure you want to delete this item?');"
                                    title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Stock Out Modal --}}
                <div class="modal fade" id="stockOutModal{{ $inventory->inventory_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-box-arrow-right me-2"></i>Stock Out Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('inventory.stockOut', $inventory->inventory_id) }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Issued Quantity</label>
                                            <input type="number" name="issued_quantity" class="form-control" min="1" max="{{ $inventory->quantity }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Issued Unit Price</label>
                                            <input type="number" name="issued_unit_price" class="form-control" required step="0.01" min="0">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Issued Total Price</label>
                                        <p id="issuedTotalPrice{{ $inventory->inventory_id }}" class="form-control bg-light mb-0">0.00</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Stock Out Date</label>
                                            <input type="date" name="stock_out_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Currency</label>
                                            <input type="text" name="issued_currency" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Unit</label>
                                            <input type="text" name="issued_unit" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Issued Department</label>
                                            <input type="text" name="issued_department" class="form-control" maxlength="255" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Received By</label>
                                        <select name="received_by" class="form-select" required>
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-check2-circle me-1"></i> Submit
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Balance Modal --}}
                <div class="modal fade" id="balanceModal{{ $inventory->inventory_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Balance Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="text-muted">Balance Quantity</th>
                                            <td class="text-end"><strong>{{ $inventory->balance_quantity ?? 'N/A' }} {{ $inventory->unit }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="text-muted">Balance Total</th>
                                            <td class="text-end"><strong>{{ number_format($inventory->balance_total_price ?? 0, 2) }} {{ $inventory->currency }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function calculateTotals(form) {
        const issuedQuantity = parseInt(form.issued_quantity.value) || 0;
        const issuedUnitPrice = parseFloat(form.issued_unit_price.value) || 0;

        // Calculate the issued total price
        const issuedTotalPrice = issuedQuantity * issuedUnitPrice;
        document.getElementById('issuedTotalPrice' + form.issued_quantity.id.replace('issued_quantity', '')).innerText =
            issuedTotalPrice.toFixed(2);
    }
</script>
@endsection