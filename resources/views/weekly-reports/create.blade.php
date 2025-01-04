@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Submit Weekly Report</h1>

    <form action="{{ route('weekly-reports.store') }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 500px; margin: auto;">
        @csrf
        <div class="form-group mb-3">
            <label for="week">Week</label>
            <input type="date" name="week" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="report">Report</label>
            <textarea name="report" class="form-control" rows="4" placeholder="Write your report here..." required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3" placeholder="Any comments?"></textarea>
        </div>
        <button type="submit" class="btn btn-success btn-lg w-100">
            <i class="bi bi-check-lg"></i> Submit
        </button>
    </form>
</div>
@endsection