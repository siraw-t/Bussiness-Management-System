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
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; border: none; font-weight: 600; font-size: 0.9rem;
        box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        transition: var(--transition);
    }
    .btn-submit:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99,102,241,0.45); }

    .current-sig {
        margin-top: 12px;
        padding: 8px;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        display: inline-block;
    }
    .current-sig img { max-width: 180px; height: auto; display: block; }

    @media (max-width: 576px) {
        .form-body { padding: 20px 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Edit Leave Request</h1>
    <p>Update your leave request details</p>
</div>

<form action="{{ route('leave-requests.update', $leaveRequest) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h5>Leave Request Details</h5>
                <p>Modify the fields you want to change</p>
            </div>
        </div>

        <div class="form-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $leaveRequest->start_date }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $leaveRequest->end_date }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="leave_type" class="form-label">Leave Type</label>
                <select name="leave_type" class="form-select" required>
                    <option value="annual_leave" {{ $leaveRequest->leave_type === 'annual_leave' ? 'selected' : '' }}>Annual Leave</option>
                    <option value="bereavement_leave" {{ $leaveRequest->leave_type === 'bereavement_leave' ? 'selected' : '' }}>Bereavement Leave</option>
                    <option value="maternity_leave" {{ $leaveRequest->leave_type === 'maternity_leave' ? 'selected' : '' }}>Maternity Leave</option>
                    <option value="sick_leave" {{ $leaveRequest->leave_type === 'sick_leave' ? 'selected' : '' }}>Sick Leave</option>
                    <option value="unpaid_leave" {{ $leaveRequest->leave_type === 'unpaid_leave' ? 'selected' : '' }}>Unpaid Leave</option>
                    <option value="parental_leave" {{ $leaveRequest->leave_type === 'parental_leave' ? 'selected' : '' }}>Parental Leave</option>
                    <option value="other" {{ $leaveRequest->leave_type === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="other_reason" class="form-label">If Other, Please Specify</label>
                <input type="text" name="other_reason" class="form-control" value="{{ $leaveRequest->other_reason }}">
            </div>

            <div class="mb-3">
                <label for="employee_signature" class="form-label">Upload New Signature</label>
                <input type="file" name="employee_signature" id="employee_signature" class="form-control">
                @if ($leaveRequest->employee_signature)
                    <div class="current-sig">
                        <small class="text-muted d-block mb-2">Current signature:</small>
                        <img src="{{ asset('storage/' . $leaveRequest->employee_signature) }}" alt="Signature">
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="attachment" class="form-label">Attach New File</label>
                <input type="file" name="attachment" class="form-control">
            </div>

            <div class="mb-0">
                <label for="approved_by" class="form-label">Select Manager</label>
                <select name="approved_by" class="form-select" required>
                    <option value="" disabled>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}" {{ $manager->user_id == $leaveRequest->approved_by ? 'selected' : '' }}>
                            {{ $manager->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-save2"></i> Update Request
            </button>
        </div>
    </div>
</form>
@endsection