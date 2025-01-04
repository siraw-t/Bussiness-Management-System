@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Edit Weekly Report</h1>

    <form action="{{ route('weekly-reports.update', $weeklyReport) }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="week">Week</label>
            <input type="date" name="week" class="form-control" value="{{ old('week', $weeklyReport->week) }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="report">Report</label>
            <textarea name="report" class="form-control" rows="4" required placeholder="Update your report...">{{ old('report', $weeklyReport->report) }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3" placeholder="Any comments?">{{ old('comment', $weeklyReport->comment) }}</textarea>
        </div>
        <button type="submit" class="btn btn-warning btn-lg w-100">
            <i class="bi bi-pencil-square"></i> Update
        </button>
        <a href="{{ route('weekly-reports.index') }}" class="btn btn-secondary btn-lg w-100 mt-2">Cancel</a>
    </form>
</div>
@endsection