@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Request Form Template</h1>
        <form action="{{ route('templates.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="form_name" class="form-label">Form Name</label>
                <input type="text" name="form_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fields" class="form-label">Fields (JSON)</label>
                <textarea name="fields" class="form-control" required></textarea>
            </div>
            <input type="hidden" name="company_id" value="1"> <!-- Set company_id as needed -->
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
