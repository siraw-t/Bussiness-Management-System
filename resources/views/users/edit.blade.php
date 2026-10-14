@extends('layouts.app')

@section('content')
<style>
    .form-page-head { text-align: center; margin-bottom: 26px; }
    .form-page-head h1 { font-size: 1.7rem; font-weight: 800; margin: 0 0 6px; color: var(--text-primary); }
    .form-page-head p { color: var(--text-secondary); font-size: 0.9rem; margin: 0; }

    .form-card {
        max-width: 700px; margin: 0 auto;
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

    @media (max-width: 576px) {
        .form-body { padding: 20px 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Edit User</h1>
    <p>Update details for <strong>{{ $user->full_name }}</strong></p>
</div>

<form action="{{ route('users.update', $user->user_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-person-gear"></i></div>
            <div>
                <h5>User Information</h5>
                <p>Modify the fields you want to update</p>
            </div>
        </div>

        <div class="form-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $user->full_name }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            @if (Auth::user()->role === 'admin' && Auth::user()->user_id === $user->user_id)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">New Password <span class="text-muted">(optional)</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
            @else
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" class="form-control" id="password" name="password" value="Not editable" readonly>
                    <small class="text-muted">Only the account owner can change the password.</small>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="{{ $user->role }}" selected>{{ ucfirst($user->role) }}</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="company_id" class="form-label">Company ID</label>
                    <input type="number" class="form-control" id="company_id" name="company_id" value="{{ $user->company_id }}" readonly>
                </div>
            </div>

            <div class="mb-0">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                @if ($user->profile_image)
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <small class="text-muted">Current:</small>
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Current" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-soft);">
                    </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('display.users') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-save2"></i> Update User
            </button>
        </div>
    </div>
</form>
@endsection