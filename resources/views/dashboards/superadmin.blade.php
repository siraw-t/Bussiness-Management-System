@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <nav class="col-md-2 col-lg-2 bg-dark text-white sidebar position-fixed" style="height: 100vh; z-index: 1000;">
                <div class="position-sticky">
                    <h5 class="sidebar-title text-center mt-5">Superadmin Menu</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('display.admin') }}">
                                <i class="bi bi-person-circle"></i> Admins
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('companies.index') }}">
                                <i class="bi bi-building"></i> Companies
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4" style="margin-left: 0;">
                <!-- Dashboard Header -->
                <h1 class="mb-4 text-center mt-5 animate__animated animate__fadeInDown"
                    style="color: #4A90E2; font-size: 2.5rem;">
                    <span>Superadmin</span> <span>Dashboard</span>
                </h1>

                <div class="row mb-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card text-bg-primary mb-4 card-animate">
                            <div class="card-body d-flex align-items-center">
                                <i class="fas fa-building fa-2x me-3"></i>
                                <div>
                                    <h5 class="card-title">Total Companies</h5>
                                    <p class="card-text" id="total-companies">{{ $totalCompanies }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card text-bg-success mb-4 card-animate">
                            <div class="card-body d-flex align-items-center">
                                <i class="fas fa-user-shield fa-2x me-3"></i>
                                <div>
                                    <h5 class="card-title">Total Admins</h5>
                                    <p class="card-text" id="total-admins">{{ $totalAdmins }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Toggle Button -->
                <button class="btn btn-primary sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>

                <!-- Analytics Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Companies Overview</h5>
                        <canvas id="companyChart" class="chart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Admins Overview</h5>
                        <canvas id="adminChart" class="chart"></canvas>
                    </div>
                </div>

                <!-- Include Chart.js from CDN -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    // Companies Chart
                    var ctxCompanies = document.getElementById('companyChart').getContext('2d');
                    var companyChart = new Chart(ctxCompanies, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($recentCompanies->pluck('company_name')) !!},
                            datasets: [{
                                label: 'Companies Registered',
                                data: {!! json_encode(
                                    $recentCompanies->pluck('registered_at')->map(function ($date) {
                                        return $date->format('Y');
                                    }),
                                ) !!},
                                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
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

                    // Admins Chart
                    var ctxAdmins = document.getElementById('adminChart').getContext('2d');
                    var adminChart = new Chart(ctxAdmins, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($admins->pluck('full_name')) !!},
                            datasets: [{
                                label: 'Admins Registered',
                                data: {!! json_encode(
                                    $admins->pluck('created_at')->map(function ($date) {
                                        return $date->format('Y');
                                    }),
                                ) !!},
                                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
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

                    // Number Animation
                    function animateNumber(elementId, start, end, duration) {
                        let startTimestamp = null;

                        const step = (timestamp) => {
                            if (!startTimestamp) startTimestamp = timestamp;
                            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                            const currentValue = Math.floor(progress * (end - start) + start);
                            document.getElementById(elementId).textContent = currentValue;
                            if (progress < 1) {
                                window.requestAnimationFrame(step);
                            }
                        };

                        window.requestAnimationFrame(step);
                    }

                    // Start animations
                    animateNumber('total-companies', 0, {{ $totalCompanies }}, 2000);
                    animateNumber('total-admins', 0, {{ $totalAdmins }}, 2000);

                    // Toggle sidebar function
                    function toggleSidebar() {
                        const sidebar = document.querySelector('.sidebar');
                        sidebar.classList.toggle('show');
                    }
                </script>

                <!-- Styles -->
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
                            transition: left 0.3s ease;
                            z-index: 1000;
                        }

                        .sidebar.show {
                            left: 0;
                        }

                        main {
                            margin-left: 0;
                        }
                    }
                </style>
            </main>
        </div>
    </div>
@endsection
