@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ Auth::user()->full_name }} Payment Requests</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('payment-requests.create') }}" class="btn btn-primary">Create New Payment Request</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Amount</th>
                <th>Description</th>
                <th>Status</th>
                <th>Comment</th>
                <th>Approved By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>${{ number_format($request->amount, 2) }}</td>
                    <td>{{ $request->description }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>{{ $request->comment ?? 'N/A' }}</td>
                    <td>
                        @if ($request->approved_by)
                            {{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>                    <td>
                        <a href="{{ route('payment-requests.edit', $request) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('payment-requests.destroy', $request) }}" method="POST" style="display:inline;">
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