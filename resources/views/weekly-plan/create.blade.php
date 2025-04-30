@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Submit Weekly Plan</h1>

        <form action="{{ route('weekly-plan.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 600px; margin: auto;">
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
                <label for="urgent_issues">Urgent Issues</label>
                <textarea name="urgent_issues" class="form-control"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="manager_id">Select Manager</label>
                <select name="approved_by" class="form-control" required>
                    <option value="">Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}">{{ $manager->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-lg"></i> Submit
            </button>
        </form>
    </div>
@endsection
