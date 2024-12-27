@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ Auth::user()->full_name }} Purchase Requests</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary">Create New Purchase Request</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Estimated Cost</th>
                <th>Status</th>
                <th>Comment</th>
                <th>Approved By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->item_name }}</td>
                    <td>{{ $request->quantity }}</td>
                    <td>${{ number_format($request->estimated_cost, 2) }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>{{ $request->comment ?? 'N/A' }}</td>
                    <td>{{ $request->approved_by ? \App\Models\User::find($request->approved_by)->full_name : 'N/A' }}</td>                   
                    <td>
                        <a href="{{ route('purchase-requests.edit', $request) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('purchase-requests.destroy', $request) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection