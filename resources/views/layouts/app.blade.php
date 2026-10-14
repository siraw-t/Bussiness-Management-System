<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Company Management System</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons & CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        /* ==================== DESIGN TOKENS ==================== */
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --primary-soft: #eef2ff;
            --accent: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;

            --bg-body: #f6f8fb;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;

            --radius-sm: 8px;
            --radius: 14px;
            --radius-lg: 20px;

            --shadow-xs: 0 1px 2px rgba(15,23,42,0.04);
            --shadow-sm: 0 2px 6px rgba(15,23,42,0.06);
            --shadow: 0 8px 24px rgba(15,23,42,0.08);
            --shadow-lg: 0 20px 50px rgba(15,23,42,0.12);

            --transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            --navbar-height: 64px;
        }

        [data-bs-theme="dark"] {
            --bg-body: #0b1120;
            --bg-card: #131c31;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --border-color: #1e293b;
            --primary-soft: #1e1b4b;
        }

        /* ==================== BASE ==================== */
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            margin: 0;
            padding-top: var(--navbar-height);
            min-height: 100vh;
            font-size: 0.94rem;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        a { color: var(--primary); text-decoration: none; transition: var(--transition); }
        a:hover { color: var(--primary-dark); }

        /* ==================== NAVBAR ==================== */
        .app-navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--navbar-height);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: saturate(180%) blur(14px);
            -webkit-backdrop-filter: saturate(180%) blur(14px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 20px;
            transition: var(--transition);
        }
        [data-bs-theme="dark"] .app-navbar {
            background: rgba(19, 28, 49, 0.85);
        }
        .app-navbar .nav-inner {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .navbar-brand .brand-mark {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1rem;
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
            flex-shrink: 0;
        }
        .nav-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .icon-btn {
            width: 40px; height: 40px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }
        .icon-btn:hover {
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(99, 102, 241, 0.15);
        }
        .icon-btn .notif-badge {
            position: absolute;
            top: -4px; right: -4px;
            background: var(--danger);
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            min-width: 18px; height: 18px;
            padding: 0 5px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-card);
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 14px 5px 5px;
            border-radius: 999px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: var(--transition);
            max-width: 220px;
        }
        .user-chip:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 18px rgba(99, 102, 241, 0.12);
        }
        .user-chip .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-soft);
            flex-shrink: 0;
        }
        .user-chip .user-name {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--text-primary);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 12px;
            background: var(--danger);
            color: #fff;
            border: none;
            font-weight: 600;
            font-size: 0.86rem;
            cursor: pointer;
            transition: var(--transition);
        }
        .logout-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
        }
        .mobile-menu-btn { display: none; }

        /* ==================== NOTIFICATIONS DROPDOWN ==================== */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            padding: 6px;
            min-width: 320px;
            background: var(--bg-card);
        }
        #notificationList {
            max-height: 420px;
            overflow-y: auto;
            padding: 4px;
        }
        #notificationList::-webkit-scrollbar { width: 6px; }
        #notificationList::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }
        .notification-link {
            display: block;
            padding: 12px 14px;
            border-radius: 10px;
            color: var(--text-secondary);
            font-size: 0.86rem;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }
        .notification-link:hover {
            background: var(--primary-soft);
            color: var(--text-primary);
        }
        .notification-link.unread {
            font-weight: 600;
            color: var(--text-primary);
            border-left-color: var(--primary);
            background: var(--primary-soft);
        }
        .notification-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: block;
            margin-top: 3px;
            font-weight: 400;
        }

        /* ==================== ALERTS ==================== */
        .alert {
            border: none;
            border-radius: var(--radius);
            padding: 14px 20px;
            font-size: 0.9rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.4s ease;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border-left: 4px solid var(--success); }
        .alert-danger  { background: #fef2f2; color: #991b1b; border-left: 4px solid var(--danger); }
        [data-bs-theme="dark"] .alert-success { background: #064e3b; color: #a7f3d0; }
        [data-bs-theme="dark"] .alert-danger  { background: #7f1d1d; color: #fecaca; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ==================== MODAL ==================== */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }
        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border-bottom: none;
            padding: 20px 24px;
        }
        .modal-title { font-weight: 700; font-size: 1.05rem; }
        .modal-body { padding: 24px; }
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            background: transparent;
        }
        .modal .btn-close { filter: invert(1); opacity: 0.9; }
        .profile-modal-table { margin-bottom: 0; }
        .profile-modal-table th {
            width: 30%;
            font-weight: 600;
            color: var(--text-secondary);
            background: transparent !important;
            border-color: var(--border-color);
        }
        .profile-modal-table td { color: var(--text-primary); border-color: var(--border-color); }

        /* ==================== BUTTONS ==================== */
        .btn { border-radius: 10px; font-weight: 600; transition: var(--transition); }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(99, 102, 241, 0.4);
        }
        .btn-secondary { background: var(--text-secondary); border: none; }
        .btn-danger { background: var(--danger); border: none; }
        .btn-success { background: var(--success); border: none; }

        /* ==================== FORM CONTROLS ==================== */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid var(--border-color);
            padding: 10px 14px;
            font-size: 0.9rem;
            background: var(--bg-card);
            color: var(--text-primary);
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
            background: var(--bg-card);
            color: var(--text-primary);
        }
        .form-label { font-weight: 600; font-size: 0.85rem; color: var(--text-primary); margin-bottom: 6px; }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 991px) {
            .user-chip .user-name { display: none; }
            .user-chip { padding: 4px; }
            .logout-btn span { display: none; }
            .logout-btn { padding: 9px 12px; }
        }
        @media (max-width: 576px) {
            :root { --navbar-height: 58px; }
            body { font-size: 0.88rem; }
            .navbar-brand { font-size: 0.92rem; }
            .navbar-brand .brand-mark { width: 30px; height: 30px; font-size: 0.85rem; }
            .app-navbar { padding: 0 12px; }
            .nav-actions { gap: 6px; }
            .icon-btn { width: 36px; height: 36px; }
            .modal-body { padding: 18px; }
            .dropdown-menu { min-width: 280px; }
        }

        /* ==================== SCROLLBAR ==================== */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-light); }

        /* ==================== PAGE WRAPPER ==================== */
        .page-wrapper {
            max-width: 1600px;
            margin: 0 auto;
            padding: 24px;
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 576px) {
            .page-wrapper { padding: 14px; }
        }
    </style>
</head>

<body>
    <!-- ==================== NAVBAR ==================== -->
    <nav class="app-navbar">
        <div class="nav-inner">
            @auth
                @php
                    $user = Auth::user();
                    $role = $user->role;
                    $dashboardRoute = strtolower($role) . '.dashboard';
                    $companyName = $role === 'superadmin'
                        ? 'Superadmin Dashboard'
                        : ($user->company->company_name ?? 'Admin Dashboard');

                    $displayNotifications = $user
                        ->notifications()
                        ->where(function ($query) {
                            $query->whereNull('read_at')->orWhere('read_at', '>', now()->subHours(12));
                        })
                        ->latest()
                        ->take(15)
                        ->get();

                    $unreadCount = $user->unreadNotifications()->count();
                @endphp
                <a class="navbar-brand" href="{{ route($dashboardRoute) }}">
                    <span class="brand-mark"><i class="bi bi-grid-1x2-fill"></i></span>
                    <span class="d-none d-sm-inline">{{ $companyName }}</span>
                    <span class="d-inline d-sm-none">ERP</span>
                </a>
            @else
                <a class="navbar-brand" href="/">
                    <span class="brand-mark"><i class="bi bi-grid-1x2-fill"></i></span>
                    <span>Company CMS</span>
                </a>
            @endauth

            <div class="nav-actions">
                @auth
                    {{-- Theme Toggle --}}
                    <button id="theme-toggle" class="icon-btn" type="button" aria-label="Toggle theme">
                        <i class="fas fa-moon" id="theme-icon"></i>
                    </button>

                    {{-- Notifications --}}
                    <div class="dropdown">
                        <button class="icon-btn" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                            <i class="fas fa-bell"></i>
                            <span id="notificationCount" class="notif-badge" style="{{ $unreadCount > 0 ? '' : 'display:none;' }}">{{ $unreadCount }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" id="notificationList" aria-labelledby="notificationDropdown">
                            @forelse ($displayNotifications as $notification)
                                <li>
                                    <a class="dropdown-item notification-link @if (!$notification->read_at) unread @endif"
                                       href="{{ $notification->data['url'] ?? '#' }}"
                                       data-id="{{ $notification->id }}"
                                       data-url="{{ $notification->data['url'] ?? '#' }}">
                                        {{ $notification->data['message'] }}
                                        <span class="notification-time text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </a>
                                </li>
                            @empty
                                <li><a class="dropdown-item disabled" href="#">No recent notifications</a></li>
                            @endforelse
                            @if ($unreadCount > 0)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-center text-primary fw-semibold" href="#" id="mark-all-read-link">
                                        <i class="bi bi-check2-all me-1"></i> Mark all as read
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    {{-- Profile Chip --}}
                    <div class="user-chip" id="profile-link" data-bs-toggle="modal" data-bs-target="#profileModal" role="button">
                        <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('avatars/default-avatar.png') }}" alt="Avatar" class="avatar">
                        <span class="user-name">Welcome, {{ Auth::user()->full_name }}!</span>
                    </div>

                    {{-- Logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <button id="theme-toggle" class="icon-btn" type="button" aria-label="Toggle theme">
                        <i class="fas fa-moon" id="theme-icon"></i>
                    </button>
                    <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-outline-secondary btn-sm d-none d-sm-inline" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ==================== PAGE CONTENT ==================== -->
    <div class="page-wrapper">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </div>

    @auth
        <!-- ==================== PROFILE MODAL ==================== -->
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileModalLabel">
                            <i class="fas fa-user-circle me-2"></i>Profile Information
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="mb-3 text-primary fw-bold">Account Details</h6>
                        <table class="table table-borderless profile-modal-table">
                            <tbody>
                                <tr><th scope="row">Username</th><td>{{ Auth::user()->username }}</td></tr>
                                <tr><th scope="row">Full Name</th><td>{{ Auth::user()->full_name }}</td></tr>
                                <tr><th scope="row">Email</th><td>{{ Auth::user()->email }}</td></tr>
                                <tr><th scope="row">Role</th><td><span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span></td></tr>
                                @if (Auth::user()->role !== 'superadmin' && Auth::user()->company)
                                    <tr>
                                        <th scope="row">Company</th>
                                        <td>{{ Auth::user()->company->company_name }} <span class="text-muted">(ID: {{ Auth::user()->company_id }})</span></td>
                                    </tr>
                                @elseif(Auth::user()->role === 'superadmin')
                                    <tr><th scope="row">Access Level</th><td><span class="badge bg-success">Full System Access</span></td></tr>
                                @endif
                                <tr><th scope="row">Member Since</th><td>{{ Auth::user()->created_at->format('M d, Y') }}</td></tr>
                            </tbody>
                        </table>
                        <hr>

                        @if (Auth::user()->role !== 'superadmin')
                            <h6 class="mb-3 text-primary fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Profile</h6>
                            <form id="profileUpdateForm" action="{{ route('users.update', Auth::user()->user_id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_marker" value="profileUpdate">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username', Auth::user()->username) }}" required>
                                        @error('username')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', Auth::user()->full_name) }}" required>
                                        @error('full_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current">
                                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>

                                <input type="hidden" name="role" value="{{ Auth::user()->role }}">
                                @if (Auth::user()->company_id)
                                    <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                                @endif

                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/png, image/jpeg, image/gif">
                                    @error('profile_image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    @if (Auth::user()->profile_image)
                                        <div class="mt-2 d-flex align-items-center gap-2">
                                            <small class="text-muted">Current:</small>
                                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Current Profile Image" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                        </div>
                                    @else
                                        <small class="text-muted d-block mt-1">No current profile image.</small>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Profile</button>
                            </form>
                        @else
                            <p class="text-muted text-center mb-0">Superadmin profile details are managed elsewhere.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationList = document.getElementById('notificationList');
            const notificationCountBadge = document.getElementById('notificationCount');
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
            const markAllReadLink = document.getElementById('mark-all-read-link');

            /* ============ THEME TOGGLE ============ */
            const themeToggleButton = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const htmlElement = document.documentElement;

            const setTheme = (theme) => {
                if (theme === 'dark') {
                    htmlElement.dataset.bsTheme = 'dark';
                    if (themeIcon) {
                        themeIcon.classList.remove('fa-moon');
                        themeIcon.classList.add('fa-sun');
                    }
                    if (themeToggleButton) themeToggleButton.setAttribute('aria-label', 'Switch to light mode');
                    localStorage.setItem('theme', 'dark');
                } else {
                    htmlElement.removeAttribute('data-bs-theme');
                    if (themeIcon) {
                        themeIcon.classList.remove('fa-sun');
                        themeIcon.classList.add('fa-moon');
                    }
                    if (themeToggleButton) themeToggleButton.setAttribute('aria-label', 'Switch to dark mode');
                    localStorage.setItem('theme', 'light');
                }
            };

            const getPreferredTheme = () => {
                const storedTheme = localStorage.getItem('theme');
                if (storedTheme) return storedTheme;
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };

            setTheme(getPreferredTheme());

            if (themeToggleButton) {
                themeToggleButton.addEventListener('click', () => {
                    const newTheme = htmlElement.dataset.bsTheme === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            }

            try {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                    if (!localStorage.getItem('theme')) {
                        setTheme(event.matches ? 'dark' : 'light');
                    }
                });
            } catch (e) {
                console.warn("matchMedia listener not supported.", e);
            }

            /* ============ NOTIFICATION COUNT ============ */
            function updateNotificationCount(newCount) {
                if (notificationCountBadge) {
                    const count = parseInt(newCount, 10);
                    notificationCountBadge.innerText = count;
                    notificationCountBadge.style.display = count > 0 ? 'inline-flex' : 'none';
                }
                if (markAllReadLink) {
                    const listItem = markAllReadLink.closest('li');
                    const divider = listItem?.previousElementSibling;
                    if (newCount > 0) {
                        if (listItem) listItem.style.display = '';
                        if (divider && divider.tagName === 'LI' && divider.querySelector('hr')) divider.style.display = '';
                    } else {
                        if (listItem) listItem.style.display = 'none';
                        if (divider && divider.tagName === 'LI' && divider.querySelector('hr')) divider.style.display = 'none';
                    }
                }
            }

            /* ============ INDIVIDUAL NOTIFICATION CLICK ============ */
            if (notificationList) {
                notificationList.addEventListener('click', function(event) {
                    const link = event.target.closest('.notification-link');
                    if (!link || !link.dataset.id) return;

                    event.preventDefault();

                    const notificationId = link.dataset.id;
                    const targetUrl = link.dataset.url || '#';
                    const isUnread = link.classList.contains('unread');

                    if (isUnread) {
                        fetch(`/notifications/${notificationId}/read`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw new Error(err.message || `HTTP error ${response.status}`); });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                link.classList.remove('unread');
                                if (data.unread_count !== undefined) updateNotificationCount(data.unread_count);
                                if (targetUrl && targetUrl !== '#') window.location.href = targetUrl;
                            } else {
                                if (targetUrl && targetUrl !== '#') window.location.href = targetUrl;
                            }
                        })
                        .catch(error => {
                            console.error('Error marking notification as read:', error);
                            if (targetUrl && targetUrl !== '#') window.location.href = targetUrl;
                        });
                    } else {
                        if (targetUrl && targetUrl !== '#') window.location.href = targetUrl;
                    }
                });
            }

            /* ============ MARK ALL AS READ ============ */
            if (markAllReadLink) {
                markAllReadLink.addEventListener('click', function(event) {
                    event.preventDefault();

                    fetch('/notifications/mark-all-read', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw new Error(err.message || `HTTP error ${response.status}`); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            updateNotificationCount(0);
                            const allLinks = notificationList?.querySelectorAll('.notification-link.unread');
                            allLinks?.forEach(link => link.classList.remove('unread'));
                        }
                    })
                    .catch(error => {
                        console.error('Error marking all notifications as read:', error);
                    });
                });
            }

            /* ============ AUTO-DISMISS ALERTS ============ */
            const autoDismissAlerts = document.querySelectorAll('.alert-dismissible');
            autoDismissAlerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getInstance(alert);
                    if (bsAlert) bsAlert.close();
                    else alert.remove();
                }, 5000);
            });

            /* ============ RE-OPEN PROFILE MODAL ON VALIDATION ERRORS ============ */
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