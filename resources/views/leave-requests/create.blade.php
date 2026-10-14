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

    .form-body { padding: 26px 28px; }

    .form-actions {
        padding: 20px 28px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        display: flex; justify-content: flex-end; gap: 10px;
    }
    [data-bs-theme="dark"] .form-actions { background: #0b1120; }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 26px; border-radius: 12px;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: #fff; border: none; font-weight: 600; font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(16,185,129,0.3);
        transition: var(--transition);
    }
    .btn-submit:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(16,185,129,0.45); }

    .alert-danger {
        background: #fef2f2; color: #991b1b;
        border-left: 4px solid var(--danger);
        border-radius: 10px;
        padding: 14px 18px;
        font-size: 0.85rem;
        margin-bottom: 20px;
    }
    .alert-danger ul { margin: 0; padding-left: 18px; }

    .signature-preview {
        margin-top: 12px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: #fff;
        padding: 8px;
    }

    @media (max-width: 576px) {
        .form-body { padding: 20px 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Create Leave Request</h1>
    <p>Submit a new leave request for approval</p>
</div>

@if ($errors->any())
    <div class="alert-danger" style="max-width:720px;margin:0 auto 20px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-calendar-plus"></i></div>
            <div>
                <h5>Leave Request Details</h5>
                <p>Fill in the information below</p>
            </div>
        </div>

        <div class="form-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="leave_type" class="form-label">Leave Type</label>
                <select name="leave_type" class="form-select" required>
                    <option value="annual_leave">Annual Leave</option>
                    <option value="bereavement_leave">Bereavement Leave</option>
                    <option value="maternity_leave">Maternity Leave</option>
                    <option value="sick_leave">Sick Leave</option>
                    <option value="unpaid_leave">Unpaid Leave</option>
                    <option value="parental_leave">Parental Leave</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="other_reason" class="form-label">If Other, Please Specify</label>
                <input type="text" name="other_reason" class="form-control" placeholder="Enter reason if 'Other' selected">
            </div>

            <div class="mb-3">
                <label for="employee_signature" class="form-label">Upload Your Signature</label>
                <input type="file" name="employee_signature" id="employee_signature" class="form-control" accept="image/*">
                <img id="signature-preview" class="signature-preview" style="display: none; max-width: 100%; height: auto;" alt="Signature preview">
            </div>

            <div class="mb-3">
                <label for="attachment" class="form-label">Attach File</label>
                <input type="file" name="attachment" class="form-control">
            </div>

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
            <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-check2-circle"></i> Submit Request
            </button>
        </div>
    </div>
</form>

<script>
    document.getElementById('employee_signature').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('signature-preview');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection