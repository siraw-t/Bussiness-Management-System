@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Weekly Reports</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="text-center mb-4">
            <a href="{{ route('weekly-reports.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Submit New Weekly Report
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mt-3">
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
                        <th>Reference Number</th>
                        <th>Status</th>
                        <th>Manager Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        @php
                            // // Debugging: Print the employee_id and week for the current report
                            // echo 'Employee ID: ' . $report->employee_id . '<br>';
                            // echo 'Report Week: ' . $report->week . '<br>';

                            // Fetch the plan for the same week as the report
                            $lastWeekPlan = \App\Models\EmployeePlan::where('employee_id', $report->employee_id)
                                ->where('week', $report->week) // Fetch plan for the same week
                                ->orderBy('week', 'desc')
                                ->first();

                            //                             // Debugging: Print the last week's plan
// if ($lastWeekPlan) {
//     echo 'Last Week Plan: ' . $lastWeekPlan->plan . '<br>';
// } else {
//     echo 'No plan found for employee ID ' .
//         $report->employee_id .
//         ' and week ' .
//         $report->week .
//         '<br>';
                            //                             }

                        @endphp
                        <tr>
                            <td>{{ $report->employee->full_name }}</td> <!-- Use the report's user -->
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
                            <td>{{ $report->submitted_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $report->reference_number }}</td>
                            <td>
                                <span
                                    class="badge {{ $report->status === 'approved' ? 'bg-success' : ($report->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td>{{ $report->manager_comment ?? 'N/A' }}</td>
                            <td class="d-flex justify-content-around">
                                <a href="{{ route('weekly-reports.edit', $report) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('weekly-reports.destroy', $report) }}" method="POST"
                                    class="me-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <a href="{{ route('weekly-reports.downloadPdf', $report->report_id) }}"
                                    class="btn btn-info btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
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

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
@endsection
