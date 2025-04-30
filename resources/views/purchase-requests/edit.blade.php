@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Edit Purchase Request</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('purchase-requests.update', $purchaseRequest) }}" method="POST" enctype="multipart/form-data"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control"
                    value="{{ old('item_name', $purchaseRequest->item_name) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="item_description">Item Description</label>
                <textarea name="item_description" class="form-control" rows="3">{{ old('item_description', $purchaseRequest->item_description) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control"
                    value="{{ old('quantity', $purchaseRequest->quantity) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="unit">Unit</label>
                <select name="unit" class="form-control" required>
                    <option value="kg" {{ $purchaseRequest->unit == 'kg' ? 'selected' : '' }}>KG</option>
                    <option value="ltr" {{ $purchaseRequest->unit == 'ltr' ? 'selected' : '' }}>LTR</option>
                    <option value="pcs" {{ $purchaseRequest->unit == 'pcs' ? 'selected' : '' }}>Pcs</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="price">Price</label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $purchaseRequest->price) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="currency">Currency</label>
                <select name="currency" class="form-control" required>
                    <option value="USD" {{ $purchaseRequest->currency == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="ETB" {{ $purchaseRequest->currency == 'ETB' ? 'selected' : '' }}>ETB</option>
                    <option value="UGX" {{ $purchaseRequest->currency == 'UGX' ? 'selected' : '' }}>UGX</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="attachments">Attachments</label>
                <input type="file" name="attachments[]" class="form-control" multiple>
                @if ($purchaseRequest->attachments)
                    <p>Current Attachments:</p>
                    <ul>
                        @foreach (json_decode($purchaseRequest->attachments) as $attachment)
                            <li><a href="{{ asset($attachment) }}" target="_blank">{{ basename($attachment) }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="form-group mb-3">
                <label for="approved_by">Select Manager (Approve)</label>
                <select name="approved_by" class="form-control" required>
                    <option value="" disabled>Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}"
                            {{ $manager->user_id == $purchaseRequest->approved_by ? 'selected' : '' }}>
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
                            {{ $finance->user_id == $purchaseRequest->checked_by ? 'selected' : '' }}>
                            {{ $finance->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> Update
            </button>
            <a href="{{ route('purchase-requests.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
@endsection
