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

    .btn-pdf {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 7px 12px; border-radius: 10px;
        background: #dbeafe; color: #1e40af;
        font-size: 0.8rem; font-weight: 600;
        border: none; cursor: pointer; transition: var(--transition);
        text-decoration: none;
    }
    .btn-pdf:hover { background: #bfdbfe; color: #1e40af; transform: translateY(-1px); }

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
        <p>All stock requisitions submitted and processed</p>
    </div>
</div>

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Description</th>
                <th>Qty Requested</th>
                <th>Unit</th>
                <th>Qty Approved</th>
                <th>Status</th>
                <th>Requested By</th>
                <th>Approved By</th>
                <th>Manager Comments</th>
                <th>Employee Comments</th>
                <th>Reference</th>
                <th>Country</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th style="text-align:right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @auth
                @php
                    $user = auth()->user();
                    $companyId = $user->company_id ?? null;

                    if ($companyId) {
                        $filteredStockRequisitions = $stockRequisitions->filter(function ($requisition) use ($companyId) {
                            return optional($requisition->user)->company_id == $companyId;
                        });
                    } else {
                        $filteredStockRequisitions = collect();
                    }
                @endphp

                @if ($filteredStockRequisitions->isEmpty())
                    <tr>
                        <td colspan="15">
                            <div class="empty-state">
                                <i class="bi bi-box-seam"></i>
                                <p class="mb-0">No stock requisitions found.</p>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach ($filteredStockRequisitions as $requisition)
                        <tr>
                            <td><strong>{{ $requisition->item_name }}</strong></td>
                            <td>{{ $requisition->description ?? 'N/A' }}</td>
                            <td><span class="qty-pill">{{ $requisition->quantity_requested }}</span></td>
                            <td>{{ $requisition->unit_requested ?? 'N/A' }}</td>
                            <td><span class="qty-pill">{{ $requisition->quantity_approved ?? 'N/A' }}</span></td>
                            <td><span class="status-badge status-{{ $requisition->status }}">{{ ucfirst($requisition->status) }}</span></td>
                            <td>
                                @if ($requisition->user)
                                    <div class="user-cell">
                                        <div class="u-avatar">{{ strtoupper(substr($requisition->user->full_name ?? 'U', 0, 1)) }}</div>
                                        <span class="u-name">{{ $requisition->user->full_name }}</span>
                                    </div>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @php
                                    $approver = \App\Models\User::find($requisition->approved_by);
                                @endphp
                                {{ optional($approver)->full_name ?? 'N/A' }}
                            </td>
                            <td>{{ $requisition->manager_comment ?? 'N/A' }}</td>
                            <td>{{ $requisition->employee_comment ?? 'N/A' }}</td>
                            <td>{{ $requisition->reference_number }}</td>
                            <td>{{ $requisition->company_country }}</td>
                            <td>{{ $requisition->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $requisition->updated_at->format('Y-m-d H:i') }}</td>
                            <td style="text-align:right;">
                                <a href="{{ route('stock_requisitions.downloadPdf', $requisition) }}" class="btn-pdf">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @else
                <tr>
                    <td colspan="15">
                        <div class="empty-state">
                            <i class="bi bi-lock"></i>
                            <p class="mb-2">Please log in to view stock requisitions.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        </div>
                    </td>
                </tr>
            @endauth
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