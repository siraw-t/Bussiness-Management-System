@extends('layouts.app')

@section('content')
<style>
    .page-head {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
    }
    .page-head h1 {
        font-size: 1.7rem; font-weight: 800; margin: 0;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .page-head p { color: var(--text-secondary); margin: 4px 0 0; font-size: 0.88rem; }

    .section-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        transition: var(--transition);
    }
    .section-card:hover { box-shadow: var(--shadow); }

    .section-head {
        padding: 18px 24px;
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px;
        border-bottom: 1px solid var(--border-color);
    }
    .section-head .sh-left { display: flex; align-items: center; gap: 14px; }
    .section-head .sh-icon {
        width: 44px; height: 44px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #fff; flex-shrink: 0;
    }
    .section-head .sh-icon.info    { background: linear-gradient(135deg, #0ea5e9, #22d3ee); box-shadow: 0 8px 20px rgba(14,165,233,0.3); }
    .section-head .sh-icon.warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); box-shadow: 0 8px 20px rgba(245,158,11,0.3); }
    .section-head h4 { margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--text-primary); }
    .section-head .sh-count { font-size: 0.78rem; color: var(--text-secondary); margin-top: 2px; }
    .section-head .sh-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 5px 14px; border-radius: 999px;
        font-size: 0.82rem; font-weight: 700;
    }
    .sh-badge.info    { background: #e0f2fe; color: #075985; }
    .sh-badge.warning { background: #fef3c7; color: #92400e; }
    [data-bs-theme="dark"] .sh-badge { filter: brightness(0.85); }

    .section-body {
        padding: 16px 18px;
        display: flex; flex-direction: column; gap: 12px;
    }

    .request-card {
        background: var(--bg-body);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 18px 20px;
        transition: var(--transition);
        position: relative; overflow: hidden;
    }
    .request-card::before {
        content: ''; position: absolute;
        top: 0; left: 0; bottom: 0; width: 4px;
        background: linear-gradient(180deg, var(--primary), var(--accent));
    }
    .request-card.warning::before { background: linear-gradient(180deg, #f59e0b, #fbbf24); }
    .request-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
        border-color: var(--primary-light);
    }
    [data-bs-theme="dark"] .request-card { background: #0b1120; }

    .request-card .rc-head {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 12px; margin-bottom: 12px; flex-wrap: wrap;
    }
    .request-card h5 { font-size: 1rem; font-weight: 700; margin: 0; color: var(--text-primary); }

    .rc-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px 20px;
        margin-bottom: 14px;
    }
    .rc-details .rc-item { font-size: 0.85rem; color: var(--text-primary); }
    .rc-details .rc-item strong {
        display: block; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--text-secondary); font-weight: 700; margin-bottom: 2px;
    }

    .rc-actions { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-review {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 10px;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: #fff; font-size: 0.82rem; font-weight: 600;
        border: none; text-decoration: none;
        box-shadow: 0 6px 14px rgba(16,185,129,0.25);
        transition: var(--transition);
    }
    .btn-review:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 10px 20px rgba(16,185,129,0.4); }

    .attach-link {
        display: inline-flex; align-items: center; gap: 4px;
        color: var(--primary); text-decoration: none; font-weight: 500;
    }
    .attach-link:hover { text-decoration: underline; }

    .empty-state {
        padding: 34px 20px; text-align: center;
        color: var(--text-secondary); font-size: 0.88rem;
    }
    .empty-state i { font-size: 2.2rem; opacity: 0.35; margin-bottom: 10px; display: block; }

    @media (max-width: 576px) {
        .page-head h1 { font-size: 1.4rem; }
        .section-head { padding: 16px 18px; }
        .section-body { padding: 14px; }
        .request-card { padding: 16px; }
        .rc-actions { justify-content: stretch; }
        .btn-review { flex: 1; justify-content: center; }
    }
</style>

<div class="page-head">
    <div>
        <h1>Approved Requests</h1>
        <p>All approved payment and petty cash requests</p>
    </div>
</div>

{{-- Payment Requests --}}
<div class="section-card">
    <div class="section-head">
        <div class="sh-left">
            <div class="sh-icon info"><i class="bi bi-credit-card-2-front"></i></div>
            <div>
                <h4>Payment Requests</h4>
                <div class="sh-count">Approved payment requests</div>
            </div>
        </div>
        <span class="sh-badge info">{{ $paymentRequests->count() }}</span>
    </div>

    <div class="section-body">
        @forelse ($paymentRequests as $request)
            <div class="request-card">
                <div class="rc-head">
                    <h5>{{ $request->item_name }}</h5>
                </div>
                <div class="rc-details">
                    <div class="rc-item">
                        <strong>Full Name</strong>
                        {{ $request->user->full_name }}
                    </div>
                    <div class="rc-item">
                        <strong>Net Payment</strong>
                        {{ $request->currency }} {{ number_format($request->net_payment, 2) }}
                    </div>
                    <div class="rc-item">
                        <strong>Requested Date</strong>
                        {{ $request->request_date->format('d/m/Y') }}
                    </div>
                    <div class="rc-item">
                        <strong>Item Description</strong>
                        {{ $request->item_description }}
                    </div>
                    <div class="rc-item">
                        <strong>Manager Comment</strong>
                        {{ $request->manager_comment ?? 'N/A' }}
                    </div>
                </div>
                <div class="rc-actions">
                    <a href="{{ route('display.payment') }}" class="btn-review">
                        <i class="bi bi-eye"></i> Review
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                No approved payment requests.
            </div>
        @endforelse
    </div>
</div>

{{-- Petty Cash Requests --}}
<div class="section-card">
    <div class="section-head">
        <div class="sh-left">
            <div class="sh-icon warning"><i class="bi bi-wallet2"></i></div>
            <div>
                <h4>Petty Cash Requests</h4>
                <div class="sh-count">Approved petty cash requests</div>
            </div>
        </div>
        <span class="sh-badge warning">{{ $pettyCashRequests->count() }}</span>
    </div>

    <div class="section-body">
        @forelse ($pettyCashRequests as $request)
            <div class="request-card warning">
                <div class="rc-head">
                    <h5>{{ $request->title }}</h5>
                </div>
                <div class="rc-details">
                    <div class="rc-item">
                        <strong>Requested Amount</strong>
                        {{ $request->currency }} {{ number_format($request->amount_requested, 2) }}
                    </div>
                    <div class="rc-item">
                        <strong>Approved Amount</strong>
                        {{ $request->currency }} {{ number_format($request->amount_approved, 2) }}
                    </div>
                    <div class="rc-item">
                        <strong>Full Name</strong>
                        {{ $request->user->full_name }}
                    </div>
                    <div class="rc-item">
                        <strong>Requested Date</strong>
                        {{ $request->created_at->format('d/m/Y') }}
                    </div>
                    <div class="rc-item">
                        <strong>Attachments</strong>
                        @if ($request->attachments)
                            <a href="{{ Storage::url($request->attachments) }}" target="_blank" class="attach-link">
                                <i class="bi bi-paperclip"></i> View Attachments
                            </a>
                        @else
                            <span class="text-muted">None</span>
                        @endif
                    </div>
                </div>
                <div class="rc-actions">
                    <a href="{{ route('display.petty_cash') }}" class="btn-review">
                        <i class="bi bi-eye"></i> Review
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                No approved petty cash requests.
            </div>
        @endforelse
    </div>
</div>
@endsection