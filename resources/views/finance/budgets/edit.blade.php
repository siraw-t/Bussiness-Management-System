@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-warning mb-4">Edit Budget Forecasting</h1>

    <form action="{{ route('finance.budgets.update', $budget->budget_id) }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
            <label for="forecast_period">Forecast Period</label>
            <input type="text" name="forecast_period" class="form-control" value="{{ $budget->forecast_period }}" required placeholder="Enter forecast period">
        </div>
        <div class="form-group mb-3">
            <label for="forecast_amount">Forecast Amount</label>
            <input type="number" name="forecast_amount" class="form-control" value="{{ $budget->forecast_amount }}" required step="0.01" placeholder="Enter forecast amount">
        </div>
        <div class="form-group mb-3">
            <label for="actual_amount">Actual Amount</label>
            <input type="number" name="actual_amount" class="form-control" value="{{ $budget->actual_amount }}" required step="0.01" placeholder="Enter actual amount">
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" placeholder="Add a description">{{ $budget->description }}</textarea>
        </div>
        <div class="form-group mb-4">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" placeholder="Add a comment">{{ $budget->comment }}</textarea>
        </div>
        
        <button type="submit" class="btn btn-warning btn-lg w-100">
            <i class="bi bi-pencil-square"></i> Update Budget
        </button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary btn-lg w-100 mt-3">Cancel</a>
    </form>
</div>
@endsection