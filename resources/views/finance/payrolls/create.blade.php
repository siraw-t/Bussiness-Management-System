@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Add Payroll</h1>

        <form action="{{ route('finance.payrolls.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 500px; margin: auto;">
            @csrf

            <div class="form-group mb-3">
                <label for="employee_id">Employee</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">-- Select Employee --</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->user_id }}">{{ $employee->username }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="pay_period">Pay Period</label>
                <input type="text" name="pay_period" class="form-control" required
                    placeholder="Enter pay period (e.g., January 2025)">
            </div>

            <div class="form-group mb-3">
                <label for="job_title">Job Title</label>
                <input type="text" name="job_title" class="form-control" required placeholder="Enter job title">
            </div>

            <div class="form-group mb-3">
                <label for="no_of_days">Number of Days</label>
                <input type="number" name="no_of_days" class="form-control" required
                    placeholder="Enter number of days worked">
            </div>

            <div class="form-group mb-3">
                <label for="basic_salary">Basic Salary</label>
                <input type="number" name="basic_salary" class="form-control" required step="0.01"
                    placeholder="Enter basic salary">
            </div>

            <div class="form-group mb-3">
                <label for="currency">Currency</label>
                <select name="currency" class="form-control" required>
                    <option value="">-- Select Currency --</option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency }}">{{ $currency }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="transport_allowance">Transport Allowance</label>
                <input type="number" name="transport_allowance" id="transport_allowance" class="form-control"
                    value="0" step="0.01" placeholder="Enter transport allowance"
                    @if ($company->country === 'Uganda') disabled @endif>
            </div>

            <div class="form-group mb-3">
                <label for="taxable_transport_allowance">Taxable Transport Allowance</label>
                <input type="number" name="taxable_transport_allowance" id="taxable_transport_allowance"
                    class="form-control" value="0" step="0.01" placeholder="Enter taxable transport allowance"
                    @if ($company->country === 'Uganda') disabled @endif>
            </div>

            <div class="form-group mb-3">
                <label for="position_allowance">Position Allowance</label>
                <input type="number" name="position_allowance" class="form-control" value="0" step="0.01"
                    placeholder="Enter position allowance">
            </div>

            <div class="form-group mb-3">
                <label for="salary_advance">Salary Advance</label>
                <input type="number" name="salary_advance" class="form-control" value="0" step="0.01"
                    placeholder="Enter salary advance amount">
            </div>

            <div class="form-group mb-3">
                <label for="pension_fund_7">Pension Fund 7%</label>
                <input type="number" name="pension_fund_7" class="form-control" value="0" step="0.01"
                    placeholder="Enter pension fund 7%">
            </div>

            <div class="form-group mb-3">
                <label for="pension_fund_11">Pension Fund 11%</label>
                <input type="number" name="pension_fund_11" class="form-control" value="0" step="0.01"
                    placeholder="Enter pension fund 11%">
            </div>

            <div class="form-group mb-3">
                <label for="approved_by">Select Manager</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled selected>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}">{{ $manager->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="bi bi-plus-circle"></i> Add Payroll
            </button>
            <a href="{{ route('finance.payrolls.index') }}" class="btn btn-secondary btn-lg w-100 mt-3">Cancel</a>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .form-control:focus {
            box-shadow: none;
            border-color: #80bdff;
        }

        .btn-primary {
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #007bff;
        }
    </style>
@endsection
