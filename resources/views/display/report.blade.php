@extends('layouts.app')

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
@endsection