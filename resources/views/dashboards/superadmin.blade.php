@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
         <!-- Sidebar -->
<nav class="col-2 bg-dark text-white sidebar position-fixed pr-30" style="height: 100vh;">
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
<h1 class="mb-4 text-center mt-5 animate__animated animate__fadeInDown" style="color: #4A90E2; font-size: 2.5rem;">
    <span>Superadmin</span> <span>Dashboard</span>
</h1>
            <div class="row mb-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card text-bg-primary mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Companies</h5>
                            <p class="card-text">{{ $totalCompanies }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card text-bg-success mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Admins</h5>
                            <p class="card-text">{{ $totalAdmins }}</p>
                        </div>
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
            
                /* Button Hover Effect */
                .btn-light:hover {
                    background-color: #f8f9fa;
                    color: #343a40;
                    transform: scale(1.05);
                    transition: transform 0.2s;
                }
            
                /* Responsive Text */
                h1 {
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
                }
            </style>
            
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
                data: {!! json_encode($recentCompanies->pluck('registered_at')->map(function($date) { return $date->format('Y'); })) !!},
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

    // Admins Chart (assume similar structure)
    var ctxAdmins = document.getElementById('adminChart').getContext('2d');
    var adminChart = new Chart(ctxAdmins, {
        type: 'bar',
        data: {
            labels: {!! json_encode($admins->pluck('full_name')) !!}, // Example for admin names
            datasets: [{
                label: 'Admins Registered',
                data: {!! json_encode($admins->pluck('created_at')->map(function($date) { return $date->format('Y'); })) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.8)', // Different color for admins
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

        body {
        padding-top: 56px; /* Adjusts for the height of the fixed navbar */
    }

    .sidebar {
        position: fixed; /* Fixes the sidebar in place */
        top: 56px; /* Pushes the sidebar below the navbar */
        bottom: 0; /* Makes the sidebar full height */
        overflow-y: auto; /* Enables scrolling if needed */
    }
    </style>
        </main>
    </div>
</div>
@endsection