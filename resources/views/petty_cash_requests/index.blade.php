@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Petty Cash Requests</h1>

        <div class="text-center mb-4">
            <a href="{{ route('petty_cash_requests.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Create New Petty Cash Request
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Reference Number</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Amount Requested</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Amount Approved</th>
                        <th>Manager Comment</th>
                        <th>Finance Comment</th>
                        <th>Approved By</th>
                        <th>Checked By</th>
                        <th>Attachments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->reference_number }}</td>
                            <td>{{ $request->title }}</td>
                            <td>{{ $request->description }}</td>
                            <td>{{ number_format($request->amount_requested, 2) }}</td>
                            <td>{{ $request->currency }}</td>
                            <td>
                                <span
                                    class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($request->amount_approved, 2) }}</td>
                            <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                            <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                            <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                            <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    // Decode attachments if stored as JSON string
                                    $attachments = is_string($request->attachments)
                                        ? json_decode($request->attachments, true)
                                        : $request->attachments;
                                @endphp

                                @if (!empty($attachments))
                                    <ul>
                                        @foreach ($attachments as $attachment)
                                            <li>
                                                <a href="{{ asset('storage/' . $attachment) }}" target="_blank">
                                                    {{ basename($attachment) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if ($request->status === 'approved')
                                        <span class="btn btn-warning btn-sm me-2 disabled"
                                            title="Cannot edit approved requests">
                                            <i class="bi bi-pencil"></i>
                                        </span>
                                    @else
                                        <a href="{{ route('petty_cash_requests.edit', $request) }}"
                                            class="btn btn-warning btn-sm me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('petty_cash_requests.destroy', $request) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-2"
                                            onclick="return confirm('Are you sure?');"
                                            @if ($request->status === 'approved') disabled @endif>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('petty_cash_requests.downloadPdf', $request) }}"
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
@endsection
