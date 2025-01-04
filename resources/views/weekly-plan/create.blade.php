@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Submit Weekly Plan</h1>

    <form action="{{ route('weekly-plan.store') }}" method="POST" class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
        @csrf
        <div class="form-group mb-3">
            <label for="week">Week (YYYY-MM-DD)</label>
            <input type="date" name="week" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="plan">Plan</label>
            <textarea name="plan" class="form-control" required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-check-lg"></i> Submit
        </button>
    </form>
</div>
@endsection