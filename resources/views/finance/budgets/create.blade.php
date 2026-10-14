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
    <h1>Add Budget Forecasting</h1>
    <p>Create a new budget forecast entry</p>
</div>

<form action="{{ route('finance.budgets.store') }}" method="POST">
    @csrf
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-wallet"></i></div>
            <div>
                <h5>Budget Forecast Details</h5>
                <p>Fill in the information below</p>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">1</span> Forecast Information</h6>

            <div class="mb-3">
                <label for="forecast_period" class="form-label">Forecast Period</label>
                <select name="forecast_period" class="form-select" required>
                    <option value="" disabled selected>Select forecast period</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="annually">Annually</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="budget_item" class="form-label">Budget Item</label>
                <input type="text" name="budget_item" class="form-control" placeholder="Enter budget item">
            </div>

            <div class="mb-0">
                <label for="budget_description" class="form-label">Budget Description</label>
                <textarea name="budget_description" class="form-control" rows="3" placeholder="Add a description"></textarea>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">2</span> Allocation Details</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="budget_code" class="form-label">Budget Code</label>
                    <input type="text" name="budget_code" class="form-control" placeholder="Enter budget code">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" placeholder="Enter department">
                </div>
            </div>

            <div class="mb-0">
                <label for="budget_source" class="form-label">Budget Source</label>
                <input type="text" name="budget_source" class="form-control" placeholder="Enter budget source">
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">3</span> Quantity & Cost</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" placeholder="Enter quantity">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Unit</label>
                    <select name="unit" class="form-select" required>
                        <option value="" disabled selected>Select unit</option>
                        <option value="pcs">Pieces</option>
                        <option value="kg">Kilograms</option>
                        <option value="liters">Liters</option>
                        <option value="boxes">Boxes</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-0">
                    <label for="unit_cost" class="form-label">Unit Cost</label>
                    <input type="number" name="unit_cost" class="form-control" step="0.01" placeholder="Enter unit cost">
                </div>
                <div class="col-md-6 mb-0">
                    <label for="currency" class="form-label">Currency</label>
                    <select name="currency" class="form-select" required>
                        <option value="" disabled selected>Select currency</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="ETB">ETB</option>
                        <option value="UGX">UGX</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">4</span> Approver</h6>

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
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-check2-circle"></i> Add Budget
            </button>
        </div>
    </div>
</form>
@endsection