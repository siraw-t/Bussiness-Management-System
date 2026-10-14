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

    .fields-help {
        background: var(--primary-soft);
        border-left: 3px solid var(--primary);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.82rem;
        color: var(--text-secondary);
        margin-bottom: 12px;
    }
    .fields-help code {
        background: rgba(99,102,241,0.12);
        color: var(--primary-dark);
        padding: 2px 6px; border-radius: 5px;
        font-size: 0.78rem;
    }
    [data-bs-theme="dark"] .fields-help code { color: var(--primary-light); }

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
    <h1>Create Request Form Template</h1>
    <p>Define a reusable form structure using JSON fields</p>
</div>

<form action="{{ route('templates.store') }}" method="POST">
    @csrf
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-file-earmark-plus-fill"></i></div>
            <div>
                <h5>New Form Template</h5>
                <p>Configure the form name and its fields</p>
            </div>
        </div>

        <div class="form-body">
            <div class="mb-3">
                <label for="form_name" class="form-label">Form Name</label>
                <input type="text" name="form_name" class="form-control" placeholder="e.g., Equipment Request Form" required>
            </div>

            <div class="mb-3">
                <label for="fields" class="form-label">Fields (JSON)</label>
                <div class="fields-help">
                    Define each field as JSON. Example:
                    <code>[{"name":"item","type":"text","label":"Item Name","required":true}]</code>
                </div>
                <textarea name="fields" class="form-control" placeholder='[{"name":"field1","type":"text","label":"Field 1","required":true}]' required></textarea>
            </div>

            <input type="hidden" name="company_id" value="1">
        </div>

        <div class="form-actions">
            <a href="{{ route('display.forms') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-check2-circle"></i> Create Template
            </button>
        </div>
    </div>
</form>
@endsection