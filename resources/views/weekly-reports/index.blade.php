@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Weekly Reports</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="text-center mb-4">
        <a href="{{ route('weekly-reports.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Submit New Weekly Report
        </a>
    </div>

    <table class="table table-hover mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Week</th>
                <th>Report</th>
                <th>Submitted At</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->week }}</td>
                    <td>{{ $report->report }}</td>
                    <td>{{ $report->submitted_at }}</td>
                    <td>{{ $report->comment ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('weekly-reports.edit', $report) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('weekly-reports.destroy', $report) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<style>
    .table-hover tr:hover {
        background-color: #f8f9fa; /* Light grey on hover */
        cursor: pointer; /* Pointer cursor on hover */
    }
</style>
@endsection