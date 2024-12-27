{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integrated Business Mngt System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        nav {
            background: #0056b3;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        nav ul li {
            margin-left: 20px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
            font-weight: 500;
        }
        nav a:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
        }
        .container {
            max-width: 1200px;
            margin: 80px auto 20px; /* Add margin-top to account for fixed navbar */
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            margin-bottom: 15px;
            text-align: center;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .card {
            background: #e9ecef;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid transparent;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid #007bff;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
        @media (max-width: 600px) {
            nav ul {
                flex-direction: column;
                align-items: flex-start;
            }
            nav ul li {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to Integrated Business Management System</h1>
</header>

<nav>
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="login">Login</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Modules</h2>
    <div class="grid">
        <div class="card"><i class="fas fa-check-circle"></i> Purchase/Payment/Leave Request</div>
        <div class="card"><i class="fas fa-file-archive"></i> Document Archiving</div>
        <div class="card"><i class="fas fa-box"></i> Inventory Management</div>
        <div class="card"><i class="fas fa-chart-line"></i> Budget Forecasting</div>
        <div class="card"><i class="fas fa-money-check-alt"></i> Payroll Management</div>
        <div class="card"><i class="fas fa-tasks"></i> Employee Planning & Reporting</div>
    </div>

    <h2>Actors and Their Access</h2>
    <div class="grid">
        <div class="card">
            <strong>Employee:</strong>
            <ul>
                <li>Role: Request leave, payment, purchase, and send weekly plan and report.</li>
                <li>Dashboard: Requests, plan and report.</li>
            </ul>
        </div>
        <div class="card">
            <strong>Storekeeper:</strong>
            <ul>
                <li>Role: Manage inventories (Stock in/out).</li>
                <li>Dashboard: Requests, plan and report, inventories.</li>
            </ul>
        </div>
        <div class="card">
            <strong>Finance:</strong>
            <ul>
                <li>Role: Review payment requests, send requests and payroll for approval, forecast budget.</li>
                <li>Dashboard: Requests, plan and report, payroll, inventories (view only), budget forecasting, archived documents.</li>
            </ul>
        </div>
        <div class="card">
            <strong>Manager:</strong>
            <ul>
                <li>Role: Approve purchase, leave, payment, and stock out requests.</li>
                <li>Dashboard: Requests, plan and report, archived documents, inventories, forecasted budgets.</li>
            </ul>
        </div>
        <div class="card">
            <strong>Admin:</strong>
            <ul>
                <li>Role: Manage user profiles, user creation, password management, role management, manage request form templates.</li>
                <li>Dashboard: Users, forms.</li>
            </ul>
        </div>
        <div class="card">
            <strong>Super Admin:</strong>
            <ul>
                <li>Role: Register companies and assign admin users.</li>
                <li>Dashboard: Companies, admin-users (login only).</li>
            </ul>
        </div>
    </div>
</div>

<footer>
    &copy; 2024 Management System. All Rights Reserved.
</footer>

</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integrated Business Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

<header class="bg-blue-600 text-white p-5 text-center">
    <h1 class="text-2xl font-bold">Welcome to Integrated Business Management System</h1>
</header>

<nav class="bg-blue-700 p-3 sticky top-0 z-50 shadow">
    <ul class="flex justify-end space-x-5">
        <li><a href="#" class="text-white hover:bg-white hover:bg-opacity-30 rounded px-3 py-2 transition">Home</a></li>
        <li><a href="#" class="text-white hover:bg-white hover:bg-opacity-30 rounded px-3 py-2 transition">About</a></li>
        <li><a href="#" class="text-white hover:bg-white hover:bg-opacity-30 rounded px-3 py-2 transition">Contact</a></li>
        <li><a href="login" class="text-white hover:bg-white hover:bg-opacity-30 rounded px-3 py-2 transition">Login</a></li>
    </ul>
</nav>

<div class="container mx-auto mt-20 mb-5 p-5 bg-white rounded-lg shadow-lg">
    <h2 class="text-blue-600 text-xl font-semibold text-center mb-5">Modules</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-gray-300 rounded-lg p-5 text-center transition transform hover:shadow-lg hover:translate-y-1">
            <i class="fas fa-check-circle"></i> Purchase/Payment/Leave Request
        </div>
        <div class="bg-gray-300 rounded-lg p-5 text-center transition transform hover:shadow-lg hover:translate-y-1">
            <i class="fas fa-file-archive"></i> Document Archiving
        </div>
        <div class="bg-gray-300 rounded-lg p-5 text-center transition transform hover:shadow-lg hover:translate-y-1">
            <i class="fas fa-box"></i> Inventory Management
        </div>
        <div class="bg-gray-300 rounded-lg p-5 text-center transition transform hover:shadow-lg hover:translate-y-1">
            <i class="fas fa-chart-line"></i> Budget Forecasting
        </div>
        <div class="bg-gray-300 rounded-lg p-5 text-center transition transform hover:shadow-lg hover:translate-y-1">
            <i class="fas fa-money-check-alt"></i> Payroll Management
        </div>
        <div class="bg-gray-300 rounded-lg p-5 text-center transition transform hover:shadow-lg hover:translate-y-1">
            <i class="fas fa-tasks"></i> Employee Planning & Reporting
        </div>
    </div>

    <h2 class="text-blue-600 text-xl font-semibold text-center mt-10 mb-5">Actors and Their Access</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-gray-300 rounded-lg p-5">
            <strong>Employee:</strong>
            <ul>
                <li>Role: Request leave, payment, purchase, and send weekly plan and report.</li>
                <li>Dashboard: Requests, plan and report.</li>
            </ul>
        </div>
        <div class="bg-gray-300 rounded-lg p-5">
            <strong>Storekeeper:</strong>
            <ul>
                <li>Role: Manage inventories (Stock in/out).</li>
                <li>Dashboard: Requests, plan and report, inventories.</li>
            </ul>
        </div>
        <div class="bg-gray-300 rounded-lg p-5">
            <strong>Finance:</strong>
            <ul>
                <li>Role: Review payment requests, send requests and payroll for approval, forecast budget.</li>
                <li>Dashboard: Requests, plan and report, payroll, inventories (view only), budget forecasting, archived documents.</li>
            </ul>
        </div>
        <div class="bg-gray-300 rounded-lg p-5">
            <strong>Manager:</strong>
            <ul>
                <li>Role: Approve purchase, leave, payment, and stock out requests.</li>
                <li>Dashboard: Requests, plan and report, archived documents, inventories, forecasted budgets.</li>
            </ul>
        </div>
        <div class="bg-gray-300 rounded-lg p-5">
            <strong>Admin:</strong>
            <ul>
                <li>Role: Manage user profiles, user creation, password management, role management, manage request form templates.</li>
                <li>Dashboard: Users, forms.</li>
            </ul>
        </div>
        <div class="bg-gray-300 rounded-lg p-5">
            <strong>Super Admin:</strong>
            <ul>
                <li>Role: Register companies and assign admin users.</li>
                <li>Dashboard: Companies, admin-users (login only).</li>
            </ul>
        </div>
    </div>
</div>

<footer class="bg-blue-600 text-white py-8 mt-5">
    <div class="container mx-auto text-center">
        <h2 class="text-xl font-semibold mb-2">Thank You for Visiting!</h2>
        <p class="text-sm mb-4">&copy; 2024 RE Technology Solutions PLC. All Rights Reserved.</p>
        <div class="flex justify-center space-x-4 mb-4">
            <a href="#" class="hover:text-blue-300 transition">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="hover:text-blue-300 transition">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="hover:text-blue-300 transition">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="#" class="hover:text-blue-300 transition">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
        <p class="text-xs">Follow us on social media for updates!</p>
    </div>
</footer>

</body>
</html>