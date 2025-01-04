@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Add Budget Forecasting</h1>

    <form action="{{ route('finance.budgets.store') }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
        @csrf
        <div class="form-group mb-3">
            <label for="forecast_period">Forecast Period</label>
            <input type="text" name="forecast_period" class="form-control" required placeholder="Enter forecast period">
        </div>
        <div class="form-group mb-3">
            <label for="forecast_amount">Forecast Amount</label>
            <input type="number" name="forecast_amount" class="form-control" required step="0.01" placeholder="Enter forecast amount">
        </div>
        <div class="form-group mb-3">
            <label for="actual_amount">Actual Amount</label>
            <input type="number" name="actual_amount" class="form-control" required step="0.01" placeholder="Enter actual amount">
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" placeholder="Add a description"></textarea>
        </div>
        <div class="form-group mb-4">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" placeholder="Add a comment"></textarea>
        </div>
    
        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-plus-circle"></i> Add Budget
        </button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary btn-lg w-100 mt-3">Cancel</a>
    </form>
</div>
@endsection