@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-info mb-4">{{ Auth::user()->full_name }}'s Budget Forecasting</h1>
    <div class="text-center mb-3">
        <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Budget
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Period</th>
                    <th>Forecast Amount</th>
                    <th>Actual Amount</th>
                    <th>Description</th>
                    <th>Comment</th>
                    <th>Approved By</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($budgets as $budget)
                <tr>
                    <td>{{ $budget->forecast_period }}</td>
                    <td>{{ number_format($budget->forecast_amount, 2) }}</td>
                    <td>{{ number_format($budget->actual_amount, 2) }}</td>
                    <td>{{ $budget->description }}</td>
                    <td>{{ $budget->comment }}</td>
                    <td>{{ $budget->approver ? $budget->approver->full_name : 'N/A' }}</td>
                    <td class="text-center">
                        <a href="{{ route('finance.budgets.edit', $budget->budget_id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('finance.budgets.destroy', $budget->budget_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this budget?');">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if ($budgets->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No budget items found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<style>
    .table-hover tr:hover {
        background-color: #f8f9fa; /* Light grey on hover */
        cursor: pointer; /* Pointer cursor on hover */
    }
</style>
@endsection