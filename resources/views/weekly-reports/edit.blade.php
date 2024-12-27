@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Weekly Report</h1>

    <form action="{{ route('weekly-reports.update', $weeklyReport) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="week">Week</label>
            <input type="text" name="week" class="form-control" value="{{ old('week', $weeklyReport->week) }}" required>
        </div>
        <div class="form-group">
            <label for="report">Report</label>
            <textarea name="report" class="form-control" required>{{ old('report', $weeklyReport->report) }}</textarea>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3">{{ old('comment', $weeklyReport->comment) }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('weekly-reports.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection