@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <h2 class="text-center text-info mb-4">{{ Auth::user()->company->company_name }} Budget Forecasting</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Forecast Period</th>
                    <th>Forecast Amount</th>
                    <th>Actual Amount</th>
                    <th>Description</th>
                    <th>Comment</th>
                    <th>Approved By</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $userCompanyId = auth()->user()->company_id; // Get the user's company ID

                    // Filter budget forecasts for users belonging to the same company
                    $filteredBudgetForecasts = $budgetForecasts->filter(function ($forecast) use ($userCompanyId) {
                        return $forecast->user->company_id == $userCompanyId; // Ensure the budget user is in the same company
                    });
                @endphp

                @if($filteredBudgetForecasts->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center">No budget forecasts found.</td>
                    </tr>
                @else
                    @foreach($filteredBudgetForecasts as $forecast)
                    <tr class="align-middle" style="transition: background-color 0.3s;">
                        <td>{{ $forecast->forecast_period }}</td>
                        <td>${{ number_format($forecast->forecast_amount, 2) }}</td>
                        <td>${{ number_format($forecast->actual_amount, 2) }}</td>
                        <td>{{ $forecast->description }}</td>
                        <td>{{ $forecast->comment }}</td>
                        <td>{{ $forecast->approver ? $forecast->approver->full_name : 'N/A' }}</td>
                        <td>{{ $forecast->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $forecast->updated_at->format('Y-m-d H:i:s') }}</td>
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