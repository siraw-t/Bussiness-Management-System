@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Leave Requests</h1>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        <div class="text-center mb-4">
            <a href="{{ route('leave-requests.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Create New Leave Request
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Full Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Leave Type</th>
                        <th>Other Reason</th>
                        <th>Employee Signature</th>
                        <th>Uploaded File</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Manager Comments</th>
                        <th>Company Country</th>
                        <th>Reference Number</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ Auth::user()->full_name }}</td>
                            <td>{{ $request->start_date }}</td>
                            <td>{{ $request->end_date }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $request->leave_type)) }}</td>
                            <td>{{ $request->other_reason ?? 'N/A' }}</td>
                            <td>
                                @if ($request->employee_signature)
                                    <img src="{{ asset('storage/' . $request->employee_signature) }}" alt="Signature"
                                        style="width: 60px;">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if ($request->attachment)
                                    <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank"
                                        class="btn btn-link btn-sm">View File</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                            <td>{{ $request->manager_comments ?? 'N/A' }}</td>
                            <td>{{ $request->company_country }}</td>
                            <td>{{ $request->reference_number }}</td>
                            <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $request->updated_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if ($request->status === 'approved')
                                        <span class="btn btn-warning btn-sm me-2 disabled"
                                            title="Cannot edit approved requests">
                                            <i class="bi bi-pencil"></i>
                                        </span>
                                    @else
                                        <a href="{{ route('leave-requests.edit', $request) }}"
                                            class="btn btn-warning btn-sm me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('leave-requests.destroy', $request) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-2"
                                            onclick="return confirm('Are you sure?');"
                                            @if ($request->status === 'approved') disabled @endif>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('leave-requests.downloadPdf', $request) }}"
                                        class="btn btn-info btn-sm me-2">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media (max-width: 375px) {
            .btn-group-vertical {
                flex-direction: column;
            }

            .btn {
                font-size: 0.8rem;
                /* Smaller font size for mobile */
                padding: 0.5rem;
                /* Reduced padding */
            }

            img {
                width: 50px;
                /* Smaller image for signatures */
            }
        }
    </style>
@endsection
