@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 col-lg-2 bg-dark text-white sidebar position-fixed p-0 m-0"
                style="height: 100vh; z-index: 1000;">
                <div class="position-sticky">
                    <div class="scrollable-links"
                        style="max-height: calc(100vh - 130px); overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                        <ul class="nav flex-column">
                            <li class="nav-item mt-5">
                                <h5 class="sidebar-title text-center mt-5 mb-4"
                                    style="font-size: 1.5rem; color: #ffffff; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">
                                    Finance Menu</h5>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('leave-requests.index') }}">
                                    <i class="bi bi-card-list"></i>
                                    <span class="link-text">Leave Requests</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('payment-requests.index') }}">
                                    <i class="bi bi-cash"></i>
                                    <span class="link-text">Payment Requests</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('purchase-requests.index') }}">
                                    <i class="bi bi-cart"></i>
                                    <span class="link-text">Purchase Requests</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('weekly-plan.index') }}">
                                    <i class="bi bi-calendar-week"></i>
                                    <span class="link-text">Weekly Plans</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('weekly-reports.index') }}">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span class="link-text">Weekly Reports</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('display.index') }}">
                                    <i class="fas fa-box"></i>
                                    <span class="link-text">View Inventory Management</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('stock_requisitions.index') }}">
                                    <i class="bi bi-file-earmark-text"></i> Request Stock
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('petty_cash_requests.index') }}">
                                    <i class="bi bi-file-earmark-text"></i> Request Petty Cash
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('finance.budgets.index') }}">
                                    <i class="bi bi-wallet"></i>
                                    <span class="link-text">Budget Forecasting</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('finance.payrolls.index') }}">
                                    <i class="bi bi-people"></i>
                                    <span class="link-text">Payroll Management</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('display.purchase') }}">
                                    <i class="bi bi-cart"></i> View Purchase Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('display.payment') }}">
                                    <i class="bi bi-cart"></i> View Payment Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('display.petty_cash') }}">
                                    <i class="bi bi-file-earmark-text"></i> View Petty Cash Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('display.finance-pending') }}">
                                    <i class="bi bi-file-earmark-text"></i> Purchase Pending Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('display.payment-pending') }}">
                                    <i class="bi bi-file-earmark-text"></i> Payment Pending Requests
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('display.pending_petty_cash') }}">
                                    <i class="bi bi-file-earmark-text"></i> Pending Petty Cash Request
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <style>
                .scrollable-links {
                    scrollbar-width: none;
                    /* For Firefox */
                    -ms-overflow-style: none;
                    /* For IE and Edge */
                }

                .scrollable-links::-webkit-scrollbar {
                    display: none;
                    /* For Chrome, Safari, and Opera */
                }

                .nav-link {
                    display: flex;
                    align-items: center;
                    padding: 10px 15px;
                    transition: background-color 0.3s ease, transform 0.3s ease;
                }

                .nav-link i {
                    margin-right: 10px;
                    /* Space between icon and text */
                    transition: transform 0.3s ease;
                    /* Animation for icon */
                }

                .nav-link:hover {
                    background-color: rgba(255, 255, 255, 0.1);
                    /* Highlight on hover */
                    transform: scale(1.02);
                    /* Slightly scale up on hover */
                }

                .nav-link:hover i {
                    transform: rotate(360deg);
                    /* Rotate icon on hover */
                }

                .link-text {
                    opacity: 1;
                    /* Default visibility */
                    transition: opacity 0.3s ease;
                    /* Fade effect for text */
                }

                .nav-link:hover .link-text {
                    opacity: 1;
                    /* Ensure text is visible on hover */
                }
            </style>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4" style="margin-left: 0;">
                <!-- Dashboard Header -->
                <h1 class="mb-4 text-center mt-5 animate__animated animate__fadeInDown"
                    style="color: #4A90E2; font-size: 2.5rem;">
                    <span>Finance</span> <span>Dashboard</span>
                </h1>
                <div class="text-center mb-4" style="width: 100%; max-width: 600px; margin: 0 auto;">
                    <div class="card text-bg-success shadow-lg animated-card"
                        style="animation: slideIn 0.5s ease forwards; background: #28a745; border: none;">
                        <div class="card-body">
                            <h5 class="card-title" style="color: white; font-weight: bold; display: inline-block;">Welcome
                                to {{ Auth::user()->company->company_name }}</h5>
                            <button class="btn btn-light float-end" onclick="alert('Explore more!')"
                                style="transition: background-color 0.3s;">
                                <i class="fas fa-info-circle"></i> Info
                            </button>
                        </div>
                    </div>
                </div>

                <style>
                    /* Card Animation */
                    .animated-card {
                        animation-name: bounceIn;
                        animation-duration: 0.5s;
                    }

                    @keyframes bounceIn {
                        0% {
                            transform: scale(0);
                            opacity: 0;
                        }

                        50% {
                            transform: scale(1.05);
                        }

                        100% {
                            transform: scale(1);
                            opacity: 1;
                        }
                    }

                    .nav-link {
                        display: flex;
                        align-items: center;
                        padding: 10px 15px;
                        transition: background-color 0.3s ease, transform 0.3s ease;
                    }

                    .nav-link i {
                        margin-right: 10px;
                        transition: transform 0.3s ease;
                    }

                    .nav-link:hover {
                        background-color: rgba(255, 255, 255, 0.1);
                        transform: scale(1.02);
                    }

                    .nav-link:hover i {
                        transform: rotate(360deg);
                    }

                    /* Sidebar Toggle Button */
                    .sidebar-toggle {
                        display: none;
                        position: fixed;
                        top: 60px;
                        left: 15px;
                        z-index: 1100;
                    }

                    .sidebar {
                        background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQARDFQTdFiAwH8zVOpv6JWkWak9AUqMj1hOw&s');
                        background-size: cover;
                        /* Ensure the image covers the entire sidebar */
                    }

                    @media (min-width: 768px) {
                        .sidebar {
                            left: 0 !important;
                        }
                    }

                    /* Mobile Styles */
                    @media (max-width: 768px) {
                        .sidebar-toggle {
                            display: block;
                        }

                        .sidebar {
                            position: absolute;
                            width: 250px;
                            height: 100vh;
                            left: -250px;
                            top: 30px;
                            transition: left 0.3s ease;
                            z-index: 1000;
                        }

                        .sidebar.show {
                            left: 0;
                        }

                        .sidebar-toggle {
                            display: block;
                        }

                        main {
                            margin-left: 0;
                        }
                    }
                </style>


                <!-- Sidebar Toggle Button -->
                <button class="btn btn-primary sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Analytics Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>All Pending Requests Overview</h5>
                        <canvas id="pendingRequestsChart" class="chart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Leave Requests Overview</h5>
                        <canvas id="leaveRequestsChart" class="chart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Payment Requests Overview</h5>
                        <canvas id="paymentRequestsChart" class="chart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Purchase Requests Overview</h5>
                        <canvas id="purchaseRequestsChart" class="chart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Weekly Plans Overview</h5>
                        <canvas id="weeklyPlansChart" class="chart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Weekly Reports Overview</h5>
                        <canvas id="weeklyReportsChart" class="chart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Inventory Management Overview</h5>
                        <canvas id="inventoryChart" class="chart"></canvas>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // Pending Requests Chart
                    const pendingRequestsData = {
                        labels: ['Leave Requests', 'Payment Requests', 'Purchase Requests', 'Weekly Plans', 'Weekly Reports'],
                        datasets: [{
                            label: 'Pending Requests',
                            data: [
                                {{ $pendingCounts['leave_requests'] }},
                                {{ $pendingCounts['payment_requests'] }},
                                {{ $pendingCounts['purchase_requests'] }},
                                {{ $pendingCounts['weekly_plans'] }},
                                {{ $pendingCounts['weekly_reports'] }}
                            ],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(153, 102, 255, 0.8)'
                            ],
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                        }]
                    };

                    // Prepare data for the charts
                    const leaveRequestsData = {
                        labels: ['Approved', 'Pending', 'Rejected'],
                        datasets: [{
                            label: 'Leave Requests',
                            data: [
                                {{ $leaveCounts['approved'] ?? 0 }},
                                {{ $leaveCounts['pending'] ?? 0 }},
                                {{ $leaveCounts['rejected'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                        }]
                    };

                    const paymentRequestsData = {
                        labels: ['Approved', 'Pending', 'Rejected'],
                        datasets: [{
                            label: 'Payment Requests',
                            data: [
                                {{ $paymentCounts['approved'] ?? 0 }},
                                {{ $paymentCounts['pending'] ?? 0 }},
                                {{ $paymentCounts['rejected'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(255, 159, 64, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                        }]
                    };

                    const purchaseRequestsData = {
                        labels: ['Approved', 'Pending', 'Rejected'],
                        datasets: [{
                            label: 'Purchase Requests',
                            data: [
                                {{ $purchaseCounts['approved'] ?? 0 }},
                                {{ $purchaseCounts['pending'] ?? 0 }},
                                {{ $purchaseCounts['rejected'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                        }]
                    };

                    const weeklyPlansData = {
                        labels: Object.keys({!! json_encode($planCounts) !!}),
                        datasets: [{
                            label: 'Weekly Plans',
                            data: Object.values({!! json_encode($planCounts) !!}),
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.8)',
                            ],
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                        }]
                    };

                    const weeklyReportsData = {
                        labels: Object.keys({!! json_encode($reportCounts) !!}),
                        datasets: [{
                            label: 'Weekly Reports',
                            data: Object.values({!! json_encode($reportCounts) !!}),
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                            ],
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                        }]
                    };

                    // Create Charts
                    new Chart(document.getElementById('pendingRequestsChart').getContext('2d'), {
                        type: 'bar',
                        data: pendingRequestsData,
                    });

                    new Chart(document.getElementById('leaveRequestsChart').getContext('2d'), {
                        type: 'pie',
                        data: leaveRequestsData,
                    });

                    new Chart(document.getElementById('paymentRequestsChart').getContext('2d'), {
                        type: 'bar',
                        data: paymentRequestsData,
                    });

                    new Chart(document.getElementById('purchaseRequestsChart').getContext('2d'), {
                        type: 'bar',
                        data: purchaseRequestsData,
                    });

                    new Chart(document.getElementById('weeklyPlansChart').getContext('2d'), {
                        type: 'bar',
                        data: weeklyPlansData,
                    });

                    new Chart(document.getElementById('weeklyReportsChart').getContext('2d'), {
                        type: 'bar',
                        data: weeklyReportsData,
                    });
                    // Create Inventory Chart
                    const ctxInventory = document.getElementById('inventoryChart').getContext('2d');
                    new Chart(ctxInventory, {
                        type: 'bar',
                        data: {
                            labels: @json($inventories->pluck('item_name')),
                            datasets: [{
                                label: 'Inventory Quantity',
                                data: @json($inventories->pluck('quantity')),
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                    'rgba(255, 159, 64, 0.8)'
                                ],
                                borderColor: 'rgba(0, 0, 0, 0.2)',
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Toggle sidebar function
                    function toggleSidebar() {
                        const sidebar = document.querySelector('.sidebar');
                        sidebar.classList.toggle('show');
                    }
                </script>

                <style>
                    .chart {
                        max-width: 100%;
                        height: auto;
                    }

                    .animated-card {
                        animation-name: fadeIn;
                        animation-duration: 0.5s;
                    }

                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                        }

                        to {
                            opacity: 1;
                        }
                    }

                    /* Cursor effect */
                    .chart {
                        cursor: pointer;
                    }

                    .chart:hover {
                        transform: scale(1.02);
                        transition: transform 0.2s;
                    }
                </style>


            </main>
        </div>
    </div>
@endsection
