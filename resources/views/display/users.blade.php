{{-- <div class="mt-4">
    <h2>{{ Auth::user()->full_name }} Manage Users</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Create User</a>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Company ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->company_id }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> --}}

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <div class="card text-bg-primary mb-4" style="max-width: 600px; margin: auto; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);">
                <div class="card-body">
                    <h1 class="text-white" style="font-size: 1.5rem;">
                        <i class="bi bi-building" style="font-size: 1.3rem;"></i> Welcome to {{ Auth::user()->company->company_name }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card text-bg-success shadow-sm text-center" style="border-radius: 10px; box-shadow: 0 4px px rgba(0, 0, 0, 0.2);">
                <div class="card-body">
                    <i class="bi bi-person-fill" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <h5 class="card-title" style="font-size: 1rem;">Total Users</h5>
                    <p class="card-text" style="font-size: 1.5rem;">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <h2 class="text-secondary mb-3" style="font-size: 2rem;">{{ Auth::user()->full_name }} Manage Users</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3" style="border-radius: 20px; padding: 10px 20px; font-size: 1.2rem;">
            <i class="bi bi-person-plus"></i> Create User
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Company ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->company_id }}</td>
                    <td>
                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 20px;" onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection