@extends('layouts.app')

@section('content')
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
            font-size: 1.2rem;
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
            font-size: 1.5rem;
            width: 45px;
            height: 45px;
            line-height: 45px;
            text-align: center;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar-toggle i {
            font-size: 1.5rem;
        }

        .sidebar {
            background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRLIGP_-tS-u1qrCfysubtykcG_guPBj4CDLw&s');
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
                width: 220px;
                height: 100vh;
                left: -220px;
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
                padding: 15px;
            }

            /* Improve Card Responsiveness */
            .card {
                font-size: 0.9rem;
                padding: 10px;
            }

            .card .card-title {
                font-size: 1.2rem;
            }

            .card .card-text {
                font-size: 1rem;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 col-lg-2 bg-dark text-white sidebar position-fixed p-0 m-0"
                style="height: 100vh; z-index: 1000;">
                <div class="position-sticky">
                    <h5 class="sidebar-title text-center mt-5">Admin Menu</h5>
                    <a class="nav-link text-white" href="{{ route('display.users') }}">
                        <i class="bi bi-person-circle"></i> Users
                    </a>
                    <a class="nav-link text-white" href="{{ route('display.forms') }}">
                        <i class="bi bi-file-earmark-text"></i> Requested Form Templates
                    </a>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4" style="margin-left: 0;">
                <h1 class="mb-4 text-center mt-5" style="color: #4A90E2; font-size: 2.5rem;">
                    <span>Admin</span> <span>Dashboard</span>
                </h1>

                <!-- Welcome Card -->
                <div class="card text-bg-primary shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Welcome to {{ Auth::user()->company->company_name }}</h5>
                    </div>
                </div>

                <!-- Cards Row -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <div class="col">
                        <div class="card text-bg-success shadow-sm mb-4">
                            <div class="card-body">
                                <i class="fas fa-user-shield fa-2x me-3"></i>
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text">{{ $totalUsers }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Toggle Button -->
                <button class="btn btn-primary sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Users Chart -->
                <div class="col-md-12">
                    <h5>Users Overview</h5>
                    <canvas id="userChart" class="chart"></canvas>
                </div>
            </main>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('userChart').getContext('2d');
        const userChart = new Chart(ctx, {
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
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
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

        // Toggle sidebar function
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }
    </script>
@endsection
