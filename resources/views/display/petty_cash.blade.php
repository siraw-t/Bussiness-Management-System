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
    .btn-summary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 16px; border-radius: 12px;
        font-weight: 600; font-size: 0.85rem;
        text-decoration: none; transition: var(--transition);
    }
    .btn-summary.manager { background: linear-gradient(135deg, var(--primary), var(--accent)); color: #fff; box-shadow: 0 8px 20px rgba(99,102,241,0.3); }
    .btn-summary.finance { background: linear-gradient(135deg, #10b981, #34d399); color: #fff; box-shadow: 0 8px 20px rgba(16,185,129,0.3); }
    .btn-summary:hover { color: #fff; transform: translateY(-2px); }

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

    .btn-pdf {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 7px 12px; border-radius: 10px;
        background: #dbeafe; color: #1e40af;
        font-size: 0.8rem; font-weight: 600;
        border: none; cursor: pointer; transition: var(--transition);
        text-decoration: none;
    }
    .btn-pdf:hover { background: #bfdbfe; color: #1e40af; transform: translateY(-1px); }

    .attach-form {
        display: flex; gap: 6px; align-items: center; flex-wrap: wrap;
    }
    .attach-form input[type="file"] {
        font-size: 0.78rem; max-width: 200px;
    }
    .attach-btn {
        padding: 6px 12px; border-radius: 8px;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: #fff; border: none; font-weight: 600; font-size: 0.78rem;
        cursor: pointer; transition: var(--transition);
    }
    .attach-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(16,185,129,0.3); }

    .attachment-list { list-style: none; padding: 0; margin: 0; font-size: 0.8rem; }
    .attachment-list li { margin-bottom: 3px; }
    .attachment-list a { color: var(--primary); }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1200px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1300px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>{{ Auth::check() ? Auth::user()->company->company_name : 'Company' }} Petty Cash Requests</h1>
        <p>All petty cash requests and approvals</p>
    </div>
    <div class="head-actions">
        @if (Auth::check() && Auth::user()->role === 'manager')
            <a href="{{ route('summaries.manager') }}" class="btn-summary manager">
                <i class="bi bi-file-earmark-bar-graph"></i> Manager Summary
            </a>
        @endif
        @if (Auth::check() && Auth::user()->role === 'finance')
            <a href="{{ route('summaries.finance') }}" class="btn-summary finance">
                <i class="bi bi-file-earmark-bar-graph"></i> Finance Summary
            </a>
        @endif
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Title</th>
                <th>Description</th>
                <th>Amount Requested</th>
                <th>In Words</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Finance Comment</th>
                <th>Amount Approved</th>
                <th>Approved in Words</th>
                <th>Manager Comment</th>
                <th>Checked By</th>
                <th>Approved By</th>
                <th>Country</th>
                <th>Attachments</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $companyId = Auth::check() ? auth()->user()->company_id : null;
                $filteredCashRequests = $pettyCashRequests->filter(function ($request) use ($companyId) {
                    return $request->user && $request->user->company_id == $companyId;
                });
            @endphp

            @if ($filteredCashRequests->isEmpty())
                <tr>
                    <td colspan="16">
                        <div class="empty-state">
                            <i class="bi bi-wallet2"></i>
                            <p class="mb-0">No petty cash requests found.</p>
                        </div>
                    </td>
                </tr>
            @else
                @foreach ($filteredCashRequests as $request)
                    <tr>
                        <td>{{ $request->reference_number }}</td>
                        <td>{{ $request->title }}</td>
                        <td>{{ $request->description ?? 'N/A' }}</td>
                        <td><strong>{{ number_format($request->amount_requested, 2) }} {{ $request->currency }}</strong></td>
                        <td>{{ $request->amount_requested_in_words ?? 'N/A' }}</td>
                        <td>{{ $request->currency }}</td>
                        <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                        <td><strong>{{ number_format($request->amount_approved, 2) }}</strong></td>
                        <td>{{ $request->amount_approved_in_words ?? 'N/A' }}</td>
                        <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                        <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</td>
                        <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                        <td>{{ $request->company_country }}</td>
                        <td>
                            @php
                                $attachments = is_string($request->attachments)
                                    ? json_decode($request->attachments, true)
                                    : $request->attachments;
                            @endphp
                            @if (!empty($attachments))
                                <ul class="attachment-list">
                                    @foreach ($attachments as $attachment)
                                        <li>
                                            <a href="{{ asset('storage/' . $attachment) }}" target="_blank">
                                                <i class="bi bi-paperclip"></i> {{ basename($attachment) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('petty_cash_requests.downloadPdf', $request) }}" class="btn-pdf mb-2">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>
                            @if (Auth::check() && Auth::user()->role === 'finance' && $request->status === 'approved')
                                <form action="{{ route('finance.petty_cash.attach', ['petty_cash_request_id' => $request->petty_cash_request_id]) }}" method="POST" enctype="multipart/form-data" class="attach-form">
                                    @csrf
                                    <input type="file" name="attachments[]" multiple class="form-control">
                                    <button type="submit" class="attach-btn">
                                        <i class="bi bi-upload"></i> Attach
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection