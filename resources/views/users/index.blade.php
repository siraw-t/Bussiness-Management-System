@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="text-center">Welcome to {{ Auth::user()->company->company_name }}</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card text-bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        {{-- <div class="col-md-6 col-lg-4">
            <div class="card text-bg-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Active Users</h5>
                    <p class="card-text">{{ $activeUsers }}</p>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-md-6 col-lg-4">
            <div class="card text-bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Inactive Users</h5>
                    <p class="card-text">{{ $inactiveUsers }}</p>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="mt-4">
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
    </div>
</div>
@endsection