<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM4F5C0z0j7B2KpXrE5ti2kW8zK9W1kT5q2Gx" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
   <style>
        /* Global Styles */
        body {
            background: linear-gradient(135deg, #f0f4f8, #e9eff6);
            font-family: 'Poppins', sans-serif;
            margin-top: 56px;
            color: black;
        }
        /* Navbar */
        .navbar {
            background: #007bff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1030;
        }
        .navbar-brand {
            font-weight: 600;
            color: black;
            text-transform: uppercase;
            font-size: 1rem;
        }
        .nav-link {
            color: black;
            transition: color 0.3s, transform 0.3s;
        }
        .nav-link:hover {
            color: #ffc107;
            transform: scale(1.1);
        }
        .sidebar {
    position: fixed; /* Ensure it stays in place */
    z-index: 1000; /* Stay above other content */
}
        /* Container */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 5px;
            background: lavender;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            animation: fadeInUp 0.7s ease;
        }
        /* Alerts */
        .alert {
            border-radius: 10px;
            font-size: 1rem;
            animation: bounceIn 1s;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid black;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark animate__animated animate__fadeInDown" style="background-color: white;">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">Company Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex me-3" id="searchForm" onsubmit="return handleSearch(event)">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput">
                <button class="btn btn-outline-black ms-2" type="submit"><i class="fas fa-search"></i></button>
            </form>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                @auth
                    @php
                        $role = Auth::user()->role;
                        $dashboardRoute = strtolower($role) . '.dashboard';
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($dashboardRoute) ? 'active' : '' }}" 
                           href="{{ route($dashboardRoute) }}" 
                           style="color: black;">
                            <i class="fas fa-tachometer-alt"></i> {{ ucfirst($role) }} Dashboard
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>  <span id="notificationCount" class="badge bg-danger">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" id="notificationList" aria-labelledby="notificationDropdown">
                            <li><a class="dropdown-item" href="#">No Notifications</a></li>
                        </ul>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <img src="{{ session('avatar_url', asset('avatars/default-avatar.png')) }}" alt="Avatar" class="avatar me-2">
                        <a class="nav-link" href="#" id="profile-link" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="fas fa-user"></i> Welcome, {{ Auth::user()->full_name }}!
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

    <!-- Main Content -->
    <div class="container animate__animated animate__fadeInUp">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Profile Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
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
                            @if(Auth::user()->role === 'superadmin')
                            <tr>
                                <th scope="row">Access Level</th>
                                <td>Companies and Their Admins</td>
                            </tr>
                            @else
                            <tr>
                                <th scope="row">Company ID</th>
                                <td>{{ Auth::user()->company_id ?? 'N/A' }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <hr>
                    <h6>Edit Profile</h6>
                    <form action="{{ route('users.update', Auth::user()->user_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ Auth::user()->username }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ Auth::user()->full_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Company ID</label>
                            <input type="number" class="form-control" id="company_id" name="company_id" value="{{ Auth::user()->company_id }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                    <hr>
                    <h6>Upload Avatar</h6>
                    <form action="{{ route('avatar.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Choose Avatar</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload Avatar</button>
                    </form>
                    <hr>
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">Logout</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let notifications = [];

        function handleSearch(event) {
            event.preventDefault();
            const query = document.getElementById('searchInput').value;
            // Implement your search logic here
            console.log('Searching for:', query);
        }

        function addNotification(message) {
            notifications.push(message);
            updateNotificationCount();
            updateNotificationList();
        }

        function updateNotificationCount() {
            document.getElementById('notificationCount').innerText = notifications.length;
        }

        function updateNotificationList() {
            const notificationList = document.getElementById('notificationList');
            notificationList.innerHTML = ''; // Clear existing notifications
            notifications.forEach((notification) => {
                const listItem = document.createElement('li');
                listItem.innerHTML = `<a class="dropdown-item" href="#">${notification}</a>`;
                notificationList.appendChild(listItem);
            });
            if (notifications.length === 0) {
                notificationList.innerHTML = '<li><a class="dropdown-item" href="#">No Notifications</a></li>';
            }
        }

        // Example of adding notifications for all users
        addNotification('New message received');
        addNotification('Your profile has been updated');
        addNotification('System maintenance scheduled for tonight at 11 PM');
    </script>
</body>

</html>