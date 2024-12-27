@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Budget Forecasting</h1>
    <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary mb-3">Add Budget</a>
    
    <table class="table">
        <thead>
            <tr>
                <th>Period</th>
                <th>Forecast Amount</th>
                <th>Actual Amount</th>
                <th>Description</th>
                <th>Comment</th>
                <th>Approved By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($budgets as $budget)
            <tr>
                <td>{{ $budget->forecast_period }}</td>
                <td>{{ $budget->forecast_amount }}</td>
                <td>{{ $budget->actual_amount }}</td>
                <td>{{ $budget->description }}</td>
                <td>{{ $budget->comment }}</td>
                <td>{{ $budget->approver ? $budget->approver->full_name : 'N/A' }}</td>

                <td>
                    <a href="{{ route('finance.budgets.edit', $budget->budget_id) }}" class="btn btn-secondary">Edit</a>
                    <form action="{{ route('finance.budgets.destroy', $budget->budget_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection