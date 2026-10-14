@extends('layouts.app')

@section('content')
<style>
    .form-page-head { text-align: center; margin-bottom: 26px; }
    .form-page-head h1 {
        font-size: 1.7rem; font-weight: 800; margin: 0 0 6px; color: var(--text-primary);
    }
    .form-page-head p { color: var(--text-secondary); font-size: 0.9rem; margin: 0; }

    .form-card {
        max-width: 720px; margin: 0 auto;
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
    <h1>Edit Weekly Plan</h1>
    <p>Update your plan for <strong>{{ $weeklyPlan->week }}</strong></p>
</div>

<form action="{{ route('weekly-plan.update', $weeklyPlan->plan_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h5>Weekly Plan Details</h5>
                <p>Modify the fields you want to change</p>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">1</span> Plan Information</h6>

            <div class="mb-3">
                <label for="week" class="form-label">Week (YYYY-MM-DD)</label>
                <input type="date" name="week" class="form-control"
                    value="{{ \Carbon\Carbon::parse(old('week', $weeklyPlan->week))->format('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label for="plan" class="form-label">Plan</label>
                <textarea name="plan" class="form-control" rows="5" required>{{ old('plan', $weeklyPlan->plan) }}</textarea>
            </div>

            <div class="mb-0">
                <label for="urgent_issues" class="form-label">Urgent Issues</label>
                <textarea name="urgent_issues" class="form-control" rows="3">{{ old('urgent_issues', $weeklyPlan->urgent_issues) }}</textarea>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">2</span> Approver</h6>

            <div class="mb-0">
                <label for="manager_id" class="form-label">Select Manager</label>
                <select name="approved_by" class="form-select" required>
                    <option value="">Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}"
                            {{ $manager->user_id == $weeklyPlan->approved_by ? 'selected' : '' }}>
                            {{ $manager->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('weekly-plan.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-save2"></i> Update Plan
            </button>
        </div>
    </div>
</form>
@endsection