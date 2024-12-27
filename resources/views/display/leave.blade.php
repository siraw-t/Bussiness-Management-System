@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Leave Requests</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Approved By</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Get the currently logged-in user's company ID
                    $companyId = auth()->user()->company_id;

                    // Filter leave requests to show only those from the user's company
                    $filteredLeaveRequests = $leaveRequests->filter(function ($request) use ($companyId) {
                        return $request->user->company_id == $companyId;
                    });
                @endphp

                @if($filteredLeaveRequests->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No leave requests found.</td>
                    </tr>
                @else
                    @foreach($filteredLeaveRequests as $request)
                    <tr>
                        <td>{{ $request->start_date }}</td>
                        <td>{{ $request->end_date }}</td>
                        <td>{{ $request->reason }}</td>
                        <td>{{ $request->status }}</td>
                        <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                        <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $request->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection