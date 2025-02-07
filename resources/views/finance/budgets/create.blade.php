@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Add Budget Forecasting</h1>

        <form action="{{ route('finance.budgets.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 500px; margin: auto;">
            @csrf
            <div class="form-group mb-3">
                <label for="forecast_period">Forecast Period</label>
                <select name="forecast_period" class="form-control" required>
                    <option value="" disabled selected>Select forecast period</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="annually">Annually</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="budget_description">Budget Description</label>
                <textarea name="budget_description" class="form-control" placeholder="Add a description"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="budget_item">Budget Item</label>
                <input type="text" name="budget_item" class="form-control" placeholder="Enter budget item">
            </div>
            <div class="form-group mb-3">
                <label for="budget_code">Budget Code</label>
                <input type="text" name="budget_code" class="form-control" placeholder="Enter budget code">
            </div>
            <div class="form-group mb-3">
                <label for="department">Department</label>
                <input type="text" name="department" class="form-control" placeholder="Enter department">
            </div>
            <div class="form-group mb-3">
                <label for="budget_source">Budget Source</label>
                <input type="text" name="budget_source" class="form-control" placeholder="Enter budget source">
            </div>
            <div class="form-group mb-3">
                <label for="quantity_with_unit">Quantity with Unit</label>
                <input type="text" name="quantity_with_unit" class="form-control" placeholder="E.g., 2 pcs">
            </div>
            <div class="form-group mb-3">
                <label for="unit_cost">Unit Cost</label>
                <input type="number" name="unit_cost" class="form-control" step="0.01" placeholder="Enter unit cost">
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">Add Budget</button>
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary btn-lg w-100 mt-3">Cancel</a>
        </form>
    </div>
@endsection
