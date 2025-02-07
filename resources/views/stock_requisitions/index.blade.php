@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-success mb-4">Stock Requisitions</h1>

        @if (session('success'))
            <div class="alert alert-success fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-4">
            <a href="{{ route('stock_requisitions.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle"></i> Create New Requisition
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Item Name & Description</th>
                        <th>Quantity Requested</th>
                        <th>Quantity Approved</th>
                        <th>Status</th>
                        <th>Requested By</th>
                        <th>Manager Comment</th>
                        <th>Approved By</th>
                        <th>Employee Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requisitions as $requisition)
                        <tr>
                            <td>{{ $requisition->item_name_with_description }}</td>
                            <td>{{ $requisition->quantity_requested }}</td>
                            <td>{{ $requisition->quantity_approved ?? 'N/A' }}</td>
                            <td>{{ ucfirst($requisition->status) }}</td>
                            <td>
                                @if ($requisition->requested_by)
                                    {{ optional(\App\Models\User::find($requisition->requested_by))->full_name ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $requisition->manager_comment ?? 'N/A' }}</td>
                            <td>
                                @if ($requisition->approved_by)
                                    {{ optional(\App\Models\User::find($requisition->approved_by))->full_name ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('stock_requisitions.comment', $requisition->stock_requisition_id) }}"
                                    method="POST">
                                    @csrf
                                    <input type="text" class="form-control" name="employee_comment" list="commentOptions"
                                        value="{{ $requisition->employee_comment }}"
                                        @if ($requisition->status !== 'approved') disabled @endif
                                        pattern="^(received|not_received)$"
                                        title="Please type 'received' or 'not_received' only." required>

                                    <datalist id="commentOptions">
                                        <option value="received">
                                        <option value="not_received">
                                    </datalist>

                                    @if ($requisition->status !== 'approved')
                                        <small class="text-danger">Cannot change to "Received" until approved by
                                            manager.</small>
                                    @endif

                                    <button type="submit" class="btn btn-primary mt-2"
                                        @if ($requisition->status !== 'approved') disabled @endif>
                                        Submit Comment
                                    </button>
                                </form>
                            </td>

                            <td>
                                <a href="{{ route('stock_requisitions.edit', $requisition->stock_requisition_id) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form
                                    action="{{ route('stock_requisitions.destroy', $requisition->stock_requisition_id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this requisition?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function updateEmployeeComment(requisitionId, commentValue) {
            fetch(`/stock_requisitions/${requisitionId}/updateComment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        employee_comment: commentValue
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Employee comment updated successfully.');
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    </script>
@endsection
