@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-warning mb-4">Edit Payroll</h1>

        <form action="{{ route('finance.payrolls.update', $payroll->payroll_id) }}" method="POST"
            class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="employee_id">Employee</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">-- Select Employee --</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->user_id }}"
                            {{ $employee->user_id == $payroll->employee_id ? 'selected' : '' }}>
                            {{ $employee->username }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="pay_period">Pay Period</label>
                <input type="text" name="pay_period" class="form-control" value="{{ $payroll->pay_period }}" required
                    placeholder="Enter pay period">
            </div>

            <div class="form-group mb-3">
                <label for="job_title">Job Title</label>
                <input type="text" name="job_title" class="form-control" value="{{ $payroll->job_title }}" required
                    placeholder="Enter job title">
            </div>

            <div class="form-group mb-3">
                <label for="no_of_days">Number of Days</label>
                <input type="number" name="no_of_days" class="form-control" value="{{ $payroll->no_of_days }}" required
                    placeholder="Enter number of days">
            </div>

            <div class="form-group mb-3">
                <label for="basic_salary">Basic Salary</label>
                <input type="number" name="basic_salary" class="form-control" value="{{ $payroll->basic_salary }}" required
                    step="0.01" placeholder="Enter basic salary">
            </div>

            <div class="form-group mb-3">
                <label for="transport_allowance">Transport Allowance</label>
                <input type="number" name="transport_allowance" class="form-control"
                    value="{{ $payroll->transport_allowance }}" step="0.01" placeholder="Enter transport allowance">
            </div>

            <div class="form-group mb-3">
                <label for="taxable_transport_allowance">Taxable Transport Allowance</label>
                <input type="number" name="taxable_transport_allowance" class="form-control"
                    value="{{ $payroll->taxable_transport_allowance }}" step="0.01"
                    placeholder="Enter taxable transport allowance">
            </div>

            <div class="form-group mb-3">
                <label for="position_allowance">Position Allowance</label>
                <input type="number" name="position_allowance" class="form-control"
                    value="{{ $payroll->position_allowance }}" step="0.01" placeholder="Enter position allowance">
            </div>

            <div class="form-group mb-3">
                <label for="salary_advance">Salary Advance</label>
                <input type="number" name="salary_advance" class="form-control" value="{{ $payroll->salary_advance }}"
                    step="0.01" placeholder="Enter salary advance">
            </div>

            <div class="form-group mb-3">
                <label for="pension_fund_7">Pension Fund 7%</label>
                <input type="number" name="pension_fund_7" class="form-control" value="{{ $payroll->pension_fund_7 }}"
                    step="0.01" placeholder="Enter pension fund 7%">
            </div>

            <div class="form-group mb-3">
                <label for="pension_fund_11">Pension Fund 11%</label>
                <input type="number" name="pension_fund_11" class="form-control" value="{{ $payroll->pension_fund_11 }}"
                    step="0.01" placeholder="Enter pension fund 11%">
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

    <!-- Optional: Add Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Optional: Add custom CSS for additional effects -->
    <style>
        .form-control:focus {
            box-shadow: none;
            border-color: #80bdff;
            /* Bootstrap primary color */
        }

        .btn-warning {
            transition: background-color 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #ffca2c;
            /* Lighter shade for hover */
        }
    </style>
@endsection
