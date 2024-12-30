@extends('layouts.app')

@section('content')
<style>
    /* General Styles */
    body {
        background-color: #f8f9fa; /* Light background for contrast */
        font-family: 'Arial', sans-serif;
    }

   

    .card {
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 20px; /* Space between cards */
        padding: 15px; /* Padding inside cards */
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.2);
    }

    .nav-link {
        color: black; /* Change link color to black */
        padding: 10px 15px; /* Padding around the link */
        display: inline-block; /* Make link look like a button */
        transition: color 0.3s, background-color 0.3s;
        margin-top: 20px; /* Margin above the link */
        border-radius: 5px; /* Rounded corners */
    }

 */

    /* Chart Styles */
    #userChart {
        max-height: 300px;
        margin-top: 20px; /* Space above the chart */
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 15px; /* Spacing for smaller devices */
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-2 bg-dark text-white sidebar position-fixed" style="height: 100vh;">
            <div class="position-sticky">
                <h5 class="sidebar-title text-center mt-5">Admin Menu</h5>
               
                    <a class="nav-link text-white" href="{{ route('display.users') }}">
                        <i class="bi bi-person-circle"></i> Users
                    </a>
              
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-4" style="margin-left: 0;">
            <h1 class="mb-4 text-center mt-5" style="color: #4A90E2; font-size: 2.5rem;">
                <span>Admin</span> <span>Dashboard</span>
            </h1>
            <div class="card text-bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Welcome to {{ Auth::user()->company->company_name }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="card text-bg-success shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>
               

                <div class="col-md-6">
                    <h5>Users Overview</h5>
                    <canvas id="userChart" class="chart"></canvas>
                </div>
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
</script>
@endsection