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

    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 16px; border-radius: 12px;
        background: var(--bg-card); color: var(--text-primary);
        border: 1px solid var(--border-color);
        font-weight: 600; font-size: 0.85rem;
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
        font-size: 0.85rem; color: var(--text-primary); vertical-align: top;
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

    .btn-submit-inline {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 10px;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: #fff; border: none; font-weight: 600; font-size: 0.8rem;
        cursor: pointer; transition: var(--transition); width: 100%;
    }
    .btn-submit-inline:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(16,185,129,0.3); }

    textarea.form-control { font-size: 0.85rem; min-height: 60px; resize: vertical; }

    .empty-state {
        padding: 60px 20px; text-align: center; color: var(--text-secondary);
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
    }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1200px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1700px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Payment Requests</h1>
        <p>Review and comment on pending payment requests</p>
    </div>
    <a href="{{ route('finance.dashboard') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if ($pendingPaymentRequests->isEmpty())
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p class="mb-0">No payment requests found at this time.</p>
    </div>
@else
    <div class="table-card">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Reference No</th>
                    <th>Request Date</th>
                    <th>Department</th>
                    <th>Item Description</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>VAT</th>
                    <th>Total</th>
                    <th>Net Payment</th>
                    <th>Withholding</th>
                    <th>Cost Center</th>
                    <th>Account Code</th>
                    <th>Status</th>
                    <th>Finance Comment</th>
                    <th>Manager Comment</th>
                    <th>Reviewed By</th>
                    <th>Approved By</th>
                    <th style="min-width:240px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendingPaymentRequests as $request)
                    <tr>
                        <td>{{ $request->reference_no ?? 'N/A' }}</td>
                        <td>{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $request->department ?? 'N/A' }}</td>
                        <td>{{ $request->item_description }}</td>
                        <td>{{ $request->item_name ?? 'N/A' }}</td>
                        <td>{{ $request->quantity ?? 'N/A' }} {{ $request->unit ?? 'N/A' }}</td>
                        <td>{{ number_format($request->amount, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                        <td>{{ number_format($request->vat, 2) ?? '0.00' }} {{ $request->currency ?? 'N/A' }}</td>
                        <td>{{ number_format($request->total, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                        <td>{{ number_format($request->net_payment, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                        <td>{{ number_format($request->with_holding, 2) ?? '0.00' }} {{ $request->currency ?? 'N/A' }}</td>
                        <td>{{ $request->cost_center ?? 'N/A' }}</td>
                        <td>{{ $request->account_code ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                        <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                        <td>{{ $request->reviewed_by ? optional(\App\Models\User::find($request->reviewed_by))->full_name : 'Not Checked' }}</td>
                        <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('finance.payment.comment', ['payment_request_id' => $request->payment_request_id]) }}" method="POST">
                                @csrf
                                <textarea class="form-control mb-2" id="finance-comment-{{ $request->payment_request_id }}" name="finance_comment" rows="2" placeholder="Add a comment..." required></textarea>
                                <button type="submit" class="btn-submit-inline">
                                    <i class="bi bi-send"></i> Submit Comment
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection