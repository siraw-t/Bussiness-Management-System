@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Employee Plans</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Plan</th>
                    <th>Submitted At</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Get the currently logged-in user's company ID
                    $companyId = auth()->user()->company_id;

                    // Filter employee plans to show only those from the user's company
                    $filteredEmployeePlans = $employeePlans->filter(function ($plan) use ($companyId) {
                        return $plan->user->company_id == $companyId;
                    });
                @endphp

                @if($filteredEmployeePlans->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">No plans found.</td>
                    </tr>
                @else
                    @foreach($filteredEmployeePlans as $plan)
                    <tr>
                        <td>{{ $plan->week }}</td>
                        <td>{{ $plan->plan }}</td>
                        <td>{{ $plan->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $plan->comment }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection