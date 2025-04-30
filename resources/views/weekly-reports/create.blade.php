@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Submit Weekly Report</h1>



        <form action="{{ route('weekly-reports.store') }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 500px; margin: auto;">
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
                <label for="tasks_in_progress">Tasks In Progress</label>
                <textarea name="tasks_in_progress" class="form-control" rows="3" placeholder="Current tasks..."></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="tasks_completed">Tasks Completed</label>
                <textarea name="tasks_completed" class="form-control" rows="3" placeholder="Completed tasks..."></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="self_assessment">Self Assessment</label>
                <textarea name="self_assessment" class="form-control" rows="3" placeholder="Your self-assessment..."></textarea>
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
            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-lg"></i> Submit
            </button>
        </form>
    </div>
@endsection
