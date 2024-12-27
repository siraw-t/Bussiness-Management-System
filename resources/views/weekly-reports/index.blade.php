@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ Auth::user()->full_name }} Weekly Reports</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('weekly-reports.create') }}" class="btn btn-primary">Submit New Weekly Report</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Week</th>
                <th>Report</th>
                <th>Submitted At</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->week }}</td>
                    <td>{{ $report->report }}</td>
                    <td>{{ $report->submitted_at }}</td>
                    <td>{{ $report->comment ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('weekly-reports.edit', $report) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('weekly-reports.destroy', $report) }}" method="POST" style="display:inline;">
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