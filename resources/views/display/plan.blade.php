@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center text-primary mb-4">{{ Auth::user()->company->company_name }} Employee Plans</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Full Name</th>
                        <th>Week</th>
                        <th>Plan</th>
                        <th>Submitted At</th>
                        <th>Urgent Issues</th>
                        <th>Status</th>
                        <th>Reference Number</th>
                        <th>Manager Comment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($employeePlans->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center">No plans found.</td>
                        </tr>
                    @else
                        @foreach ($employeePlans as $plan)
                            <tr class="align-middle" style="transition: background-color 0.3s;">
                                <td>{{ $plan->employee->full_name ?? 'N/A' }}</td>
                                <td>{{ $plan->week }}</td>
                                <td>{{ $plan->plan }}</td>
                                <td>{{ $plan->submitted_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $plan->urgent_issues ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $plan->status == 'approved' ? 'bg-success' : ($plan->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($plan->status) }}
                                    </span>
                                </td>
                                <td>{{ $plan->reference_number }}</td>
                                <td>{{ $plan->manager_comment ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('weekly-plan.downloadPdf', $plan) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <style>
        .table-hover tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>
@endsection
