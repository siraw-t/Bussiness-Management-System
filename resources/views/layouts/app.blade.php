<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Ensure CSRF token is present -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Company Management System</title>
    {{-- other head elements --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    {{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    <style>
        /* Global Styles */
        body {
            background: linear-gradient(135deg, #f0f4f8, #e9eff6);
            font-family: 'Poppins', sans-serif;
            margin-top: 70px;
            /* Adjust for fixed navbar height */
            color: black;
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Add transitions for theme change */
        }

        /* Navbar */
        .navbar {
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1030;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            /* Add transitions */
        }

        .navbar-brand {
            color: black;
            text-transform: uppercase;
            font-size: 1rem;
            font-weight: 600;
            transition: color 0.3s ease;
            /* Add transitions */
        }

        .nav-link {
            color: black;
            transition: color 0.3s, transform 0.3s;
            padding: 0.5rem 1rem;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #ffc107;
            transform: scale(1.05);
        }

        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.75rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(0, 0, 0, 0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            /* Increased padding */
            background: #ffffff;
            /* White background */
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.7s ease;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, color 0.3s ease;
            /* Add transitions */
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            font-size: 0.95rem;
            /* Slightly smaller */
            animation: bounceIn 0.8s;
            /* Adjusted animation */
            margin-bottom: 1.5rem;
            /* Added margin */
        }

        .avatar {
            width: 35px;
            /* Slightly smaller */
            height: 35px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
            /* Lighter border */
            object-fit: cover;
            margin-right: 8px;
            /* Adjusted margin */
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            border: none;
            margin-top: 0.5rem;
            /* Add space below navbar */
            transition: background-color 0.3s ease, border-color 0.3s ease;
            /* Add transitions */
        }

        .dropdown-item {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            color: #343a40;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: #f8f9fa;
            color: #0056b3;
        }

        .dropdown-divider {
            margin: 0.3rem 0;
        }

        /* Notification specific */
        #notificationList {
            max-height: 400px;
            /* Limit height */
            overflow-y: auto;
            /* Add scroll if needed */
        }

        .notification-link.unread {
            font-weight: 600;
            /* Bold for unread */
            color: #212529;
            /* Darker text for unread */
        }

        .notification-link .badge {
            font-size: 0.7em;
            vertical-align: middle;
        }

        .notification-time {
            font-size: 0.8em;
            color: #6c757d;
            display: block;
            margin-top: 3px;
        }

        /* Profile Modal */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            border-bottom: none;
            padding: 1rem 1.5rem;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: none;
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .profile-modal-table th {
            width: 30%;
            /* Adjust width */
            font-weight: 600;
            color: #495057;
        }

        .profile-modal-table td {
            color: #212529;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Make profile form labels bold */
        #profileModal .form-label {
            font-weight: 600;
            color: #495057;
        }

        /* Logout button styling */
        .logout-btn {
            width: 100%;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .logout-btn:hover {
            background-color: #c82333;
            /* Darker red on hover */
            border-color: #bd2130;
        }

        /* Add some spacing for the user welcome text */
        #profile-link {
            display: inline-flex;
            /* Align icon and text */
            align-items: center;
        }

        #profile-link .fa-user {
            margin-right: 5px;
        }

        /* --- START: Dark Mode Styles --- */
        [data-bs-theme="dark"] {
            --bs-body-bg: #1a1d20;
            /* Dark background */
            --bs-body-color: #e8eaed;
            /* Light text */
            --bs-emphasis-color: #f8f9fa;
            --bs-secondary-bg: #343a40;
            --bs-tertiary-bg: #2b3035;
            --bs-border-color: #495057;
            --bs-form-control-bg: #2b3035;
            /* Dark form input bg */
            --bs-form-control-color: var(--bs-body-color);
            /* Form input text */
            --bs-form-control-border-color: var(--bs-border-color);
            /* Form input border */
            --bs-link-color: #6ea8fe;
            /* Lighter blue for links */
            --bs-link-hover-color: #8ec1fe;
        }

        [data-bs-theme="dark"] body {
            background: linear-gradient(135deg, #2a2d30, #1a1d20);
            /* Dark gradient */
            color: var(--bs-body-color);
        }

        [data-bs-theme="dark"] .navbar {
            background: #212529;
            /* Bootstrap dark */
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.08);
        }

        [data-bs-theme="dark"] .navbar-brand,
        [data-bs-theme="dark"] .nav-link {
            color: #adb5bd;
            /* Lighter grey for links */
        }

        [data-bs-theme="dark"] .nav-link:hover,
        [data-bs-theme="dark"] .nav-link.active {
            color: #ffc107;
            /* Keep warning color for hover/active */
        }

        [data-bs-theme="dark"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.7)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        [data-bs-theme="dark"] .container {
            background: #2b3035;
            /* Slightly lighter dark */
            box-shadow: 0 6px 25px rgba(255, 255, 255, 0.05);
            color: var(--bs-body-color);
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #343a40;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--bs-border-color);
            --bs-dropdown-link-color: #e8eaed;
            --bs-dropdown-link-hover-color: #ffffff;
            --bs-dropdown-link-hover-bg: #495057;
            --bs-dropdown-link-disabled-color: #6c757d;
            --bs-dropdown-divider-bg: #495057;
        }

        [data-bs-theme="dark"] .notification-link.unread {
            color: #f8f9fa;
            /* Brighter for unread in dark mode */
            font-weight: bold;
        }

        [data-bs-theme="dark"] .notification-link:not(.unread) {
            color: #adb5bd;
            /* Slightly dimmer for read */
        }

        [data-bs-theme="dark"] .notification-time {
            color: #adb5bd;
            /* Lighter muted */
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: var(--bs-tertiary-bg);
            color: var(--bs-body-color);
            border: 1px solid var(--bs-border-color);
        }

        [data-bs-theme="dark"] .modal-header {
            background-color: #0b5ed7;
            /* Slightly darker primary */
            border-bottom: 1px solid var(--bs-border-color);
            color: #e8eaed;
        }

        [data-bs-theme="dark"] .modal-header .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
            /* Make close button visible */
        }

        [data-bs-theme="dark"] .modal-footer {
            background-color: var(--bs-secondary-bg);
            border-top: 1px solid var(--bs-border-color);
        }

        [data-bs-theme="dark"] .profile-modal-table th,
        [data-bs-theme="dark"] #profileModal .form-label {
            color: #adb5bd;
            /* Lighter labels/headers */
        }

        [data-bs-theme="dark"] .profile-modal-table td {
            color: var(--bs-body-color);
        }

        [data-bs-theme="dark"] .table {
            --bs-table-color: var(--bs-body-color);
            --bs-table-bg: transparent;
            /* Use container bg */
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-striped-color: var(--bs-body-color);
            --bs-table-striped-bg: rgba(255, 255, 255, 0.04);
            /* Subtle striping */
            --bs-table-hover-color: var(--bs-body-color);
            --bs-table-hover-bg: rgba(255, 255, 255, 0.06);
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: var(--bs-form-control-bg);
            color: var(--bs-form-control-color);
            border-color: var(--bs-form-control-border-color);
        }

        [data-bs-theme="dark"] .form-control::placeholder {
            color: #6c757d;
            /* Placeholder text */
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #343a40;
            /* Slightly different focus bg */
            color: var(--bs-form-control-color);
            border-color: #86b7fe;
            /* Default Bootstrap focus blue */
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Improve select arrow visibility */
        [data-bs-theme="dark"] .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23adb5bd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }


        [data-bs-theme="dark"] .alert-success {
            --bs-alert-color: #a3cfbb;
            --bs-alert-bg: #0a361a;
            --bs-alert-border-color: #146c43;
            --bs-alert-link-color: #c1e2d3;
        }

        [data-bs-theme="dark"] .alert-danger {
            --bs-alert-color: #f1b0b7;
            --bs-alert-bg: #491a21;
            --bs-alert-border-color: #a52834;
            --bs-alert-link-color: #f7c6cb;
        }

        [data-bs-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
            /* Make close button visible */
        }

        [data-bs-theme="dark"] #theme-toggle {
            color: #adb5bd;
            /* Match other nav links */
            border-color: #495057;
        }

        [data-bs-theme="dark"] #theme-toggle:hover {
            color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
            /* Subtle hover */
            border-color: #ffc107;
        }

        /* Ensure avatar border is visible */
        [data-bs-theme="dark"] .avatar {
            border-color: #495057;
        }

        /* Adjust text-muted color */
        [data-bs-theme="dark"] .text-muted {
            color: #adb5bd !important;
            /* Slightly lighter muted */
        }

        /* Adjust text-primary color */
        [data-bs-theme="dark"] .text-primary {
            color: #6ea8fe !important;
        }

        /* --- END: Dark Mode Styles --- */
    </style>
</head>

<body> <!-- No data-bs-theme here initially, JS will add it -->
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light animate__animated animate__fadeInDown">
        <div class="container-fluid">
            @auth
                @php
                    // --- Fetching Logic ---
                    $user = Auth::user();
                    $role = $user->role;
                    $dashboardRoute = strtolower($role) . '.dashboard';
                    $companyName =
                        $role === 'superadmin'
                            ? 'Superadmin Dashboard'
                            : $user->company->company_name ?? 'Admin Dashboard';

                    // Get notifications to display (unread OR read within last 12 hours)
                    $displayNotifications = $user
                        ->notifications()
                        ->where(function ($query) {
                            $query->whereNull('read_at')->orWhere('read_at', '>', now()->subHours(12));
                        })
                        ->latest() // Show newest first
                        ->take(15) // Limit the number fetched/displayed
                        ->get();

                    // Get the specific count of unread notifications for the badge
                    $unreadCount = $user->unreadNotifications()->count();
                    // --- End Fetching Logic ---
                @endphp
                <a class="navbar-brand" href="{{ route($dashboardRoute) }}">
                    {{ $companyName }}
                </a>
            @else
                <a class="navbar-brand" href="/">Company CMS</a> {{-- Fallback for non-authed pages --}}
            @endauth

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center"> {{-- Use align-items-center for vertical alignment --}}
                    @auth
                        {{-- START: Theme Toggle Button --}}
                        <li class="nav-item me-2"> {{-- Added margin-end --}}
                            <button id="theme-toggle" class="btn btn-sm btn-outline-secondary" type="button"
                                aria-label="Toggle theme">
                                <i class="fas fa-moon" id="theme-icon"></i> {{-- Icon will change (start with moon for light default) --}}
                            </button>
                        </li>
                        {{-- END: Theme Toggle Button --}}

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                {{-- Display badge only if count > 0 --}}
                                <span id="notificationCount" class="badge bg-danger ms-1"
                                    style="{{ $unreadCount > 0 ? '' : 'display: none;' }}">
                                    {{ $unreadCount }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" id="notificationList"
                                aria-labelledby="notificationDropdown">
                                @forelse ($displayNotifications as $notification)
                                    <li>
                                        {{-- Add 'unread' class if notification is unread --}}
                                        <a class="dropdown-item notification-link @if (!$notification->read_at) unread @endif"
                                            href="{{ $notification->data['url'] ?? '#' }}"
                                            data-id="{{ $notification->id }}" {{-- Notification ID --}}
                                            data-url="{{ $notification->data['url'] ?? '#' }}">
                                            {{-- Original URL --}}

                                            {{ $notification->data['message'] }}

                                            {{-- Optional: Show relative time --}}
                                            <span class="notification-time text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </a>
                                    </li>
                                @empty
                                    <li><a class="dropdown-item disabled" href="#">No recent notifications</a></li>
                                @endforelse
                                {{-- Optional: Separator and Mark All Read Link --}}
                                @if ($unreadCount > 0)
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-center text-primary" href="#"
                                            id="mark-all-read-link">Mark all as read</a></li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item d-flex align-items-center me-3"> {{-- Added margin-end (me-3) --}}
                            <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('avatars/default-avatar.png') }}"
                                alt="Avatar" class="avatar">
                            <a class="nav-link" href="#" id="profile-link" data-bs-toggle="modal"
                                data-bs-target="#profileModal" style="padding-right: 0;"> {{-- Remove right padding if needed --}}
                                <i class="fas fa-user d-none d-lg-inline"></i> {{-- Hide icon on small screens if desired --}}
                                Welcome, {{ Auth::user()->full_name }}!
                            </a>
                        </li>
                        <li class="nav-item"> {{-- Wrapped button in li for consistency --}}
                            <form action="{{ route('logout') }}" method="POST" class="d-flex"> {{-- Use d-flex if needed --}}
                                @csrf
                                <button type="submit" class="btn btn-danger logout-btn">
                                    <span class="d-none d-lg-inline">Logout</span> {{-- Hide text on small screens --}}
                                    <i class="fas fa-sign-out-alt ms-1"></i>
                                </button>
                            </form>
                        </li>
                    @else
                        {{-- START: Theme Toggle Button (Guest) --}}
                        <li class="nav-item me-2">
                            <button id="theme-toggle" class="btn btn-sm btn-outline-secondary" type="button"
                                aria-label="Toggle theme">
                                <i class="fas fa-moon" id="theme-icon"></i>
                            </button>
                        </li>
                        {{-- END: Theme Toggle Button (Guest) --}}
                        {{-- Links for guests if needed --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4 animate__animated animate__fadeInUp"> {{-- Added mt-4 for spacing --}}
        @if (session('success'))
            {{-- Use Bootstrap 5 alert styling --}}
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </div>

    @auth {{-- Only include modal if user is authenticated --}}
        <!-- Profile Modal -->
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg"> {{-- Consider making modal larger --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileModalLabel"><i class="fas fa-user-circle me-2"></i>Profile
                            Information</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Display User Info Table --}}
                        <h6 class="mb-3 text-primary">Account Details</h6>
                        <table class="table table-striped table-hover profile-modal-table">
                            <tbody>
                                <tr>
                                    <th scope="row">Username</th>
                                    <td>{{ Auth::user()->username }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Full Name</th>
                                    <td>{{ Auth::user()->full_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Role</th>
                                    <td>{{ ucfirst(Auth::user()->role) }}</td>
                                </tr>
                                @if (Auth::user()->role !== 'superadmin' && Auth::user()->company)
                                    <tr>
                                        <th scope="row">Company</th>
                                        <td>{{ Auth::user()->company->company_name }} (ID: {{ Auth::user()->company_id }})
                                        </td>
                                    </tr>
                                @elseif(Auth::user()->role === 'superadmin')
                                    <tr>
                                        <th scope="row">Access Level</th>
                                        <td>Full System Access</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th scope="row">Member Since</th>
                                    <td>{{ Auth::user()->created_at->format('M d, Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>

                        {{-- Edit Profile Form (Conditionally Shown) --}}
                        @if (Auth::user()->role !== 'superadmin')
                            <h6 class="mb-3 text-primary"><i class="fas fa-user-edit me-2"></i>Edit Profile</h6>
                            <form id="profileUpdateForm" action="{{ route('users.update', Auth::user()->user_id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="form_marker" value="profileUpdate"> {{-- Added for error handling --}}

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            value="{{ old('username', Auth::user()->username) }}" required>
                                        @error('username')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name"
                                            value="{{ old('full_name', Auth::user()->full_name) }}" required>
                                        @error('full_name')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Leave blank to keep current">
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>
                                </div>

                                {{-- Role and Company are usually not editable by the user themselves --}}
                                {{-- Keep hidden or readonly fields if needed for submission --}}
                                <input type="hidden" name="role" value="{{ Auth::user()->role }}">
                                @if (Auth::user()->company_id)
                                    <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                                @endif


                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image"
                                        accept="image/png, image/jpeg, image/gif">
                                    @error('profile_image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    @if (Auth::user()->profile_image)
                                        <div class="mt-2">
                                            <small class="text-muted">Current:</small>
                                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                                                alt="Current Profile Image"
                                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-left: 10px;">
                                        </div>
                                    @else
                                        <small class="text-muted d-block mt-1">No current profile image.</small>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update
                                    Profile</button>
                            </form>
                        @else
                            <p class="text-muted text-center">Superadmin profile details are managed elsewhere.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- Scripts -->
    {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}} {{-- Ensure this compiles Bootstrap JS or include manually --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Existing Notification/Modal JS ---
            const notificationList = document.getElementById('notificationList');
            const notificationCountBadge = document.getElementById('notificationCount');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const markAllReadLink = document.getElementById('mark-all-read-link'); // Get the mark all link


            // --- START: Theme Toggle Logic ---
            const themeToggleButton = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const htmlElement = document.documentElement; // Target the <html> element

            // Function to set the theme (applies attribute, updates icon, saves preference)
            const setTheme = (theme) => {
                if (theme === 'dark') {
                    htmlElement.dataset.bsTheme = 'dark'; // Set Bootstrap theme attribute
                    if (themeIcon) { // Check if icon exists
                        themeIcon.classList.remove('fa-moon');
                        themeIcon.classList.add('fa-sun');
                    }
                    if (themeToggleButton) themeToggleButton.setAttribute('aria-label', 'Switch to light mode');
                    localStorage.setItem('theme', 'dark'); // Save preference
                } else { // Includes 'light' or any other case
                    htmlElement.removeAttribute('data-bs-theme'); // Remove attribute for light mode
                    if (themeIcon) { // Check if icon exists
                        themeIcon.classList.remove('fa-sun');
                        themeIcon.classList.add('fa-moon');
                    }
                    if (themeToggleButton) themeToggleButton.setAttribute('aria-label', 'Switch to dark mode');
                    localStorage.setItem('theme', 'light'); // Save preference
                }
            };

            // Function to get the preferred theme (check localStorage, then system preference)
            const getPreferredTheme = () => {
                const storedTheme = localStorage.getItem('theme');
                if (storedTheme) {
                    return storedTheme; // Return stored theme if available
                }
                // If no stored theme, check system preference
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };

            // Set the initial theme when the page loads
            const currentTheme = getPreferredTheme();
            setTheme(currentTheme);

            // Add click listener to the toggle button (check if button exists)
            if (themeToggleButton) {
                themeToggleButton.addEventListener('click', () => {
                    // Determine the *new* theme by checking the current state
                    const newTheme = htmlElement.dataset.bsTheme === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme); // Apply the new theme
                });
            }

            // Optional: Listen for changes in system preference (if user hasn't set one)
            try { // Use try...catch as matchMedia might not be supported everywhere (though unlikely now)
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                    // Only change if no theme is *explicitly* stored by the user
                    if (!localStorage.getItem('theme')) {
                        setTheme(event.matches ? 'dark' : 'light');
                    }
                });
            } catch (e) {
                console.warn("matchMedia listener for prefers-color-scheme not supported or errored.", e);
            }
            // --- END: Theme Toggle Logic ---


            // --- Function to update notification count display ---
            function updateNotificationCount(newCount) {
                if (notificationCountBadge) {
                    const count = parseInt(newCount, 10);
                    notificationCountBadge.innerText = count;
                    if (count > 0) {
                        notificationCountBadge.style.display = 'inline-block'; // Show badge
                    } else {
                        notificationCountBadge.style.display = 'none'; // Hide badge
                    }
                }
                // Also update the visibility of the "Mark all read" link
                if (markAllReadLink) {
                    const listItem = markAllReadLink.closest('li');
                    const divider = listItem?.previousElementSibling; // Get the divider before it

                    if (newCount > 0) {
                        // Ensure the link and its parent list item are visible
                        if (listItem) listItem.style.display = '';
                        if (divider && divider.tagName === 'LI' && divider.querySelector('hr')) divider.style
                            .display = ''; // Show divider too
                    } else {
                        // Hide the link and its parent list item
                        if (listItem) listItem.style.display = 'none';
                        if (divider && divider.tagName === 'LI' && divider.querySelector('hr')) divider.style
                            .display = 'none'; // Hide divider too
                    }
                }
            }


            // --- Event Listener for Individual Notification Clicks ---
            if (notificationList) {
                notificationList.addEventListener('click', function(event) {
                    const link = event.target.closest('.notification-link');
                    if (!link || !link.dataset.id) { // Ensure it's a valid notification link with an ID
                        return; // Exit if the click wasn't on a notification link or it lacks data-id
                    }

                    event.preventDefault(); // Prevent default link navigation *initially*

                    const notificationId = link.dataset.id;
                    const targetUrl = link.dataset.url || '#'; // Get the original URL (fallback to #)
                    const isUnread = link.classList.contains('unread'); // Check if it was unread

                    // Only send request if it's actually marked as unread in the UI
                    if (isUnread) {
                        const markAsReadUrl =
                            `/notifications/${notificationId}/read`; // Make sure this route exists

                        fetch(markAsReadUrl, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                            })
                            .then(response => {
                                if (!response.ok) {
                                    // Try to parse error message from backend
                                    return response.json().then(err => {
                                        throw new Error(err.message ||
                                            `HTTP error ${response.status}`)
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    console.log('Notification marked as read.');
                                    link.classList.remove('unread');
                                    if (data.unread_count !== undefined) {
                                        updateNotificationCount(data.unread_count);
                                    }
                                    // Navigate after successful update
                                    if (targetUrl && targetUrl !== '#') window.location.href =
                                        targetUrl;
                                } else {
                                    console.error('Failed to mark notification as read:', data
                                        .message || 'Unknown backend error');
                                    alert(
                                        'Could not mark notification as read. Proceeding to link anyway.'
                                    );
                                    // Still navigate even if marking failed
                                    if (targetUrl && targetUrl !== '#') window.location.href =
                                        targetUrl;
                                }
                            })
                            .catch(error => {
                                console.error('Error marking notification as read:', error);
                                alert(
                                    `An error occurred: ${error.message}. Could not mark notification as read. Proceeding to link anyway.`
                                );
                                // Still navigate even on network/fetch error
                                if (targetUrl && targetUrl !== '#') window.location.href = targetUrl;
                            });
                    } else {
                        // If the notification was *already read*, just navigate
                        console.log('Notification already read, navigating directly.');
                        if (targetUrl && targetUrl !== '#') {
                            window.location.href = targetUrl;
                        }
                    }
                });
            }


            // --- Event Listener for "Mark all as read" Link ---
            if (markAllReadLink) {
                markAllReadLink.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior

                    const markAllUrl = '/notifications/mark-all-read'; // Make sure this route exists

                    fetch(markAllUrl, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message ||
                                        `HTTP error ${response.status}`)
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                console.log('All notifications marked as read.');
                                updateNotificationCount(0); // Update count to 0

                                // Remove 'unread' class from all visible notification links
                                const allLinks = notificationList?.querySelectorAll(
                                    '.notification-link.unread');
                                allLinks?.forEach(link => link.classList.remove('unread'));

                                // Hide the "Mark all as read" link itself and its divider (handled by updateNotificationCount)

                            } else {
                                console.error('Failed to mark all notifications as read:', data
                                    .message || 'Unknown backend error');
                                alert('Could not mark all notifications as read. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error marking all notifications as read:', error);
                            alert(
                                `An error occurred: ${error.message}. Could not mark all notifications as read.`
                            );
                        });
                });
            }

            // --- Close alert messages after a delay ---
            const autoDismissAlerts = document.querySelectorAll('.alert-dismissible');
            autoDismissAlerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getInstance(alert);
                    if (bsAlert) {
                        bsAlert.close();
                    } else {
                        alert.remove(); // Fallback removal
                    }
                }, 5000); // 5 seconds
            });

            // --- Handle Profile Modal Form Submission Errors ---
            // If backend validation redirects back with errors for the profile form,
            // this reopens the modal to show them. Requires the hidden input field.
            @if ($errors->any() && old('form_marker') === 'profileUpdate')
                var profileModalElement = document.getElementById('profileModal');
                if (profileModalElement) {
                    var profileModal = new bootstrap.Modal(profileModalElement);
                    profileModal.show();
                }
            @endif

        });
    </script>

</body>

</html>
