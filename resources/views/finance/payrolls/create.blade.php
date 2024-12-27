@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Payroll</h1>

    <form action="{{ route('finance.payrolls.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="employee_id">Employee</label>
            <select name="employee_id" class="form-control" required>
                <option value="">-- Select Employee --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->user_id }}">{{ $employee->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="pay_period">Pay Period</label>
            <input type="text" name="pay_period" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="basic_salary">Basic Salary</label>
            <input type="number" name="basic_salary" class="form-control" required step="0.01">
        </div>
        <div class="form-group">
            <label for="allowances">Allowances</label>
            <input type="number" name="allowances" class="form-control" value="0" step="0.01">
        </div>
        <div class="form-group">
            <label for="deductions">Deductions</label>
            <input type="number" name="deductions" class="form-control" value="0" step="0.01">
        </div>
        <div class="form-group">
            <label for="net_salary">Net Salary</label>
            <input type="number" name="net_salary" class="form-control" required step="0.01">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" selected>Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Payroll</button>
        <a href="{{ route('finance.payrolls.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection