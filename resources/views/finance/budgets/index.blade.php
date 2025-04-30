@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-info mb-4">Budget Forecasting</h1>
        <div class="text-center mb-3">
            <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add Budget
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Reference Number</th>
                        <th>Forecast Period</th>
                        <th>Budget Description</th>
                        <th>Budget Item</th>
                        <th>Budget Code</th>
                        <th>Department</th>
                        <th>Budget Source</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Unit Cost</th>
                        <th>Currency</th>
                        <th>Total Budget</th>
                        <th>Status</th>
                        <th>Manager Comments</th>
                        <th>Created_by</th>
                        <th>Approved By</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($budgets as $budget)
                        <tr>
                            <td>{{ $budget->reference_number }}</td>
                            <td>{{ $budget->forecast_period }}</td>
                            <td>{{ $budget->budget_description }}</td>
                            <td>{{ $budget->budget_item }}</td>
                            <td>{{ $budget->budget_code }}</td>
                            <td>{{ $budget->department }}</td>
                            <td>{{ $budget->budget_source }}</td>
                            <td>{{ $budget->quantity }}</td>
                            <td>{{ $budget->unit }}</td>
                            <td>{{ number_format($budget->unit_cost, 2) }}</td>
                            <td>{{ $budget->currency }}</td>
                            <td>{{ number_format($budget->total_budget, 2) }} {{ $budget->currency }}</td>
                            <td>
                                <span class="badge {{ $budget->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($budget->status) }}
                                </span>
                            </td>
                            <td>{{ $budget->manager_comment ?? 'N/A' }}</td>
                            <td>{{ $budget->created_by }}</td>
                            <td>{{ $budget->approver ? $budget->approver->full_name : 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if ($budget->status !== 'approved')
                                        <a href="{{ route('finance.budgets.edit', $budget) }}"
                                            class="btn btn-warning btn-sm me-2" title="Edit Budget">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('finance.budgets.destroy', $budget) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this budget item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm me-2" title="Delete Budget">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="btn btn-warning btn-sm me-2 disabled"
                                            title="Cannot edit approved budgets">
                                            <i class="bi bi-pencil"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm me-2 disabled"
                                            title="Cannot delete approved budgets">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                    @endif
                                    <a href="{{ route('finance.budgets.downloadBudgetPdf', $budget) }}"
                                        class="btn btn-info btn-sm" title="Download PDF">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if ($budgets->isEmpty())
                        <tr>
                            <td colspan="15" class="text-center">No budget items found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .table-hover tr:hover {
            background-color: #f8f9fa;
            /* Light grey on hover */
            cursor: pointer;
            /* Pointer cursor on hover */
        }
    </style>
@endsection
