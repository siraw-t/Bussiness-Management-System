@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Submit Weekly Report</h1>

    <form action="{{ route('weekly-reports.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="week">Week</label>
            <input type="text" name="week" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="report">Report</label>
            <textarea name="report" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endsection