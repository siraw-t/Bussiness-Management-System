@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Weekly Plan</h1>

    <form action="{{ route('weekly-plan.update', $weeklyPlan) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="week">Week</label>
            <input type="text" name="week" class="form-control" value="{{ old('week', $weeklyPlan->week) }}" required>
        </div>
        <div class="form-group">
            <label for="plan">Plan</label>
            <textarea name="plan" class="form-control" required>{{ old('plan', $weeklyPlan->plan) }}</textarea>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" class="form-control" rows="3">{{ old('comment', $weeklyPlan->comment) }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('weekly-plan.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection