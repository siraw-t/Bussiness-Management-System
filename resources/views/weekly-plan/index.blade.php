@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Weekly Plans</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="text-center mb-4">
        <a href="{{ route('weekly-plan.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-lg"></i> Submit New Weekly Plan
        </a>
    </div>

    <table class="table table-striped table-hover mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Week</th>
                <th>Plan</th>
                <th>Submitted At</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plans as $plan)
                <tr>
                    <td>{{ $plan->week }}</td>
                    <td>{{ $plan->plan }}</td>
                    <td>{{ $plan->submitted_at }}</td>
                    <td>{{ $plan->comment ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('weekly-plan.edit', $plan) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('weekly-plan.destroy', $plan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plan?');">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Optional: Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<style>
    .table-hover tr:hover {
        background-color: #f0f8ff; /* Light blue on hover */
        transition: background-color 0.3s; /* Smooth transition */
        cursor: pointer; /* Pointer cursor on hover */
    }
</style>
@endsection