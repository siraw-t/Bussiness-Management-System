@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-warning mb-4">Edit Payroll</h1>

    <form action="{{ route('finance.payrolls.update', $payroll->payroll_id) }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
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
        <div class="form-group mb-3">
            <label for="pay_period">Pay Period</label>
            <input type="text" name="pay_period" class="form-control" value="{{ $payroll->pay_period }}" required placeholder="Enter pay period">
        </div>
        <div class="form-group mb-3">
            <label for="basic_salary">Basic Salary</label>
            <input type="number" name="basic_salary" class="form-control" value="{{ $payroll->basic_salary }}" required step="0.01" placeholder="Enter basic salary">
        </div>
        <div class="form-group mb-3">
            <label for="allowances">Allowances</label>
            <input type="number" name="allowances" class="form-control" value="{{ $payroll->allowances }}" step="0.01" placeholder="Enter allowances">
        </div>
        <div class="form-group mb-3">
            <label for="deductions">Deductions</label>
            <input type="number" name="deductions" class="form-control" value="{{ $payroll->deductions }}" step="0.01" placeholder="Enter deductions">
        </div>
        <div class="form-group mb-3">
            <label for="net_salary">Net Salary</label>
            <input type="number" name="net_salary" class="form-control" value="{{ $payroll->net_salary }}" required step="0.01" placeholder="Enter net salary">
        </div>
        <div class="form-group mb-4">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $payroll->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $payroll->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $payroll->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <button type="submit" class="btn btn-warning btn-lg w-100">
            <i class="bi bi-pencil-square"></i> Update Payroll
        </button>
        <a href="{{ route('finance.payrolls.index') }}" class="btn btn-secondary btn-lg w-100 mt-3">Cancel</a>
    </form>
</div>
@endsection