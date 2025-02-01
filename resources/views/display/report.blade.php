@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center text-primary mb-4">{{ Auth::user()->company->company_name }} Employee Reports</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Full Name</th>
                        <th>Week</th>
                        <th>Last Week's Plan</th>
                        <th>Report</th>
                        <th>Tasks In Progress</th>
                        <th>Tasks Completed</th>
                        <th>Self Assessment</th>
                        <th>Submitted At</th>
                        <th>Manager Comment</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($employeeReports->isEmpty())
                        <tr>
                            <td colspan="10" class="text-center">No reports found.</td>
                        </tr>
                    @else
                        @foreach ($employeeReports as $report)
                            @php
                                $lastWeekPlan = \App\Models\EmployeePlan::where('employee_id', $report->employee_id)
                                    ->where('week', $report->week) // Fetch plan for the same week
                                    ->orderBy('week', 'desc')
                                    ->first();
                            @endphp
                            <tr class="align-middle" style="transition: background-color 0.3s;">
                                <td>{{ $report->employee->full_name ?? 'N/A' }}</td>
                                <td>{{ $report->week }}</td>
                                <td>
                                    @if ($lastWeekPlan)
                                        {{ $lastWeekPlan->plan }}
                                    @else
                                        <span class="text-muted">No plan available</span>
                                    @endif
                                </td>
                                <td>{{ $report->report }}</td>
                                <td>{{ $report->tasks_in_progress ?? 'N/A' }}</td>
                                <td>{{ $report->tasks_completed ?? 'N/A' }}</td>
                                <td>{{ $report->self_assessment ?? 'N/A' }}</td>
                                <td>{{ $report->submitted_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $report->manager_comment ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $report->status == 'approved' ? 'bg-success' : ($report->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('weekly-reports.downloadPdf', $report) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="bi bi-download"></i>
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
            /* Light grey on hover */
            cursor: pointer;
            /* Pointer cursor on hover */
            transition: background-color 0.3s;
            /* Smooth transition */
        }
    </style>
@endsection
