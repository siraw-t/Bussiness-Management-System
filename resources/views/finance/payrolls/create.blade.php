@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Add Payroll</h1>

    <form action="{{ route('finance.payrolls.store') }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
        @csrf
        <div class="form-group mb-3">
            <label for="employee_id">Employee</label>
            <select name="employee_id" class="form-control" required>
                <option value="">-- Select Employee --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->user_id }}">{{ $employee->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="pay_period">Pay Period</label>
            <input type="text" name="pay_period" class="form-control" required placeholder="Enter pay period">
        </div>
        <div class="form-group mb-3">
            <label for="basic_salary">Basic Salary</label>
            <input type="number" name="basic_salary" class="form-control" required step="0.01" placeholder="Enter basic salary">
        </div>
        <div class="form-group mb-3">
            <label for="allowances">Allowances</label>
            <input type="number" name="allowances" class="form-control" value="0" step="0.01" placeholder="Enter allowances">
        </div>
        <div class="form-group mb-3">
            <label for="deductions">Deductions</label>
            <input type="number" name="deductions" class="form-control" value="0" step="0.01" placeholder="Enter deductions">
        </div>
        <div class="form-group mb-3">
            <label for="net_salary">Net Salary</label>
            <input type="number" name="net_salary" class="form-control" required step="0.01" placeholder="Enter net salary">
        </div>
        <div class="form-group mb-4">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" selected>Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-plus-circle"></i> Add Payroll
        </button>
        <a href="{{ route('finance.payrolls.index') }}" class="btn btn-secondary btn-lg w-100 mt-3">Cancel</a>
    </form>
</div>
@endsection