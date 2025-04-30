@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4"> Weekly Plans</h1>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        <div class="text-center mb-4">
            <a href="{{ route('weekly-plan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Submit New Weekly Plan
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Full Name</th>
                        <th>Company Country</th>
                        <th>Week</th>
                        <th>Plan</th>
                        <th>Submitted At</th>
                        <th>Manager Comment</th>
                        <th>Urgent Issues</th>
                        <th>Reference Number</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plans as $plan)
                        <tr>
                            <td>{{ $plan->employee->full_name ?? 'N/A' }}</td>
                            <td>{{ $plan->employee->company->country ?? 'N/A' }}</td>
                            <td>{{ $plan->week }}</td>
                            <td>{{ $plan->plan }}</td>
                            <td>{{ $plan->submitted_at }}</td>
                            <td>{{ $plan->manager_comment ?? 'N/A' }}</td>
                            <td>{{ $plan->urgent_issues ?? 'N/A' }}</td>
                            <td>{{ $plan->reference_number }}</td>
                            <td>
                                <span
                                    class="badge {{ $plan->status === 'approved' ? 'bg-success' : ($plan->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($plan->status) }}
                                </span>
                            </td>
                            <td>{{ optional(\App\Models\User::find($plan->approved_by))->full_name ?? 'N/A' }}</td>

                            <td>
                                <div class="btn-group" role="group">
                                    @if ($plan->status === 'approved')
                                        <span class="btn btn-warning btn-sm me-2 disabled"
                                            title="Cannot edit approved plans">
                                            <i class="bi bi-pencil"></i>
                                        </span>
                                    @else
                                        <a href="{{ route('weekly-plan.edit', $plan) }}"
                                            class="btn btn-warning btn-sm me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('weekly-plan.destroy', $plan) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-2"
                                            onclick="return confirm('Are you sure?');"
                                            @if ($plan->status === 'approved') disabled @endif>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('weekly-plan.downloadPdf', $plan) }}" class="btn btn-info btn-sm">
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .table-hover tr:hover {
            background-color: #f0f8ff;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        @media (max-width: 375px) {
            .btn {
                font-size: 0.8rem;
                /* Smaller font size for mobile */
                padding: 0.4rem 0.6rem;
                /* Reduced padding */
            }

            .table th,
            .table td {
                font-size: 0.9rem;
                /* Smaller text for table */
            }
        }
    </style>
@endsection
