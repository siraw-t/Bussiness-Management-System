@extends('layouts.app')

@section('content')
<style>
    .page-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 22px;
    }
    .page-head h1 {
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0;
        color: var(--text-primary);
    }
    .page-head p { color: var(--text-secondary); margin: 4px 0 0; font-size: 0.88rem; }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        font-weight: 600;
        font-size: 0.88rem;
        border: none;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        transition: var(--transition);
    }
    .btn-add:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99, 102, 241, 0.45); }

    .table-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    .table-modern { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; }
    .table-modern thead th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        color: var(--text-secondary);
        font-weight: 700;
        font-size: 0.73rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 14px 18px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }
    [data-bs-theme="dark"] .table-modern thead th { background: #0b1120; }
    .table-modern tbody td {
        padding: 16px 18px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.88rem;
        color: var(--text-primary);
        vertical-align: middle;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .company-cell { display: flex; align-items: center; gap: 12px; }
    .company-logo {
        width: 42px; height: 42px; border-radius: 12px;
        object-fit: cover;
        border: 2px solid var(--border-color);
        background: var(--bg-body);
        flex-shrink: 0;
    }
    .company-logo-placeholder {
        width: 42px; height: 42px; border-radius: 12px;
        background: linear-gradient(135deg, var(--primary-soft), #e0e7ff);
        color: var(--primary-dark);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1rem; flex-shrink: 0;
        border: 2px solid var(--border-color);
    }
    .company-cell .c-name { font-weight: 600; color: var(--text-primary); }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: var(--transition);
    }
    .action-btn.edit { background: #fef3c7; color: #92400e; }
    .action-btn.edit:hover { background: #fde68a; transform: translateY(-1px); }
    .action-btn.delete { background: #fee2e2; color: #991b1b; }
    .action-btn.delete:hover { background: #fecaca; transform: translateY(-1px); }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: var(--text-secondary);
    }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 900px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 900px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Companies</h1>
        <p>Manage all registered companies and their administrators</p>
    </div>
    <a href="{{ route('companies.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Add New Company
    </a>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Company</th>
                <th>Country</th>
                <th>City</th>
                <th>Contact</th>
                <th>Admin</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td>
                        <div class="company-cell">
                            @if ($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="company-logo">
                            @else
                                <div class="company-logo-placeholder">{{ strtoupper(substr($company->company_name, 0, 1)) }}</div>
                            @endif
                            <div>
                                <div class="c-name">{{ $company->company_name }}</div>
                                <div class="text-muted small">ID: #{{ $company->company_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $company->country }}</td>
                    <td>{{ $company->city }}</td>
                    <td>
                        <div>{{ $company->contact_email }}</div>
                        <div class="text-muted small">{{ $company->contact_phone }}</div>
                    </td>
                    <td>{{ optional($company->admin)->full_name ?? '—' }}</td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('companies.edit', ['company' => $company->company_id]) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('companies.destroy', ['company' => $company->company_id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this company?');">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection