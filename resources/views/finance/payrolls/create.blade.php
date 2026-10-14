@extends('layouts.app')

@section('content')
<style>
    .form-page-head { text-align: center; margin-bottom: 26px; }
    .form-page-head h1 {
        font-size: 1.7rem; font-weight: 800; margin: 0 0 6px; color: var(--text-primary);
    }
    .form-page-head p { color: var(--text-secondary); font-size: 0.9rem; margin: 0; }

    .form-card {
        max-width: 780px; margin: 0 auto;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .form-header {
        padding: 24px 28px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        display: flex; align-items: center; gap: 14px;
    }
    .form-header .hdr-icon {
        width: 46px; height: 46px; border-radius: 14px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .form-header h5 { margin: 0; font-size: 1.05rem; font-weight: 700; }
    .form-header p { margin: 2px 0 0; font-size: 0.82rem; opacity: 0.9; }

    .form-section {
        padding: 22px 28px;
        border-bottom: 1px solid var(--border-color);
    }
    .form-section:last-of-type { border-bottom: none; }
    .form-section h6 {
        font-size: 0.78rem; text-transform: uppercase;
        letter-spacing: 0.1em; color: var(--text-secondary);
        font-weight: 700; margin: 0 0 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-section h6 .step-num {
        width: 22px; height: 22px; border-radius: 6px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.72rem; font-weight: 700;
    }

    .form-control:disabled {
        background: var(--bg-body);
        opacity: 0.6; cursor: not-allowed;
    }

    .form-actions {
        padding: 20px 28px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        display: flex; justify-content: flex-end; gap: 10px;
    }
    [data-bs-theme="dark"] .form-actions { background: #0b1120; }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 26px; border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; border: none; font-weight: 600; font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        transition: var(--transition);
    }
    .btn-submit:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99,102,241,0.45); }

    @media (max-width: 576px) {
        .form-section { padding: 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Add Payroll</h1>
    <p>Create a new payroll record</p>
</div>

<form action="{{ route('finance.payrolls.store') }}" method="POST">
    @csrf
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-people"></i></div>
            <div>
                <h5>Payroll Details</h5>
                <p>Fill in the information below</p>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">1</span> Employee Information</h6>

            <div class="mb-3">
                <label for="employee_id" class="form-label">Employee</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">-- Select Employee --</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->user_id }}">{{ $employee->username }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pay_period" class="form-label">Pay Period</label>
                    <input type="text" name="pay_period" class="form-control" required placeholder="e.g., January 2025">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="job_title" class="form-label">Job Title</label>
                    <input type="text" name="job_title" class="form-control" required placeholder="Enter job title">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="no_of_days" class="form-label">Number of Days</label>
                    <input type="number" name="no_of_days" class="form-control" required placeholder="Days worked">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <select name="currency" class="form-select" required>
                        <option value="">-- Select Currency --</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency }}">{{ $currency }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-0">
                <label for="basic_salary" class="form-label">Basic Salary</label>
                <input type="number" name="basic_salary" class="form-control" required step="0.01" placeholder="Enter basic salary">
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">2</span> Allowances</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="transport_allowance" class="form-label">Transport Allowance</label>
                    <input type="number" name="transport_allowance" id="transport_allowance" class="form-control"
                        value="0" step="0.01" placeholder="Enter transport allowance"
                        @if ($company->country === 'Uganda') disabled @endif>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="taxable_transport_allowance" class="form-label">Taxable Transport Allowance</label>
                    <input type="number" name="taxable_transport_allowance" id="taxable_transport_allowance"
                        class="form-control" value="0" step="0.01" placeholder="Enter taxable transport allowance"
                        @if ($company->country === 'Uganda') disabled @endif>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="position_allowance" class="form-label">Position Allowance</label>
                    <input type="number" name="position_allowance" class="form-control" value="0" step="0.01" placeholder="Enter position allowance">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="salary_advance" class="form-label">Salary Advance</label>
                    <input type="number" name="salary_advance" class="form-control" value="0" step="0.01" placeholder="Enter salary advance">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-0">
                    <label for="pension_fund_7" class="form-label">Pension Fund 7%</label>
                    <input type="number" name="pension_fund_7" class="form-control" value="0" step="0.01" placeholder="Enter pension fund 7%">
                </div>
                <div class="col-md-6 mb-0">
                    <label for="pension_fund_11" class="form-label">Pension Fund 11%</label>
                    <input type="number" name="pension_fund_11" class="form-control" value="0" step="0.01" placeholder="Enter pension fund 11%">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">3</span> Approver</h6>

            <div class="mb-0">
                <label for="approved_by" class="form-label">Select Manager</label>
                <select name="approved_by" class="form-select" required>
                    <option value="" disabled selected>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}">{{ $manager->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('finance.payrolls.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-plus-circle"></i> Add Payroll
            </button>
        </div>
    </div>
</form>
@endsection