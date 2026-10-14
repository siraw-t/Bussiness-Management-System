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
        font-size: 0.86rem; color: var(--text-primary); vertical-align: middle;
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

    @media (max-width: 1100px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1400px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Budget Forecasting</h1>
        <p>Overview of all budget forecasts for your company</p>
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Period</th>
                <th>Description</th>
                <th>Item</th>
                <th>Code</th>
                <th>Department</th>
                <th>Source</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Total Budget</th>
                <th>Status</th>
                <th>Manager Comment</th>
                <th>Approved By</th>
                <th>Reference</th>
                <th>Country</th>
                <th>Created At</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $userCompanyId = auth()->user()->company_id;
                $filteredBudgetForecasts = $budgetForecasts->filter(function ($forecast) use ($userCompanyId) {
                    return $forecast->creator->company_id == $userCompanyId;
                });
            @endphp

            @if ($filteredBudgetForecasts->isEmpty())
                <tr>
                    <td colspan="16">
                        <div class="empty-state">
                            <i class="bi bi-wallet2"></i>
                            <p class="mb-0">No budget forecasts found.</p>
                        </div>
                    </td>
                </tr>
            @else
                @foreach ($filteredBudgetForecasts as $forecast)
                    <tr>
                        <td>{{ ucfirst($forecast->forecast_period) }}</td>
                        <td>{{ $forecast->budget_description }}</td>
                        <td>{{ $forecast->budget_item }}</td>
                        <td>{{ $forecast->budget_code }}</td>
                        <td>{{ $forecast->department }}</td>
                        <td>{{ $forecast->budget_source }}</td>
                        <td>{{ $forecast->quantity }} {{ $forecast->unit }}</td>
                        <td>{{ number_format($forecast->unit_cost, 2) }} {{ $forecast->currency }}</td>
                        <td>{{ number_format($forecast->total_budget, 2) }} {{ $forecast->currency }}</td>
                        <td>
                            <span class="status-badge status-{{ $forecast->status }}">
                                {{ ucfirst($forecast->status) }}
                            </span>
                        </td>
                        <td>{{ $forecast->manager_comment ?? 'N/A' }}</td>
                        <td>{{ optional($forecast->approver)->full_name ?? 'N/A' }}</td>
                        <td>{{ $forecast->reference_number }}</td>
                        <td>{{ $forecast->company_country }}</td>
                        <td>{{ $forecast->created_at->format('Y-m-d H:i') }}</td>
                        <td style="text-align:right;">
                            <a href="{{ route('finance.budgets.downloadBudgetPdf', $forecast) }}" class="btn-pdf">
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