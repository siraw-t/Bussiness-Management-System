@extends('layouts.app')

@section('content')
<style>
    .page-head {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; margin-bottom: 22px;
    }
    .page-head h1 {
        font-size: 1.6rem; font-weight: 800; margin: 0;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .page-head p { color: var(--text-secondary); margin: 4px 0 0; font-size: 0.88rem; }

    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 18px; border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; font-weight: 600; font-size: 0.88rem;
        border: none; box-shadow: 0 8px 20px rgba(99,102,241,0.3);
        transition: var(--transition); text-decoration: none;
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
        font-weight: 700; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.08em;
        padding: 14px 16px; text-align: left;
        border-bottom: 1px solid var(--border-color); white-space: nowrap;
    }
    [data-bs-theme="dark"] .table-modern thead th { background: #0b1120; }
    .table-modern tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.85rem; color: var(--text-primary); vertical-align: middle;
    }
    .table-modern tbody tr { transition: background-color 0.15s ease; }
    .table-modern tbody tr:hover { background: var(--primary-soft); }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-cell .u-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem; flex-shrink: 0;
    }
    .user-cell .u-name { font-weight: 600; color: var(--text-primary); white-space: nowrap; }

    .status-badge {
        display: inline-flex; align-items: center;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; text-transform: capitalize;
    }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-pending  { background: #fef3c7; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    [data-bs-theme="dark"] .status-badge { filter: brightness(0.85); }

    .qty-pill {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 5px 12px; border-radius: 999px;
        font-weight: 700; font-size: 0.82rem; min-width: 50px;
        background: var(--primary-soft); color: var(--primary-dark);
    }
    [data-bs-theme="dark"] .qty-pill { color: var(--primary-light); }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 5px;
        padding: 7px 11px; border-radius: 9px;
        font-size: 0.78rem; font-weight: 600; border: none; cursor: pointer;
        transition: var(--transition); text-decoration: none; white-space: nowrap;
    }
    .action-btn.edit   { background: #fef3c7; color: #92400e; }
    .action-btn.edit:hover { background: #fde68a; transform: translateY(-1px); }
    .action-btn.delete { background: #fee2e2; color: #991b1b; }
    .action-btn.delete:hover { background: #fecaca; transform: translateY(-1px); }
    .action-btn.pdf    { background: #dbeafe; color: #1e40af; }
    .action-btn.pdf:hover { background: #bfdbfe; transform: translateY(-1px); }
    .action-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

    .action-group { display: flex; gap: 5px; flex-wrap: wrap; justify-content: flex-end; }

    /* Employee comment form */
    .comment-form { display: flex; flex-direction: column; gap: 6px; min-width: 200px; }
    .comment-form .form-control {
        font-size: 0.82rem; padding: 8px 12px;
    }
    .comment-submit {
        display: inline-flex; align-items: center; justify-content: center; gap: 5px;
        padding: 7px 12px; border-radius: 9px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; border: none; font-weight: 600; font-size: 0.78rem;
        cursor: pointer; transition: var(--transition);
    }
    .comment-submit:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 18px rgba(99,102,241,0.3); }
    .comment-submit:disabled { opacity: 0.45; cursor: not-allowed; }
    .comment-hint { font-size: 0.7rem; color: var(--danger); }

    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-secondary); }
    .empty-state i { font-size: 3rem; opacity: 0.35; margin-bottom: 14px; display: block; }

    @media (max-width: 1200px) {
        .table-card { overflow-x: auto; }
        .table-modern { min-width: 1400px; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Stock Requisitions</h1>
        <p>All requisitions submitted and processed</p>
    </div>
    <a href="{{ route('stock_requisitions.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Create New Requisition
    </a>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Qty Requested</th>
                <th>Unit</th>
                <th>Qty Approved</th>
                <th>Status</th>
                <th>Requested By</th>
                <th>Country</th>
                <th>Manager Comment</th>
                <th>Approved By</th>
                <th>Employee Comment</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requisitions as $requisition)
                <tr>
                    <td>{{ $requisition->reference_number }}</td>
                    <td><strong>{{ $requisition->item_name }}</strong></td>
                    <td>{{ $requisition->description ?? 'N/A' }}</td>
                    <td><span class="qty-pill">{{ $requisition->quantity_requested }}</span></td>
                    <td>{{ $requisition->unit_requested ?? 'N/A' }}</td>
                    <td><span class="qty-pill">{{ $requisition->quantity_approved ?? 'N/A' }}</span></td>
                    <td><span class="status-badge status-{{ $requisition->status }}">{{ ucfirst($requisition->status) }}</span></td>
                    <td>
                        @if ($requisition->requested_by)
                            <div class="user-cell">
                                <div class="u-avatar">{{ strtoupper(substr(optional(\App\Models\User::find($requisition->requested_by))->full_name ?? 'U', 0, 1)) }}</div>
                                <span class="u-name">{{ optional(\App\Models\User::find($requisition->requested_by))->full_name ?? 'N/A' }}</span>
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $requisition->company_country }}</td>
                    <td>{{ $requisition->manager_comment ?? 'N/A' }}</td>
                    <td>
                        @php $approver = \App\Models\User::find($requisition->approved_by); @endphp
                        {{ optional($approver)->full_name ?? 'N/A' }}
                    </td>
                    <td>
                        <form action="{{ route('stock_requisitions.comment', $requisition->stock_requisition_id) }}" method="POST" class="comment-form">
                            @csrf
                            <input type="text" class="form-control" name="employee_comment" list="commentOptions"
                                value="{{ $requisition->employee_comment }}"
                                @if ($requisition->status !== 'approved') disabled @endif
                                pattern="^(received|not_received)$"
                                title="Please type 'received' or 'not_received' only." required>

                            <datalist id="commentOptions">
                                <option value="received">
                                <option value="not_received">
                            </datalist>

                            @if ($requisition->status !== 'approved')
                                <span class="comment-hint">Cannot change until approved by manager.</span>
                            @endif

                            <button type="submit" class="comment-submit"
                                @if ($requisition->status !== 'approved') disabled @endif>
                                <i class="bi bi-send"></i> Submit Comment
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="action-group">
                            @if ($requisition->status === 'approved')
                                <button class="action-btn edit" disabled title="Cannot edit approved requisitions">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @else
                                <a href="{{ route('stock_requisitions.edit', $requisition->stock_requisition_id) }}" class="action-btn edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            <form action="{{ route('stock_requisitions.destroy', $requisition->stock_requisition_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete"
                                    onclick="return confirm('Are you sure you want to delete this requisition?');"
                                    title="Delete"
                                    @if ($requisition->status === 'approved') disabled @endif>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                            <a href="{{ route('stock_requisitions.downloadPdf', $requisition) }}" class="action-btn pdf" title="Download PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13">
                        <div class="empty-state">
                            <i class="bi bi-box-seam"></i>
                            <p class="mb-0">No stock requisitions found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function updateEmployeeComment(requisitionId, commentValue) {
        fetch(`/stock_requisitions/${requisitionId}/updateComment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    employee_comment: commentValue
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                alert('Employee comment updated successfully.');
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }
</script>
@endsection