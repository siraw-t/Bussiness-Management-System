@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Edit Petty Cash Request</h1>

        <form action="{{ route('petty_cash_requests.update', $pettyCashRequest) }}" method="POST"
            enctype="multipart/form-data" class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control"
                    value="{{ old('title', $pettyCashRequest->title) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $pettyCashRequest->description) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="amount_requested">Amount Requested</label>
                <input type="number" step="0.01" name="amount_requested" class="form-control"
                    value="{{ old('amount_requested', $pettyCashRequest->amount_requested) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="amount_requested_in_words">Amount Requested in Words</label>
                <input type="text" name="amount_requested_in_words" class="form-control"
                    value="{{ old('amount_requested_in_words', $pettyCashRequest->amount_requested_in_words) }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="currency">Currency</label>
                <select name="currency" class="form-control" required>
                    <option value="USD" {{ $pettyCashRequest->currency == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="ETB" {{ $pettyCashRequest->currency == 'ETB' ? 'selected' : '' }}>ETB</option>
                    <option value="UGX" {{ $pettyCashRequest->currency == 'UGX' ? 'selected' : '' }}>UGX</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="approved_by">Select Manager (Approve)</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}"
                            {{ $manager->user_id == $pettyCashRequest->approved_by ? 'selected' : '' }}>
                            {{ $manager->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="checked_by">Select Finance (Check)</label>
                <select name="checked_by" class="form-control" required>
                    <option value="" disabled>Select a finance person</option>
                    @foreach ($finances as $finance)
                        <option value="{{ $finance->user_id }}"
                            {{ $finance->user_id == $pettyCashRequest->checked_by ? 'selected' : '' }}>
                            {{ $finance->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> Update
            </button>
            <a href="{{ route('petty_cash_requests.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.querySelector('input[name="amount_requested"]');
            const amountInWordsInput = document.querySelector('input[name="amount_requested_in_words"]');

            // Convert the amount on input
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
                                str += words[Math.floor(remainder / 10) + 18]; // 18 is the index for 'twenty'
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

            // Initialize conversion for existing amount
            const initialAmount = parseFloat(amountInput.value);
            if (!isNaN(initialAmount)) {
                amountInWordsInput.value = convertNumberToWords(initialAmount);
            }
        });
    </script>
@endsection
