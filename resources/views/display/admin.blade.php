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
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 14px 20px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }
    [data-bs-theme="dark"] .table-modern thead th { background: #0b1120; }
    .table-modern tbody td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.9rem;
        color: var(--text-primary);
        vertical-align: middle;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .user-cell { display: flex; align-items: center; gap: 12px; }
    .user-cell .u-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
    }
    .user-cell .u-name { font-weight: 600; color: var(--text-primary); }
    .user-cell .u-email { font-size: 0.78rem; color: var(--text-secondary); }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .role-superadmin { background: #fef3c7; color: #92400e; }
    .role-admin      { background: #dbeafe; color: #1e40af; }
    .role-manager    { background: #e0e7ff; color: #4338ca; }
    .role-finance    { background: #d1fae5; color: #065f46; }
    .role-employee   { background: #f1f5f9; color: #475569; }
    .role-storekeeper{ background: #fce7f3; color: #9d174d; }
    [data-bs-theme="dark"] .role-badge { filter: brightness(0.85); }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: var(--text-secondary);
    }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 768px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 640px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Admin Users</h1>
        <p>Manage all admin accounts across the system</p>
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
                <tr>
                    <td><span class="text-muted">#{{ $admin->user_id }}</span></td>
                    <td>
                        <div class="user-cell">
                            <div class="u-avatar">{{ strtoupper(substr($admin->full_name ?? 'U', 0, 1)) }}</div>
                            <div>
                                <div class="u-name">{{ $admin->full_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <span class="role-badge role-{{ $admin->role }}">{{ $admin->role }}</span>
                    </td>
                    <td>{{ $admin->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p class="mb-0">No admin users found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection