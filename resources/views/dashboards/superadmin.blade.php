@extends('layouts.app')

@section('content')
<style>
    /* ============ SUPERADMIN DASHBOARD ============ */
    .dash-layout { display: flex; gap: 24px; min-height: calc(100vh - var(--navbar-height) - 48px); }

    .dash-sidebar {
        width: 260px;
        flex-shrink: 0;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        border-radius: var(--radius-lg);
        padding: 24px 16px;
        color: #cbd5e1;
        position: sticky;
        top: calc(var(--navbar-height) + 24px);
        height: fit-content;
        max-height: calc(100vh - var(--navbar-height) - 48px);
        overflow-y: auto;
        box-shadow: var(--shadow-lg);
    }
    .dash-sidebar .side-title {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #64748b;
        padding: 0 12px 12px;
        font-weight: 700;
    }
    .dash-sidebar .side-nav { list-style: none; padding: 0; margin: 0; }
    .dash-sidebar .side-nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 12px;
        color: #cbd5e1;
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 4px;
        transition: var(--transition);
    }
    .dash-sidebar .side-nav a:hover {
        background: rgba(99, 102, 241, 0.15);
        color: #fff;
        transform: translateX(2px);
    }
    .dash-sidebar .side-nav a.active {
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
    }
    .dash-sidebar .side-nav i { font-size: 1.05rem; }

    .dash-main { flex: 1; min-width: 0; }

    .dash-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .dash-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 22px 24px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 18px;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }

    .stat-icon {
        width: 56px; height: 56px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    }
    .stat-icon.blue  { background: linear-gradient(135deg, #3b82f6, #6366f1); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #34d399); box-shadow: 0 8px 20px rgba(16,185,129,0.3); }
    .stat-icon.purple{ background: linear-gradient(135deg, #8b5cf6, #a78bfa); box-shadow: 0 8px 20px rgba(139,92,246,0.3); }

    .stat-info h5 {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-secondary);
        font-weight: 600;
        margin: 0 0 6px;
    }
    .stat-info .stat-value {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
    }

    .chart-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 22px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        height: 100%;
        transition: var(--transition);
    }
    .chart-card:hover { box-shadow: var(--shadow); }
    .chart-card h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .chart-card canvas { max-height: 280px; }

    /* Mobile offcanvas sidebar */
    .dash-sidebar-toggle {
        display: none;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        border: none;
        width: 42px; height: 42px;
        border-radius: 12px;
        font-size: 1.15rem;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
        cursor: pointer;
    }

    @media (max-width: 991px) {
        .dash-layout { display: block; }
        .dash-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            max-height: 100vh;
            width: 280px;
            border-radius: 0;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            padding-top: 80px;
        }
        .dash-sidebar.show { transform: translateX(0); }
        .dash-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }
        .dash-sidebar-backdrop.show { display: block; }
        .dash-sidebar-toggle { display: inline-flex; align-items: center; justify-content: center; }
    }
</style>

<div class="dash-sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

<div class="dash-layout">
    <!-- Sidebar -->
    <nav class="dash-sidebar" id="dashSidebar">
        <div class="side-title">Superadmin Menu</div>
        <ul class="side-nav">
            <li>
                <a href="{{ route('display.admin') }}" class="{{ request()->routeIs('display.admin') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i> Admins
                </a>
            </li>
            <li>
                <a href="{{ route('companies.index') }}" class="{{ request()->routeIs('companies.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Companies
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main -->
    <main class="dash-main">
        <div class="dash-header">
            <div class="d-flex align-items-center gap-3">
                <button class="dash-sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h1>Superadmin Dashboard</h1>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-6">
                <div class="stat-card blue">
                    <div class="stat-icon blue"><i class="fas fa-building"></i></div>
                    <div class="stat-info">
                        <h5>Total Companies</h5>
                        <div class="stat-value" id="total-companies">{{ $totalCompanies }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-6">
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-user-shield"></i></div>
                    <div class="stat-info">
                        <h5>Total Admins</h5>
                        <div class="stat-value" id="total-admins">{{ $totalAdmins }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="chart-card">
                    <h5><i class="bi bi-bar-chart-line-fill text-primary"></i> Companies Overview</h5>
                    <canvas id="companyChart"></canvas>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="chart-card">
                    <h5><i class="bi bi-bar-chart-line-fill text-danger"></i> Admins Overview</h5>
                    <canvas id="adminChart"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Companies Chart
    var ctxCompanies = document.getElementById('companyChart').getContext('2d');
    new Chart(ctxCompanies, {
        type: 'bar',
        data: {
            labels: {!! json_encode($recentCompanies->pluck('company_name')) !!},
            datasets: [{
                label: 'Companies Registered',
                data: {!! json_encode($recentCompanies->pluck('registered_at')->map(function ($date) { return $date->format('Y'); })) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.75)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 0,
                borderRadius: 8,
                maxBarThickness: 44,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Admins Chart
    var ctxAdmins = document.getElementById('adminChart').getContext('2d');
    new Chart(ctxAdmins, {
        type: 'bar',
        data: {
            labels: {!! json_encode($admins->pluck('full_name')) !!},
            datasets: [{
                label: 'Admins Registered',
                data: {!! json_encode($admins->pluck('created_at')->map(function ($date) { return $date->format('Y'); })) !!},
                backgroundColor: 'rgba(239, 68, 68, 0.75)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 0,
                borderRadius: 8,
                maxBarThickness: 44,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Number Animation
    function animateNumber(elementId, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const currentValue = Math.floor(progress * (end - start) + start);
            document.getElementById(elementId).textContent = currentValue;
            if (progress < 1) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
    }
    animateNumber('total-companies', 0, {{ $totalCompanies }}, 1800);
    animateNumber('total-admins', 0, {{ $totalAdmins }}, 1800);

    // Toggle sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('dashSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        sidebar.classList.toggle('show');
        backdrop.classList.toggle('show');
    }
</script>
@endsection