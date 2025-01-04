@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <h2 class="text-center text-info mb-4">{{ Auth::user()->company->company_name }} Employee Plans</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
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
                    <tr class="align-middle" style="transition: background-color 0.3s;">
                        <td>{{ $plan->week }}</td>
                        <td>{{ $plan->plan }}</td>
                        <td>{{ $plan->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $plan->comment ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @endif
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
    }
</style>
@endsection