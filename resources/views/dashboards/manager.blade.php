@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
              <!-- Sidebar -->
<nav class="col-2 bg-dark text-white sidebar position-fixed pr-30" style="height: 100vh; top: 0; z-index: 1000;">
    <div class="position-sticky">
        <div class="scrollable-links" style="max-height: calc(100vh - 130px); overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
            <ul class="nav flex-column">
                <li class="nav-item mt-5">
                    <h5 class="sidebar-title text-center mt-5 mb-4" style="font-size: 1.5rem; color: #ffffff; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">Manager Menu</h5>
                </li>
              
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.leave') }}">
                            <i class="bi bi-card-list"></i> View Leave Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.purchase') }}">
                            <i class="bi bi-cart"></i> View Purchase Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.payment') }}">
                            <i class="bi bi-cash"></i> View Payment Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.plan') }}">
                            <i class="bi bi-calendar-week"></i> View Weekly Plans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.report') }}">
                            <i class="bi bi-file-earmark-text"></i> View Weekly Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.index') }}">
                            <i class="fas fa-box"></i> View Inventory Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.budget') }}">
                            <i class="bi bi-file-earmark-text"></i> View Budget Forecasting
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.payroll') }}">
                            <i class="bi bi-file-earmark-text"></i> View Payroll
                        </a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('display.pending') }}">
                            <i class="bi bi-file-earmark-text"></i> Pending Requests
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <style>
            .scrollable-links {
                scrollbar-width: none; /* For Firefox */
                -ms-overflow-style: none; /* For IE and Edge */
            }
        
            .scrollable-links::-webkit-scrollbar {
                display: none; /* For Chrome, Safari, and Opera */
            }
        
            .nav-link {
                display: flex;
                align-items: center;
                padding: 10px 15px;
                transition: background-color 0.3s ease, transform 0.3s ease;
            }
        
            .nav-link i {
                margin-right: 10px; /* Space between icon and text */
                transition: transform 0.3s ease; /* Animation for icon */
            }
        
            .nav-link:hover {
                background-color: rgba(255, 255, 255, 0.1); /* Highlight on hover */
                transform: scale(1.02); /* Slightly scale up on hover */
            }
        
            .nav-link:hover i {
                transform: rotate(360deg); /* Rotate icon on hover */
            }
        
            .link-text {
                opacity: 1; /* Default visibility */
                transition: opacity 0.3s ease; /* Fade effect for text */
            }
        
            .nav-link:hover .link-text {
                opacity: 1; /* Ensure text is visible on hover */
            }
        </style>

       <!-- Main Content -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-4" style="margin-left: 0;">
    <!-- Dashboard Header -->
<h1 class="mb-4 text-center mt-5 animate__animated animate__fadeInDown" style="color: #4A90E2; font-size: 2.5rem;">
    <span>Manager</span> <span>Dashboard</span>
</h1>
<div class="text-center mb-4" style="width: 100%; max-width: 600px; margin: 0 auto;">
    <div class="card text-bg-success shadow-lg animated-card" style="animation: slideIn 0.5s ease forwards; background: #28a745; border: none;">
        <div class="card-body">
            <h5 class="card-title" style="color: white; font-weight: bold; display: inline-block;">Welcome to {{ Auth::user()->company->company_name }}</h5>
            <button class="btn btn-light float-end" onclick="alert('Explore more!')" style="transition: background-color 0.3s;">
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

/* Sidebar Toggle Button */
.sidebar-toggle {
        display: none; /* Hide by default */
        position: fixed;
        top: 5px; /* Adjust vertical position */
        left: 300px; /* Adjust horizontal position */
        z-index: 1100; /* Ensure it's above other elements */
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .sidebar-toggle {
            display: block; /* Show toggle button on mobile */
        }
        .sidebar {
            position: absolute;
            width: 250px;
            height: 100vh;
            left: -250px; /* Hide off-screen */
            transition: left 0.3s ease;
            z-index: 1000;
        }
        .sidebar.show {
            left: 0; /* Show sidebar */
        }
        main {
            margin-left: 0; /* No margin on mobile */
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
         <canvas class="chart " id="inventoryChart" ></canvas>
        </div>
         </div>
            
           <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Prepare data for the charts
        const leaveRequestsData = {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                label: 'Leave Requests',
                data: [
                    {{ $leaveCounts['Approved'] ?? 0 }},
                    {{ $leaveCounts['Pending'] ?? 0 }},
                    {{ $leaveCounts['Rejected'] ?? 0 }}
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
                    {{ $paymentCounts['Approved'] ?? 0 }},
                    {{ $paymentCounts['Pending'] ?? 0 }},
                    {{ $paymentCounts['Rejected'] ?? 0 }}
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
                    {{ $purchaseCounts['Approved'] ?? 0 }},
                    {{ $purchaseCounts['Pending'] ?? 0 }},
                    {{ $purchaseCounts['Rejected'] ?? 0 }}
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
            labels: ['Submitted', 'Not Submitted'],
            datasets: [{
                label: 'Weekly Plans',
                data: [
                    {{ $planCounts['Submitted'] ?? 0 }},
                    {{ $planCounts['Not Submitted'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: 'rgba(0, 0, 0, 0.2)',
                borderWidth: 1,
            }]
        };

        const weeklyReportsData = {
            labels: ['Submitted', 'Not Submitted'],
            datasets: [{
                label: 'Weekly Reports',
                data: [
                    {{ $reportCounts['Submitted'] ?? 0 }},
                    {{ $reportCounts['Not Submitted'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: 'rgba(0, 0, 0, 0.2)',
                borderWidth: 1,
            }]
        };

        const inventoryData = {
    labels: @json($inventories->pluck('item_name')),
    datasets: [{
        label: 'Inventory Quantity',
        data: @json($inventories->pluck('quantity')),
        backgroundColor: [
            'rgba(54, 162, 235, 0.8)', // Example color for each item
            'rgba(255, 206, 86, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)'
        ],
        borderColor: 'rgba(0, 0, 0, 0.2)',
        borderWidth: 1,
    }]
};

        // Create Leave Requests Chart
        const ctxLeave = document.getElementById('leaveRequestsChart').getContext('2d');
        new Chart(ctxLeave, {
            type: 'pie',
            data: leaveRequestsData,
        });

        // Create Payment Requests Chart
        const ctxPayment = document.getElementById('paymentRequestsChart').getContext('2d');
        new Chart(ctxPayment, {
            type: 'bar',
            data: paymentRequestsData,
        });

        // Create Purchase Requests Chart
        const ctxPurchase = document.getElementById('purchaseRequestsChart').getContext('2d');
        new Chart(ctxPurchase, {
            type: 'bar',
            data: purchaseRequestsData,
        });

        // Create Weekly Plans Chart
        const ctxPlans = document.getElementById('weeklyPlansChart').getContext('2d');
        new Chart(ctxPlans, {
            type: 'bar',
            data: weeklyPlansData,
        });

        // Create Weekly Reports Chart
        const ctxReports = document.getElementById('weeklyReportsChart').getContext('2d');
        new Chart(ctxReports, {
            type: 'bar',
            data: weeklyReportsData,
        });


         // Create Inventory Chart
const ctxInventory = document.getElementById('inventoryChart').getContext('2d');
new Chart(ctxInventory, {
    type: 'bar',
    data: inventoryData,
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
@endsection