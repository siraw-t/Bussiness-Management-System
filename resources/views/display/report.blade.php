{{-- @extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Employee Reports</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Report</th>
                    <th>Comment</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Get the currently logged-in user's company ID
                    $companyId = auth()->user()->company_id;

                    // Filter employee reports to show only those from the user's company
                    $filteredEmployeeReports = $employeeReports->filter(function ($report) use ($companyId) {
                        return $report->user->company_id == $companyId;
                    });
                @endphp

                @if($filteredEmployeeReports->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">No reports found.</td>
                    </tr>
                @else
                    @foreach($filteredEmployeeReports as $report)
                    <tr>
                        <td>{{ $report->week }}</td>
                        <td>{{ $report->report }}</td>
                        <td>{{ $report->comment }}</td>
                        <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <h2 class="text-center text-info mb-4">{{ Auth::user()->company->company_name }} Employee Reports</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Week</th>
                    <th>Report</th>
                    <th>Comment</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                @auth
                    @php
                        // Get the currently logged-in user's company ID safely
                        $user = auth()->user();
                        $companyId = optional($user)->company_id;

                        // Filter employee reports to show only those from the user's company
                        if ($companyId) {
                            $filteredEmployeeReports = $employeeReports->filter(function ($report) use ($companyId) {
                                return optional($report->user)->company_id == $companyId;
                            });
                        } else {
                            $filteredEmployeeReports = collect(); // Empty collection if user is not valid
                        }
                    @endphp

                    @if($filteredEmployeeReports->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center text-danger">No reports found.</td>
                        </tr>
                    @else
                        @foreach($filteredEmployeeReports as $report)
                        <tr class="align-middle" style="transition: background-color 0.3s;">
                            <td>{{ $report->week }}</td>
                            <td>{{ $report->report }}</td>
                            <td>{{ $report->comment ?? 'N/A' }}</td>
                            <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td colspan="4" class="text-center">
                            <a href="{{ route('login') }}" class="btn btn-primary">Please log in to view reports.</a>
                        </td>
                    </tr>
                @endauth
            </tbody>
        </table>
    </div>
</div>

<!-- Optional: Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<!-- Custom CSS for hover effects -->
<style>
    .table-hover tr:hover {
        background-color: #f8f9fa; /* Light grey on hover */
        cursor: pointer; /* Pointer cursor on hover */
        transition: background-color 0.3s; /* Smooth transition */
    }

    .btn {
        transition: transform 0.2s;
    }

    .btn:hover {
        transform: scale(1.05);
    }
</style>
@endsection