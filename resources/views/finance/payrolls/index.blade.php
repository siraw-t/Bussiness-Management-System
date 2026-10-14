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

    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 18px; border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; font-weight: 600; font-size: 0.88rem;
        border: none; box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        transition: var(--transition); text-decoration: none;
    }
    .btn-add:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99,102,241,0.45); }

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
    .user-cell .u-name { font-weight: 600; color: var(--text-primary); white-space: nowrap; }

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
    }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-pending  { background: #fef3c7; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    [data-bs-theme="dark"] .status-badge { filter: brightness(0.85); }

    .amount-cell { white-space: nowrap; font-weight: 500; }
    .amount-net  { white-space: nowrap; font-weight: 700; color: var(--success); }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 5px;
        padding: 7px 11px; border-radius: 9px;
        font-size: 0.78rem; font-weight: 600; border: none; cursor: pointer;
        transition: var(--transition); text-decoration: none; white-space: nowrap;
    }
    .action-btn.edit   { background: #fef3c7; color: #92400e; }
    .action-btn.edit:hover { background: #fde68a; transform: translateY(-1px); }
    .action-btn.delete { background: #fee2e2; color: #991b1b; }
    .action-btn.delete:hover { background: #fecaca; transform: translateY(-1px); }
    .action-btn.pdf    { background: #dbeafe; color: #1e40af; }
    .action-btn.pdf:hover { background: #bfdbfe; transform: translateY(-1px); }
    .action-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

    .action-group { display: flex; gap: 5px; flex-wrap: wrap; justify-content: flex-end; }

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
        <p>All payroll records submitted for your company</p>
    </div>
    <a href="{{ route('finance.payrolls.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Add Payroll
    </a>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Pay Period</th>
                <th>Job Title</th>
                <th>Reference</th>
                <th>Basic Salary</th>
                <th>Days</th>
                <th>Transport</th>
                <th>Taxable Transport</th>
                <th>Position Allow.</th>
                <th>Total Taxable</th>
                <th>Income Tax</th>
                <th>Pension 7%</th>
                <th>Pension 11%</th>
                <th>Salary Advance</th>
                <th>Net Payment</th>
                <th>Prepared By</th>
                <th>Status</th>
                <th>Approved By</th>
                <th>Manager Comments</th>
                <th>Country</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payrolls as $payroll)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="u-avatar">{{ strtoupper(substr($payroll->employee->username ?? 'E', 0, 1)) }}</div>
                            <span class="u-name">{{ $payroll->employee->username }}</span>
                        </div>
                    </td>
                    <td>{{ $payroll->pay_period }}</td>
                    <td>{{ $payroll->job_title }}</td>
                    <td>{{ $payroll->reference_number }}</td>
                    <td class="amount-cell">{{ number_format($payroll->basic_salary, 2) }} {{ $payroll->currency }}</td>
                    <td>{{ $payroll->no_of_days }}</td>
                    <td class="amount-cell">{{ number_format($payroll->transport_allowance, 2) }} {{ $payroll->currency }}</td>
                    <td class="amount-cell">{{ number_format($payroll->taxable_transport_allowance, 2) }} {{ $payroll->currency }}</td>
                    <td class="amount-cell">{{ number_format($payroll->position_allowance, 2) }} {{ $payroll->currency }}</td>
                    <td class="amount-cell">{{ number_format($payroll->total_taxable_earnings, 2) }} {{ $payroll->currency }}</td>
                    <td class="amount-cell">{{ number_format($payroll->income_tax, 2) }} {{ $payroll->currency }}</td>
                    <td class="amount-cell">{{ number_format($payroll->pension_fund_7, 2) }} {{ $payroll->currency }}</td>
                    <td class="amount-cell">{{ number_format($payroll->pension_fund_11, 2) }} {{ $payroll->currency }}</td>
                    <td class="amount-cell">{{ number_format($payroll->salary_advance, 2) }} {{ $payroll->currency }}</td>
                    <td class="amount-net">{{ number_format($payroll->net_payment, 2) }} {{ $payroll->currency }}</td>
                    <td>{{ optional(\App\Models\User::find($payroll->prepared_by))->full_name ?? 'N/A' }}</td>
                    <td><span class="status-badge status-{{ $payroll->status }}">{{ ucfirst($payroll->status) }}</span></td>
                    <td>{{ optional(\App\Models\User::find($payroll->approved_by))->full_name ?? 'N/A' }}</td>
                    <td>{{ $payroll->manager_comment ?? 'N/A' }}</td>
                    <td>{{ $payroll->company_country }}</td>
                    <td>
                        <div class="action-group">
                            @if ($payroll->status !== 'approved')
                                <a href="{{ route('finance.payrolls.edit', $payroll) }}" class="action-btn edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('finance.payrolls.destroy', $payroll) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this payroll record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <button class="action-btn edit" disabled title="Cannot edit approved payrolls">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn delete" disabled title="Cannot delete approved payrolls">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif

                            <a href="{{ route('finance.payrolls.downloadPdf', $payroll) }}" class="action-btn pdf" title="Download PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="21">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p class="mb-0">No payroll records found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection