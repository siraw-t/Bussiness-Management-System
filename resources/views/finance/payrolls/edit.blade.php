@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Payroll</h1>

    <form action="{{ route('finance.payrolls.update', $payroll->payroll_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="employee_id">Employee</label>
            <select name="employee_id" class="form-control" required>
                <option value="">-- Select Employee --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->user_id }}" {{ $employee->user_id == $payroll->employee_id ? 'selected' : '' }}>
                        {{ $employee->username }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="pay_period">Pay Period</label>
            <input type="text" name="pay_period" class="form-control" value="{{ $payroll->pay_period }}" required>
        </div>
        <div class="form-group">
            <label for="basic_salary">Basic Salary</label>
            <input type="number" name="basic_salary" class="form-control" value="{{ $payroll->basic_salary }}" required step="0.01">
        </div>
        <div class="form-group">
            <label for="allowances">Allowances</label>
            <input type="number" name="allowances" class="form-control" value="{{ $payroll->allowances }}" step="0.01">
        </div>
        <div class="form-group">
            <label for="deductions">Deductions</label>
            <input type="number" name="deductions" class="form-control" value="{{ $payroll->deductions }}" step="0.01">
        </div>
        <div class="form-group">
            <label for="net_salary">Net Salary</label>
            <input type="number" name="net_salary" class="form-control" value="{{ $payroll->net_salary }}" required step="0.01">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" selected>Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Payroll</button>
        <a href="{{ route('finance.payrolls.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection