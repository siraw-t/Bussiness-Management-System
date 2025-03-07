@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <div class="card text-bg-primary mb-4"
                    style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);">
                    <div class="card-body">
                        <h1 class="text-white" style="font-size: 1.5rem;">
                            <i class="bi bi-file-earmark-text" style="font-size: 1.3rem;"></i> Welcome to
                            {{ Auth::user()->company->company_name }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <h2 class="text-secondary mb-3" style="font-size: 2rem;">Manage Request Form Templates</h2>
            <a href="{{ route('templates.create') }}" class="btn btn-primary mb-3"
                style="border-radius: 20px; padding: 10px 20px; font-size: 1.2rem;">
                <i class="bi bi-plus-circle"></i> Create Template
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Form Name</th>
                        <th>Fields</th>
                        <th>Created By</th>
                        <th>Company ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($templates as $template)
                        <tr>
                            <td>{{ $template->form_name }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                    data-target="#fieldsModal{{ $template->form_id }}">
                                    View Fields
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="fieldsModal{{ $template->form_id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="fieldsModalLabel{{ $template->form_id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="fieldsModalLabel{{ $template->form_id }}">Fields
                                                    for {{ $template->form_name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <pre>{{ json_encode(json_decode($template->fields), JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $template->created_by }}</td>
                            <td>{{ $template->company_id }}</td>
                            <td>
                                <a href="{{ route('templates.edit', $template->form_id) }}" class="btn btn-warning btn-sm"
                                    style="border-radius: 20px;">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('templates.destroy', $template->form_id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 20px;"
                                        onclick="return confirm('Are you sure you want to delete this template?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
