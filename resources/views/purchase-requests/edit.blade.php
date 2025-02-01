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
                <label for="item_info">Item Info</label>
                <input type="text" name="item_info" class="form-control"
                    value="{{ old('item_info', $purchaseRequest->item_info) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="quantity_with_unit">Quantity with Unit</label>
                <input type="text" name="quantity_with_unit" class="form-control"
                    value="{{ old('quantity_with_unit', $purchaseRequest->quantity_with_unit) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="price_with_currency">Price with Currency</label>
                <input type="text" name="price_with_currency" class="form-control"
                    value="{{ old('price_with_currency', $purchaseRequest->price_with_currency) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="attachments">Attachments (At least 3 files required)</label>
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

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> Update
            </button>
            <a href="{{ route('purchase-requests.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
@endsection
