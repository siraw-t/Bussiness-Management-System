@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Budget Forecasting</h1>

    <form action="{{ route('finance.budgets.update', $budget->budget_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="forecast_period">Forecast Period</label>
            <input type="text" name="forecast_period" class="form-control" value="{{ $budget->forecast_period }}" required>
        </div>
        <div class="form-group">
            <label for="forecast_amount">Forecast Amount</label>
            <input type="number" name="forecast_amount" class="form-control" value="{{ $budget->forecast_amount }}" required step="0.01">
        </div>
        <div class="form-group">
            <label for="actual_amount">Actual Amount</label>
            <input type="number" name="actual_amount" class="form-control" value="{{ $budget->actual_amount }}" required step="0.01">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control">{{ $budget->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control">{{ $budget->comment }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Budget</button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection