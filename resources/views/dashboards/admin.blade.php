@extends('layouts.app')

@section('content')
<style>
    .dash-layout { display: flex; gap: 24px; min-height: calc(100vh - var(--navbar-height) - 48px); }
    .dash-sidebar {
        width: 260px; flex-shrink: 0;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        border-radius: var(--radius-lg);
        padding: 24px 16px; color: #cbd5e1;
        position: sticky; top: calc(var(--navbar-height) + 24px);
        height: fit-content; max-height: calc(100vh - var(--navbar-height) - 48px);
        overflow-y: auto; box-shadow: var(--shadow-lg);
    }
    .dash-sidebar .side-title { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.12em; color: #64748b; padding: 0 12px 12px; font-weight: 700; }
    .dash-sidebar .side-nav { list-style: none; padding: 0; margin: 0; }
    .dash-sidebar .side-nav a {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 14px; border-radius: 12px;
        color: #cbd5e1; font-weight: 500; font-size: 0.88rem;
        margin-bottom: 4px; transition: var(--transition);
    }
    .dash-sidebar .side-nav a:hover { background: rgba(99,102,241,0.15); color: #fff; transform: translateX(2px); }
    .dash-sidebar .side-nav a.active {
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; box-shadow: 0 8px 20px rgba(99,102,241,0.35);
    }
    .dash-sidebar .side-nav i { font-size: 1.05rem; flex-shrink: 0; }
    .dash-main { flex: 1; min-width: 0; }

    .dash-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
    .dash-header h1 {
        font-size: 1.75rem; font-weight: 800; margin: 0;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .dash-sidebar-toggle {
        display: none; background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff; border: none; width: 42px; height: 42px; border-radius: 12px;
        font-size: 1.15rem; box-shadow: 0 8px 20px rgba(99,102,241,0.35); cursor: pointer;
    }

    .welcome-banner {
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        border-radius: var(--radius-lg); padding: 26px 30px; color: #fff;
        margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
        box-shadow: 0 18px 40px rgba(99,102,241,0.28);
        position: relative; overflow: hidden;
    }
    .welcome-banner::after { content: ''; position: absolute; top: -40px; right: -40px; width: 180px; height: 180px; border-radius: 50%; background: rgba(255,255,255,0.12); }
    .welcome-banner h2 { font-size: 1.2rem; font-weight: 700; margin: 0 0 4px; position: relative; z-index: 1; }
    .welcome-banner p { margin: 0; font-size: 0.86rem; opacity: 0.9; position: relative; z-index: 1; }

    .stat-card {
        background: var(--bg-card); border-radius: var(--radius-lg);
        padding: 22px 24px; border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm); transition: var(--transition);
        display: flex; align-items: center; gap: 18px;
        height: 100%; position: relative; overflow: hidden;
    }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--primary), var(--accent)); }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
    .stat-icon {
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: #fff; flex-shrink: 0;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        box-shadow: 0 8px 20px rgba(59,130,246,0.3);
    }
    .stat-info h5 { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-secondary); font-weight: 600; margin: 0 0 6px; }
    .stat-info .stat-value { font-size: 1.85rem; font-weight: 800; color: var(--text-primary); line-height: 1; }

    .chart-card {
        background: var(--bg-card); border-radius: var(--radius-lg);
        padding: 20px 22px; border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm); height: 100%; transition: var(--transition);
    }
    .chart-card:hover { box-shadow: var(--shadow); }
    .chart-card h5 { font-size: 0.92rem; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .chart-card canvas { max-height: 300px; }

    .dash-sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1040; backdrop-filter: blur(2px); }
    .dash-sidebar-backdrop.show { display: block; }

    @media (max-width: 991px) {
        .dash-layout { display: block; }
        .dash-sidebar { position: fixed; left: 0; top: 0; height: 100vh; max-height: 100vh; width: 280px; border-radius: 0; transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); z-index: 1050; padding-top: 80px; }
        .dash-sidebar.show { transform: translateX(0); }
        .dash-sidebar-toggle { display: inline-flex; align-items: center; justify-content: center; }
    }
    @media (max-width: 576px) { .dash-header h1 { font-size: 1.4rem; } }
</style>

<div class="dash-sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

<div class="dash-layout">
    <nav class="dash-sidebar" id="dashSidebar">
        <div class="side-title">Admin Menu</div>
        <ul class="side-nav">
            <li><a href="{{ route('display.users') }}" class="{{ request()->routeIs('display.users') ? 'active' : '' }}"><i class="bi bi-person-circle"></i> Users</a></li>
            <li><a href="{{ route('display.forms') }}" class="{{ request()->routeIs('display.forms') ? 'active' : '' }}"><i class="bi bi-file-earmark-text"></i> Form Templates</a></li>
        </ul>
    </nav>

    <main class="dash-main">
        <div class="dash-header">
            <div class="d-flex align-items-center gap-3">
                <button class="dash-sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar"><i class="bi bi-list"></i></button>
                <h1>Admin Dashboard</h1>
            </div>
        </div>

        <div class="welcome-banner">
            <div>
                <h2>Welcome to {{ Auth::user()->company->company_name }}</h2>
                <p>Manage users and system configuration</p>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
                    <div class="stat-info">
                        <h5>Total Users</h5>
                        <div class="stat-value">{{ $totalUsers }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="chart-card">
                    <h5><i class="bi bi-bar-chart-line-fill text-primary"></i> Users Overview by Role</h5>
                    <canvas id="userChart"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748b';

    new Chart(document.getElementById('userChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Admin', 'Employee', 'Finance', 'Manager', 'Storekeeper'],
            datasets: [{
                label: '# of Users',
                data: [
                    {{ $users->where('role', 'admin')->count() }},
                    {{ $users->where('role', 'employee')->count() }},
                    {{ $users->where('role', 'finance')->count() }},
                    {{ $users->where('role', 'manager')->count() }},
                    {{ $users->where('role', 'storekeeper')->count() }}
                ],
                backgroundColor: ['#6366f1', '#3b82f6', '#f59e0b', '#10b981', '#8b5cf6'],
                borderWidth: 0, borderRadius: 10, maxBarThickness: 60,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } }, x: { grid: { display: false } } }
        }
    });

    function toggleSidebar() {
        document.getElementById('dashSidebar').classList.toggle('show');
        document.getElementById('sidebarBackdrop').classList.toggle('show');
    }
</script>
@endsection