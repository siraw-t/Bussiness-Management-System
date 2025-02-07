@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center text-info mb-4">{{ Auth::user()->company->company_name }} Budget Forecasting</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Forecast Period</th>
                        <th>Budget Description</th>
                        <th>Budget Item</th>
                        <th>Budget Code</th>
                        <th>Department</th>
                        <th>Budget Source</th>
                        <th>Quantity with Unit</th>
                        <th>Unit Cost</th>
                        <th>Total Budget</th>
                        <th>Status</th>
                        <th>Manager Comment</th>
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

                    @if ($filteredBudgetForecasts->isEmpty())
                        <tr>
                            <td colspan="14" class="text-center">No budget forecasts found.</td>
                        </tr>
                    @else
                        @foreach ($filteredBudgetForecasts as $forecast)
                            <tr class="align-middle" style="transition: background-color 0.3s;">
                                <td>{{ ucfirst($forecast->forecast_period) }}</td>
                                <td>{{ $forecast->budget_description }}</td>
                                <td>{{ $forecast->budget_item }}</td>
                                <td>{{ $forecast->budget_code }}</td>
                                <td>{{ $forecast->department }}</td>
                                <td>{{ $forecast->budget_source }}</td>
                                <td>{{ $forecast->quantity_with_unit }}</td>
                                <td>${{ number_format($forecast->unit_cost, 2) }}</td>
                                <td>${{ number_format($forecast->total_budget, 2) }}</td>
                                <td>
                                    <span
                                        class="badge {{ $forecast->status === 'approved' ? 'bg-success' : ($forecast->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($forecast->status) }}
                                    </span>
                                </td>
                                <td>{{ $forecast->manager_comment ?? 'N/A' }}</td>
                                <td>{{ optional($forecast->approver)->full_name ?? 'N/A' }}</td>
                                <td>{{ $forecast->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $forecast->updated_at->format('Y-m-d H:i:s') }}</td>
                                <td><a href="{{ route('finance.budgets.downloadBudgetPdf', $forecast) }}"
                                        class="btn btn-info">
                                        PDF</a></td>
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
            background-color: #f8f9fa;
            /* Light grey on hover */
            cursor: pointer;
            /* Pointer cursor on hover */
        }
    </style>
@endsection
