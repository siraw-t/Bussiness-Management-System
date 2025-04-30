@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Edit Weekly Report</h1>


        <form action="{{ route('weekly-reports.update', $weeklyReport) }}" method="POST" class="shadow p-4 rounded bg-light"
            style="max-width: 500px; margin: auto;">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="week">Week</label>
                <input type="date" name="week" class="form-control" value="{{ old('week', $weeklyReport->week) }}"
                    required>
            </div>
            <div class="form-group mb-3">
                <label for="report">Report</label>
                <textarea name="report" class="form-control" rows="4" required placeholder="Update your report...">{{ old('report', $weeklyReport->report) }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="tasks_in_progress">Tasks In Progress</label>
                <textarea name="tasks_in_progress" class="form-control" rows="3" placeholder="Current tasks...">{{ old('tasks_in_progress', $weeklyReport->tasks_in_progress) }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="tasks_completed">Tasks Completed</label>
                <textarea name="tasks_completed" class="form-control" rows="3" placeholder="Completed tasks...">{{ old('tasks_completed', $weeklyReport->tasks_completed) }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="self_assessment">Self Assessment</label>
                <textarea name="self_assessment" class="form-control" rows="3" placeholder="Your self-assessment...">{{ old('self_assessment', $weeklyReport->self_assessment) }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="manager_id">Select Manager</label>
                <select name="approved_by" class="form-control" required>
                    <option value="">Select a manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->user_id }}"
                            {{ $manager->user_id == $weeklyReport->approved_by ? 'selected' : '' }}>
                            {{ $manager->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-warning btn-lg w-100">
                <i class="bi bi-pencil-square"></i> Update
            </button>
            <a href="{{ route('weekly-reports.index') }}" class="btn btn-secondary btn-lg w-100 mt-2">Cancel</a>
        </form>
    </div>
@endsection
