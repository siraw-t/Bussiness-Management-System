{{-- resources/views/documents/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Document</h1>
    <form action="{{ route('documents.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="document_name">Document Name</label>
            <input type="text" class="form-control" id="document_name" name="document_name" required>
        </div>
        <div class="form-group">
            <label for="document_type">Document Type</label>
            <select class="form-control" id="document_type" name="document_type" required>
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
        <div class="form-group">
            <label for="document_owner">Document Owner</label>
            <select class="form-control" id="document_owner" name="document_owner" required>
                @foreach($users as $user)
                    <option value="{{ $user->user_id }}">{{ $user->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Archive Document</button>
    </form>
</div>
@endsection