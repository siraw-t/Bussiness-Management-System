@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Budget Forecasting</h1>

    <form action="{{ route('finance.budgets.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="forecast_period">Forecast Period</label>
            <input type="text" name="forecast_period" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="forecast_amount">Forecast Amount</label>
            <input type="number" name="forecast_amount" class="form-control" required step="0.01">
        </div>
        <div class="form-group">
            <label for="actual_amount">Actual Amount</label>
            <input type="number" name="actual_amount" class="form-control" required step="0.01">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control"></textarea>
        </div>
        {{-- <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" selected>Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div> --}}
        <button type="submit" class="btn btn-primary">Add Budget</button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection