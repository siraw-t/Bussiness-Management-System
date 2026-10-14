@extends('layouts.app')

@section('content')
<style>
    .form-page-head { text-align: center; margin-bottom: 26px; }
    .form-page-head h1 { font-size: 1.7rem; font-weight: 800; margin: 0 0 6px; color: var(--text-primary); }
    .form-page-head p { color: var(--text-secondary); font-size: 0.9rem; margin: 0; }

    .form-card {
        max-width: 760px; margin: 0 auto;
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

    textarea.form-control {
        font-family: 'SF Mono', Consolas, monospace;
        font-size: 0.85rem;
        line-height: 1.6;
        min-height: 200px;
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
        .form-body { padding: 20px 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Edit Request Form Template</h1>
    <p>Update the configuration for <strong>{{ $template->form_name }}</strong></p>
</div>

<form action="{{ route('templates.update', $template->form_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-pencil-square"></i></div>
            <div>
                <h5>Edit Template</h5>
                <p>Modify the form name or field definitions</p>
            </div>
        </div>

        <div class="form-body">
            <div class="mb-3">
                <label for="form_name" class="form-label">Form Name</label>
                <input type="text" name="form_name" class="form-control" value="{{ $template->form_name }}" required>
            </div>

            <div class="mb-0">
                <label for="fields" class="form-label">Fields (JSON)</label>
                <textarea name="fields" class="form-control" required>{{ $template->fields }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('display.forms') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-save2"></i> Update Template
            </button>
        </div>
    </div>
</form>
@endsection