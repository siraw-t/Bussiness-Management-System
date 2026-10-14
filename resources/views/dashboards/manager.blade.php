@extends('layouts.app')

@section('content')
<style>
    .dash-layout { display: flex; gap: 24px; min-height: calc(100vh - var(--navbar-height) - 48px); }
    .dash-sidebar {
        width: 260px; flex-shrink: 0;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        border-radius: var(--radius-lg); padding: 24px 16px;
        color: #cbd5e1; position: sticky;
        top: calc(var(--navbar-height) + 24px);
        height: fit-content; max-height: calc(100vh - var(--navbar-height) - 48px);
        overflow-y: auto; box-shadow: var(--shadow-lg);
    }
    .dash-sidebar .side-title { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.12em; color: #64748b; padding: 0 12px 12px; font-weight: 700; }
    .dash-sidebar .side-nav { list-style: none; padding: 0; margin: 0; }
    .dash-sidebar .side-nav a {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 14px; border-radius: 12px;
        color: #cbd5e1; font-weight: 500; font-size: 0.85rem;
        margin-bottom: 3px; transition: var(--transition);
    }
    .dash-sidebar .side-nav a:hover { background: rgba(99,102,241,0.15); color: #fff; transform: translateX(2px); }
    .dash-sidebar .side-nav a.active { background: linear-gradient(135deg, var(--primary), var(--accent)); color: #fff; box-shadow: 0 8px 20px rgba(99,102,241,0.35); }
    .dash-sidebar .side-nav i { font-size: 1rem; flex-shrink: 0; }
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
        background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        border-radius: var(--radius-lg); padding: 26px 30px; color: #fff;
        margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
        box-shadow: 0 18px 40px rgba(139,92,246,0.28);
        position: relative; overflow: hidden;
    }
    .welcome-banner::after { content: ''; position: absolute; top: -40px; right: -40px; width: 180px; height: 180px; border-radius: 50%; background: rgba(255,255,255,0.12); }
    .welcome-banner h2 { font-size: 1.2rem; font-weight: 700; margin: 0 0 4px; position: relative; z-index: 1; }
    .welcome-banner p { margin: 0; font-size: 0.86rem; opacity: 0.9; position: relative; z-index: 1; }
    .chart-card {
        background: var(--bg-card); border-radius: var(--radius-lg);
        padding: 20px 22px; border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm); height: 100%; transition: var(--transition);
    }
    .chart-card:hover { box-shadow: var(--shadow); transform: translateY(-2px); }
    .chart-card h5 { font-size: 0.9rem; font-weight: 700; color: var(--text-primary); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
    .chart-card canvas { max-height: 260px; }
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
        <div class="side-title">Manager Menu</div>
        <ul class="side-nav">
            <li><a href="{{ route('display.leave') }}"><i class="bi bi-card-list"></i> View Leave Requests</a></li>
            <li><a href="{{ route('display.purchase') }}"><i class="bi bi-cart"></i> View Purchase Requests</a></li>
            <li><a href="{{ route('display.payment') }}"><i class="bi bi-cash"></i> View Payment Requests</a></li>
            <li><a href="{{ route('display.plan') }}"><i class="bi bi-calendar-week"></i> View Weekly Plans</a></li>
            <li><a href="{{ route('display.report') }}"><i class="bi bi-file-earmark-text"></i> View Weekly Reports</a></li>
            <li><a href="{{ route('display.index') }}"><i class="fas fa-box"></i> View Inventory</a></li>
            <li><a href="{{ route('display.budget') }}"><i class="bi bi-wallet"></i> View Budget Forecasting</a></li>
            <li><a href="{{ route('display.payroll') }}"><i class="bi bi-people"></i> View Payroll</a></li>
            <li><a href="{{ route('display.stock-requisition') }}"><i class="bi bi-box-seam"></i> View Stock Requisitions</a></li>
            <li><a href="{{ route('display.petty_cash') }}"><i class="bi bi-wallet2"></i> View Petty Cash</a></li>
            <li><a href="{{ route('display.pending') }}"><i class="bi bi-hourglass-split"></i> Pending Requests</a></li>
            <li><a href="{{ route('documents.index') }}"><i class="bi bi-archive"></i> Archived Documents</a></li>
        </ul>
    </nav>

    <main class="dash-main">
        <div class="dash-header">
            <div class="d-flex align-items-center gap-3">
                <button class="dash-sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar"><i class="bi bi-list"></i></button>
                <h1>Manager Dashboard</h1>
            </div>
        </div>

        <div class="welcome-banner">
            <div>
                <h2>Welcome to {{ Auth::user()->company->company_name }}</h2>
                <p>Oversee and approve team activity</p>
            </div>
            <button class="btn btn-light" onclick="alert('Explore more!')">
                <i class="fas fa-info-circle me-1"></i> Info
            </button>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-6"><div class="chart-card"><h5>All Pending Requests</h5><canvas id="pendingRequestsChart"></canvas></div></div>
            <div class="col-12 col-lg-6"><div class="chart-card"><h5>Leave Requests</h5><canvas id="leaveRequestsChart"></canvas></div></div>
            <div class="col-12 col-lg-6"><div class="chart-card"><h5>Payment Requests</h5><canvas id="paymentRequestsChart"></canvas></div></div>
            <div class="col-12 col-lg-6"><div class="chart-card"><h5>Purchase Requests</h5><canvas id="purchaseRequestsChart"></canvas></div></div>
            <div class="col-12 col-lg-6"><div class="chart-card"><h5>Weekly Plans</h5><canvas id="weeklyPlansChart"></canvas></div></div>
            <div class="col-12 col-lg-6"><div class="chart-card"><h5>Weekly Reports</h5><canvas id="weeklyReportsChart"></canvas></div></div>
            <div class="col-12 col-lg-6"><div class="chart-card"><h5>Inventory Management</h5><canvas id="inventoryChart"></canvas></div></div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748b';
    const noLegend = { plugins: { legend: { display: false } } };

    new Chart(document.getElementById('pendingRequestsChart').getContext('2d'), {
        type: 'bar',
        data: { labels: ['Leave', 'Payment', 'Purchase', 'Plans', 'Reports'],
            datasets: [{ label: 'Pending',
                data: [{{ $pendingCounts['leave_requests'] }},{{ $pendingCounts['payment_requests'] }},{{ $pendingCounts['purchase_requests'] }},{{ $pendingCounts['weekly_plans'] }},{{ $pendingCounts['weekly_reports'] }}],
                backgroundColor: ['rgba(99,102,241,0.75)','rgba(16,185,129,0.75)','rgba(245,158,11,0.75)','rgba(6,182,212,0.75)','rgba(139,92,246,0.75)'],
                borderWidth: 0, borderRadius: 8, maxBarThickness: 44 }] },
        options: { responsive: true, maintainAspectRatio: true, ...noLegend,
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } }, x: { grid: { display: false } } } }
    });

    new Chart(document.getElementById('leaveRequestsChart').getContext('2d'), {
        type: 'pie',
        data: { labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{ data: [{{ $leaveCounts['approved'] ?? 0 }},{{ $leaveCounts['pending'] ?? 0 }},{{ $leaveCounts['rejected'] ?? 0 }}],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0 }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true } } } }
    });

    new Chart(document.getElementById('paymentRequestsChart').getContext('2d'), {
        type: 'bar',
        data: { labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{ data: [{{ $paymentCounts['approved'] ?? 0 }},{{ $paymentCounts['pending'] ?? 0 }},{{ $paymentCounts['rejected'] ?? 0 }}],
                backgroundColor: ['#8b5cf6', '#f59e0b', '#ef4444'], borderWidth: 0, borderRadius: 8, maxBarThickness: 44 }] },
        options: { responsive: true, maintainAspectRatio: true, ...noLegend,
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } }, x: { grid: { display: false } } } }
    });

    new Chart(document.getElementById('purchaseRequestsChart').getContext('2d'), {
        type: 'bar',
        data: { labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{ data: [{{ $purchaseCounts['approved'] ?? 0 }},{{ $purchaseCounts['pending'] ?? 0 }},{{ $purchaseCounts['rejected'] ?? 0 }}],
                backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444'], borderWidth: 0, borderRadius: 8, maxBarThickness: 44 }] },
        options: { responsive: true, maintainAspectRatio: true, ...noLegend,
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } }, x: { grid: { display: false } } } }
    });

    new Chart(document.getElementById('weeklyPlansChart').getContext('2d'), {
        type: 'bar',
        data: { labels: Object.keys({!! json_encode($planCounts) !!}),
            datasets: [{ label: 'Weekly Plans', data: Object.values({!! json_encode($planCounts) !!}),
                backgroundColor: 'rgba(6,182,212,0.75)', borderWidth: 0, borderRadius: 8, maxBarThickness: 44 }] },
        options: { responsive: true, maintainAspectRatio: true, ...noLegend,
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } }, x: { grid: { display: false } } } }
    });

    new Chart(document.getElementById('weeklyReportsChart').getContext('2d'), {
        type: 'bar',
        data: { labels: Object.keys({!! json_encode($reportCounts) !!}),
            datasets: [{ label: 'Weekly Reports', data: Object.values({!! json_encode($reportCounts) !!}),
                backgroundColor: 'rgba(139,92,246,0.75)', borderWidth: 0, borderRadius: 8, maxBarThickness: 44 }] },
        options: { responsive: true, maintainAspectRatio: true, ...noLegend,
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } }, x: { grid: { display: false } } } }
    });

    new Chart(document.getElementById('inventoryChart').getContext('2d'), {
        type: 'bar',
        data: { labels: @json($inventories->pluck('item_name')),
            datasets: [{ label: 'Inventory Quantity', data: @json($inventories->pluck('quantity')),
                backgroundColor: 'rgba(99,102,241,0.75)', borderWidth: 0, borderRadius: 8, maxBarThickness: 44 }] },
        options: { responsive: true, maintainAspectRatio: true, ...noLegend,
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' } }, x: { grid: { display: false } } } }
    });

    function toggleSidebar() {
        document.getElementById('dashSidebar').classList.toggle('show');
        document.getElementById('sidebarBackdrop').classList.toggle('show');
    }
</script>
@endsection