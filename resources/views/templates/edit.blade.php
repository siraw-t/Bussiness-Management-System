@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Request Form Template</h1>
        <form action="{{ route('templates.update', $template->form_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="form_name" class="form-label">Form Name</label>
                <input type="text" name="form_name" class="form-control" value="{{ $template->form_name }}" required>
            </div>
            <div class="mb-3">
                <label for="fields" class="form-label">Fields (JSON)</label>
                <textarea name="fields" class="form-control" required>{{ $template->fields }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
