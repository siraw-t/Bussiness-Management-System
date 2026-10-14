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
        background: linear-gradient(135deg, #10b981, #34d399);
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
        background: linear-gradient(135deg, #10b981, #34d399);
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.72rem; font-weight: 700;
    }

    .amount-box {
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        border-radius: 12px;
        padding: 14px 16px;
    }
    [data-bs-theme="dark"] .amount-box { background: #064e3b; border-color: #065f46; }
    .amount-box input {
        background: transparent !important;
        border: none !important;
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: #065f46 !important;
        padding: 0 !important;
    }
    [data-bs-theme="dark"] .amount-box input { color: #a7f3d0 !important; }

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

    @media (max-width: 576px) {
        .form-section { padding: 18px; }
        .form-header { padding: 20px 18px; }
        .form-actions { padding: 18px; flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; }
    }
</style>

<div class="form-page-head">
    <h1>Create Petty Cash Request</h1>
    <p>Submit a new petty cash request for approval</p>
</div>

<form action="{{ route('petty_cash_requests.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-card">
        <div class="form-header">
            <div class="hdr-icon"><i class="bi bi-wallet2"></i></div>
            <div>
                <h5>Petty Cash Request Details</h5>
                <p>Fill in the information below</p>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">1</span> Request Information</h6>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g., Transport cost" required>
            </div>

            <div class="mb-0">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional description..."></textarea>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">2</span> Amount</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="amount_requested" class="form-label">Amount Requested</label>
                    <input type="number" step="0.01" name="amount_requested" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <select name="currency" class="form-select" required>
                        <option value="USD">USD</option>
                        <option value="ETB">ETB</option>
                        <option value="UGX">UGX</option>
                    </select>
                </div>
            </div>

            <div class="mb-0">
                <label for="amount_requested_in_words" class="form-label">Amount Requested in Words</label>
                <div class="amount-box">
                    <input type="text" name="amount_requested_in_words" class="form-control" readonly placeholder="Auto-generated">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h6><span class="step-num">3</span> Approvers</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="approved_by" class="form-label">Select Manager (Approve)</label>
                    <select name="approved_by" class="form-select" required>
                        <option value="" disabled selected>Select a manager</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->user_id }}">{{ $manager->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="checked_by" class="form-label">Select Finance (Check)</label>
                    <select name="checked_by" class="form-select" required>
                        <option value="" disabled selected>Select a finance person</option>
                        @foreach ($finances as $finance)
                            <option value="{{ $finance->user_id }}">{{ $finance->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('petty_cash_requests.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn-submit">
                <i class="bi bi-check2-circle"></i> Submit Request
            </button>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.querySelector('input[name="amount_requested"]');
        const amountInWordsInput = document.querySelector('input[name="amount_requested_in_words"]');

        amountInput.addEventListener('input', function() {
            const amount = parseFloat(amountInput.value);
            amountInWordsInput.value = convertNumberToWords(amount);
        });

        function convertNumberToWords(amount) {
            const words = [
                '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
                'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen',
                'eighteen', 'nineteen', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty',
                'ninety'
            ];

            const scale = ['', 'thousand', 'million', 'billion'];

            if (amount === 0) return 'zero';

            let result = '';
            let num = Math.floor(amount);
            let scaleIndex = 0;

            while (num > 0) {
                let remainder = num % 1000;
                if (remainder > 0) {
                    let str = '';
                    if (remainder >= 100) {
                        str += words[Math.floor(remainder / 100)] + ' hundred ';
                        remainder %= 100;
                    }
                    if (remainder > 0) {
                        if (remainder < 20) {
                            str += words[remainder];
                        } else {
                            str += words[Math.floor(remainder / 10) + 18];
                            if (remainder % 10 > 0) {
                                str += ' ' + words[remainder % 10];
                            }
                        }
                    }
                    result = str + ' ' + scale[scaleIndex] + ' ' + result;
                }
                num = Math.floor(num / 1000);
                scaleIndex++;
            }

            return result.trim();
        }
    });
</script>
@endsection