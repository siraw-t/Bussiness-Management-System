@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Budget Forecasting</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
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
                    <tr>
                        <td>{{ $forecast->forecast_period }}</td>
                        <td>{{ $forecast->forecast_amount }}</td>
                        <td>{{ $forecast->actual_amount }}</td>
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
@endsection