@extends('layouts.app')

@section('content')
<style>
    .page-head {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .page-head h1 {
        font-size: 1.6rem; font-weight: 800; margin: 0;
        color: var(--text-primary);
    }
    .page-head p { color: var(--text-secondary); margin: 4px 0 0; font-size: 0.88rem; }

    .welcome-banner {
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        border-radius: var(--radius-lg); padding: 26px 30px; color: #fff;
        margin-bottom: 22px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
        box-shadow: 0 18px 40px rgba(99, 102, 241, 0.28);
        position: relative; overflow: hidden;
    }
    .welcome-banner::after {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,0.12);
    }
    .welcome-banner h2 {
        font-size: 1.2rem; font-weight: 700; margin: 0 0 4px;
        position: relative; z-index: 1;
    }
    .welcome-banner p { margin: 0; font-size: 0.86rem; opacity: 0.9; position: relative; z-index: 1; }

    .stat-card {
        background: var(--bg-card); border-radius: var(--radius-lg);
        padding: 22px 24px; border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm); transition: var(--transition);
        display: flex; align-items: center; gap: 18px;
        height: 100%; position: relative; overflow: hidden;
        margin-bottom: 22px;
    }
    .stat-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, #10b981, #34d399);
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
    .stat-icon {
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: #fff; flex-shrink: 0;
        background: linear-gradient(135deg, #10b981, #34d399);
        box-shadow: 0 8px 20px rgba(16,185,129,0.3);
    }
    .stat-info h5 {
        font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--text-secondary); font-weight: 600; margin: 0 0 6px;
    }
    .stat-info .stat-value {
        font-size: 1.85rem; font-weight: 800; color: var(--text-primary); line-height: 1;
    }

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
        padding: 14px 18px; text-align: left;
        border-bottom: 1px solid var(--border-color); white-space: nowrap;
    }
    [data-bs-theme="dark"] .table-modern thead th { background: #0b1120; }
    .table-modern tbody td {
        padding: 16px 18px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.88rem; color: var(--text-primary); vertical-align: middle;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .user-cell { display: flex; align-items: center; gap: 12px; }
    .user-cell .u-avatar {
        width: 42px; height: 42px; border-radius: 50%;
        object-fit: cover; border: 2px solid var(--primary-soft);
        flex-shrink: 0;
    }
    .user-cell .u-avatar-placeholder {
        width: 42px; height: 42px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.9rem; flex-shrink: 0;
    }
    .user-cell .u-name { font-weight: 600; color: var(--text-primary); }
    .user-cell .u-username { font-size: 0.75rem; color: var(--text-secondary); }

    .role-badge {
        display: inline-flex; align-items: center;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
    }
    .role-superadmin { background: #fef3c7; color: #92400e; }
    .role-admin      { background: #dbeafe; color: #1e40af; }
    .role-manager    { background: #e0e7ff; color: #4338ca; }
    .role-finance    { background: #d1fae5; color: #065f46; }
    .role-employee   { background: #f1f5f9; color: #475569; }
    .role-storekeeper{ background: #fce7f3; color: #9d174d; }
    [data-bs-theme="dark"] .role-badge { filter: brightness(0.85); }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 8px 12px; border-radius: 10px;
        font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer;
        transition: var(--transition);
    }
    .action-btn.edit { background: #fef3c7; color: #92400e; }
    .action-btn.edit:hover { background: #fde68a; transform: translateY(-1px); }
    .action-btn.delete { background: #fee2e2; color: #991b1b; }
    .action-btn.delete:hover { background: #fecaca; transform: translateY(-1px); }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 900px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 900px; }
    }
    @media (max-width: 576px) {
        .welcome-banner { padding: 20px; }
        .welcome-banner h2 { font-size: 1.05rem; }
        .page-head h1 { font-size: 1.35rem; }
    }
</style>

<div class="welcome-banner">
    <div>
        <h2><i class="bi bi-building me-1"></i> Welcome to {{ Auth::user()->company->company_name }}</h2>
        <p>{{ Auth::user()->full_name }} · Manage your team</p>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
            <div class="stat-info">
                <h5>Total Users</h5>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
        </div>
    </div>
</div>

<div class="page-head">
    <div>
        <h1>Manage Users</h1>
        <p>Create, edit, and manage users in your company</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-add">
        <i class="bi bi-person-plus"></i> Create User
    </a>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Company ID</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>
                        <div class="user-cell">
                            @if ($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="u-avatar">
                            @else
                                <div class="u-avatar-placeholder">
                                    {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="u-name">{{ $user->full_name }}</div>
                                <div class="u-username">@ {{ $user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td><span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span></td>
                    <td><span class="text-muted">#{{ $user->company_id }}</span></td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('users.edit', $user->user_id) }}" class="action-btn edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete"
                                onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p class="mb-0">No users found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection