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

    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-cell .u-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem; flex-shrink: 0;
    }
    .user-cell .u-name { font-weight: 600; color: var(--text-primary); }

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
    .amount-net { font-weight: 700; color: var(--success); }

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
        <h1>Payroll Records</h1>
        <p>All payroll records processed for your company</p>
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Country</th>
                <th>Job Title</th>
                <th>Pay Period</th>
                <th>Basic Salary</th>
                <th>Transport</th>
                <th>Taxable Transport</th>
                <th>Position Allowance</th>
                <th>Total Taxable</th>
                <th>Income Tax</th>
                <th>Pension 7%</th>
                <th>Pension 11%</th>
                <th>Salary Advance</th>
                <th>Net Salary</th>
                <th>Status</th>
                <th>Manager Comments</th>
                <th>Prepared By</th>
                <th>Approved By</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $userCompanyId = auth()->user()->company_id;
                $filteredPayrolls = $payrolls->filter(function ($payroll) use ($userCompanyId) {
                    return $payroll->employee->company_id == $userCompanyId;
                });
            @endphp

            @if ($filteredPayrolls->isEmpty())
                <tr>
                    <td colspan="21">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p class="mb-0">No payroll records found.</p>
                        </div>
                    </td>
                </tr>
            @else
                @foreach ($filteredPayrolls as $payroll)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="u-avatar">{{ strtoupper(substr($payroll->employee->full_name ?? 'E', 0, 1)) }}</div>
                                <span class="u-name">{{ $payroll->employee->full_name }}</span>
                            </div>
                        </td>
                        <td>{{ $payroll->company_country }}</td>
                        <td>{{ $payroll->job_title }}</td>
                        <td>{{ $payroll->pay_period }}</td>
                        <td class="amount-cell">{{ number_format($payroll->basic_salary, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell">{{ number_format($payroll->transport_allowance ?? 0, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell">{{ number_format($payroll->taxable_transport_allowance ?? 0, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell">{{ number_format($payroll->position_allowance ?? 0, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell">{{ number_format($payroll->total_taxable_earnings, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell">{{ number_format($payroll->income_tax, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell">{{ number_format($payroll->pension_fund_7, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell">{{ number_format($payroll->pension_fund_11, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell">{{ number_format($payroll->salary_advance ?? 0, 2) }} {{ $payroll->currency }}</td>
                        <td class="amount-cell amount-net">{{ number_format($payroll->net_payment, 2) }} {{ $payroll->currency }}</td>
                        <td>
                            <span class="status-badge status-{{ $payroll->status }}">{{ ucfirst($payroll->status) }}</span>
                        </td>
                        <td>{{ $payroll->manager_comment ?? 'N/A' }}</td>
                        <td>{{ $payroll->preparedBy ? $payroll->preparedBy->full_name : 'N/A' }}</td>
                        <td>{{ optional(\App\Models\User::find($payroll->approved_by))->full_name ?? 'N/A' }}</td>
                        <td>{{ $payroll->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $payroll->updated_at->format('Y-m-d H:i') }}</td>
                        <td style="text-align:right;">
                            <a href="{{ route('finance.payrolls.downloadPdf', $payroll) }}" class="btn-pdf">
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