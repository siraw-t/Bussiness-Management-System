@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Add New Document</h1>
    <form action="{{ route('documents.store') }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
        @csrf
        <div class="form-group mb-3">
            <label for="document_name">Document Name</label>
            <input type="text" class="form-control" id="document_name" name="document_name" required placeholder="Enter document name">
        </div>
        <div class="form-group mb-3">
            <label for="document_type">Document Type</label>
            <select class="form-control" id="document_type" name="document_type" required>
                <option value="" disabled selected>Select type</option>
                <option value="leave">Leave</option>
                <option value="purchase">Purchase</option>
                <option value="payment">Payment</option>
                <option value="plan">Employee Plan</option>
                <option value="report">Employee Report</option>
                <option value="inventory">Inventory</option>
                <option value="budget">Budget Forecasted</option>
                <option value="payroll">Payrolls</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="document_owner">Document Owner</label>
            <select class="form-control" id="document_owner" name="document_owner" required>
                @foreach($users as $user)
                    <option value="{{ $user->user_id }}">{{ $user->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Add a brief description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-file-earmark-plus"></i> Archive Document
        </button>
    </form>
</div>
@endsection