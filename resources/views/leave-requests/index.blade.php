@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ Auth::user()->full_name }} Leave Requests</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">Create New Leave Request</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Approved By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->start_date }}</td>
                    <td>{{ $request->end_date }}</td>
                    <td>{{ $request->reason }}</td>
                    <td>{{ $request->status }}</td>
                    <td>{{ $request->approved_by ? \App\Models\User::find($request->approved_by)->full_name : 'N/A' }}</td>                   
                     <td>
                        <a href="{{ route('leave-requests.edit', $request) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('leave-requests.destroy', $request) }}" method="POST" style="display:inline;">
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