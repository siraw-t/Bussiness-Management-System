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

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
    }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-pending  { background: #fef3c7; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    [data-bs-theme="dark"] .status-badge { filter: brightness(0.85); }

    .amount-cell { font-weight: 600; white-space: nowrap; }
    .amount-total { font-weight: 700; color: var(--success); }

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
        .table-modern { min-width: 1500px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>{{ Auth::check() ? Auth::user()->company->company_name : 'Company' }} Purchase Requests</h1>
        <p>All purchase requests submitted within your company</p>
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Finance Comment</th>
                <th>Manager Comment</th>
                <th>Checked By</th>
                <th>Approved By</th>
                <th>Country</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $companyId = Auth::check() ? auth()->user()->company_id : null;
                $filteredPurchaseRequests = $purchaseRequests->filter(function ($request) use ($companyId) {
                    return $request->user && $request->user->company_id == $companyId;
                });
            @endphp

            @if ($filteredPurchaseRequests->isEmpty())
                <tr>
                    <td colspan="16">
                        <div class="empty-state">
                            <i class="bi bi-cart-x"></i>
                            <p class="mb-0">No purchase requests found.</p>
                        </div>
                    </td>
                </tr>
            @else
                @foreach ($filteredPurchaseRequests as $request)
                    <tr>
                        <td>{{ $request->reference_number }}</td>
                        <td><strong>{{ $request->item_name }}</strong></td>
                        <td>{{ $request->item_description ?? 'N/A' }}</td>
                        <td>{{ $request->quantity }} {{ $request->unit }}</td>
                        <td>{{ $request->unit }}</td>
                        <td class="amount-cell">{{ $request->price }} {{ $request->currency }}</td>
                        <td class="amount-total">{{ $request->total_price }} {{ $request->currency }}</td>
                        <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                        <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                        <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</td>
                        <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                        <td>{{ $request->company_country }}</td>
                        <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $request->updated_at->format('Y-m-d H:i') }}</td>
                        <td style="text-align:right;">
                            <a href="{{ route('purchase-requests.downloadPdf', $request) }}" class="btn-pdf">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection