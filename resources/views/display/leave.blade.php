{{-- @extends('layouts.app')

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
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <h2 class="text-center text-primary mb-4">{{ Auth::user()->company->company_name }} Leave Requests</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
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
                @auth
                    @php
                        $user = auth()->user();
                        $companyId = $user->company_id ?? null;

                        if ($companyId) {
                            $filteredLeaveRequests = $leaveRequests->filter(function ($request) use ($companyId) {
                                return optional($request->user)->company_id == $companyId;
                            });
                        } else {
                            $filteredLeaveRequests = collect();
                        }
                    @endphp

                    @if($filteredLeaveRequests->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center text-danger">No leave requests found.</td>
                        </tr>
                    @else
                        @foreach($filteredLeaveRequests as $request)
                        <tr class="align-middle" style="transition: background-color 0.3s;">
                            <td>{{ $request->start_date }}</td>
                            <td>{{ $request->end_date }}</td>
                            <td>{{ $request->reason }}</td>
                            <td>
                                <span class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                            <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $request->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td colspan="7" class="text-center">
                            <a href="{{ route('login') }}" class="btn btn-primary">Please log in to view your leave requests.</a>
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