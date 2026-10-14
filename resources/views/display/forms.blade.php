@extends('layouts.app')

@section('content')
<style>
    .page-head {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .page-head h1 { font-size: 1.6rem; font-weight: 800; margin: 0; color: var(--text-primary); }
    .page-head p { color: var(--text-secondary); margin: 4px 0 0; font-size: 0.88rem; }

    .welcome-banner {
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        border-radius: var(--radius-lg); padding: 26px 30px; color: #fff;
        margin-bottom: 22px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
        box-shadow: 0 18px 40px rgba(99,102,241,0.28);
        position: relative; overflow: hidden;
    }
    .welcome-banner::after {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,0.12);
    }
    .welcome-banner h2 { font-size: 1.2rem; font-weight: 700; margin: 0 0 4px; position: relative; z-index: 1; }
    .welcome-banner p { margin: 0; font-size: 0.86rem; opacity: 0.9; position: relative; z-index: 1; }

    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 18px; border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; font-weight: 600; font-size: 0.88rem;
        border: none; box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        transition: var(--transition);
    }
    .btn-add:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(99,102,241,0.45); }

    .table-card {
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);
    }
    .table-modern { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; }
    .table-modern thead th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        color: var(--text-secondary);
        font-weight: 700; font-size: 0.73rem;
        text-transform: uppercase; letter-spacing: 0.08em;
        padding: 14px 18px; text-align: left;
        border-bottom: 1px solid var(--border-color); white-space: nowrap;
    }
    [data-bs-theme="dark"] .table-modern thead th { background: #0b1120; }
    .table-modern tbody td {
        padding: 16px 18px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.88rem; color: var(--text-primary); vertical-align: middle;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .form-name { font-weight: 600; color: var(--text-primary); }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 8px 12px; border-radius: 10px;
        font-size: 0.82rem; font-weight: 600; border: none; cursor: pointer;
        transition: var(--transition);
    }
    .action-btn.view   { background: #dbeafe; color: #1e40af; }
    .action-btn.view:hover { background: #bfdbfe; transform: translateY(-1px); }
    .action-btn.edit   { background: #fef3c7; color: #92400e; }
    .action-btn.edit:hover { background: #fde68a; transform: translateY(-1px); }
    .action-btn.delete { background: #fee2e2; color: #991b1b; }
    .action-btn.delete:hover { background: #fecaca; transform: translateY(-1px); }

    .fields-modal-pre {
        background: #0f172a; color: #a5f3fc;
        padding: 18px; border-radius: 12px;
        font-family: 'SF Mono', Consolas, monospace;
        font-size: 0.82rem; line-height: 1.6;
        overflow-x: auto; max-height: 400px;
        border: 1px solid var(--border-color);
    }
    [data-bs-theme="dark"] .fields-modal-pre { background: #0b1120; }

    @media (max-width: 900px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 800px; }
    }
</style>

<div class="welcome-banner">
    <div>
        <h2><i class="bi bi-file-earmark-text me-1"></i> Welcome to {{ Auth::user()->company->company_name }}</h2>
        <p>Manage reusable request form templates</p>
    </div>
</div>

<div class="page-head">
    <div>
        <h1>Manage Request Form Templates</h1>
        <p>Create and manage dynamic form templates for requests</p>
    </div>
    <a href="{{ route('templates.create') }}" class="btn-add">
        <i class="bi bi-plus-circle"></i> Create Template
    </a>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Form Name</th>
                <th>Fields</th>
                <th>Created By</th>
                <th>Company ID</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($templates as $template)
                <tr>
                    <td><span class="form-name">{{ $template->form_name }}</span></td>
                    <td>
                        <button type="button" class="action-btn view" data-bs-toggle="modal" data-bs-target="#fieldsModal{{ $template->form_id }}">
                            <i class="bi bi-eye"></i> View Fields
                        </button>

                        <div class="modal fade" id="fieldsModal{{ $template->form_id }}" tabindex="-1" aria-labelledby="fieldsModalLabel{{ $template->form_id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="fieldsModalLabel{{ $template->form_id }}">
                                            <i class="bi bi-code-square me-2"></i>Fields for {{ $template->form_name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <pre class="fields-modal-pre">{{ json_encode(json_decode($template->fields), JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $template->created_by }}</td>
                    <td><span class="text-muted">#{{ $template->company_id }}</span></td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('templates.edit', $template->form_id) }}" class="action-btn edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('templates.destroy', $template->form_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this template?');">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection