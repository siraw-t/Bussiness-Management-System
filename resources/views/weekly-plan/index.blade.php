@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ Auth::user()->full_name }} Weekly Plans</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('weekly-plan.create') }}" class="btn btn-primary">Submit New Weekly Plan</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Week</th>
                <th>Plan</th>
                <th>Submitted At</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plans as $plan)
                <tr>
                    <td>{{ $plan->week }}</td>
                    <td>{{ $plan->plan }}</td>
                    <td>{{ $plan->submitted_at }}</td>
                    <td>{{ $plan->comment ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('weekly-plan.edit', $plan) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('weekly-plan.destroy', $plan) }}" method="POST" style="display:inline;">
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