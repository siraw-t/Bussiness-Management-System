@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4"> Purchase Requests</h1>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        <div class="text-center mb-4">
            <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Create New Purchase Request
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Reference Number</th>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Company Country</th>
                        <th>Status</th>
                        <th>Finance Comment</th>
                        <th>Manager Comment</th>
                        <th>Checked By</th>
                        <th>Approved By</th>
                        <th>Attachments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->reference_number }}</td>
                            <td>{{ $request->item_name }}</td>
                            <td>{{ $request->item_description }}</td>
                            <td>{{ $request->quantity }} {{ $request->unit }}</td>
                            <td>{{ $request->unit }}</td>
                            <td>{{ number_format($request->price, 2) }} {{ $request->currency }}</td>
                            <td>{{ number_format($request->total_price, 2) }} {{ $request->currency }}</td>
                            <td>{{ $request->company_country }}</td>
                            <td>
                                <span
                                    class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                            <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                            <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</td>
                            <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                            <td>
                                @if ($request->attachments)
                                    <ul>
                                        @foreach (json_decode($request->attachments) as $attachment)
                                            <li>
                                                <a href="{{ asset('storage/' . $attachment) }}"
                                                    target="_blank">{{ basename($attachment) }}</a>
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
                                        <a href="{{ route('purchase-requests.edit', $request) }}"
                                            class="btn btn-warning btn-sm me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('purchase-requests.destroy', $request) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-2"
                                            onclick="return confirm('Are you sure?');"
                                            @if ($request->status === 'approved') disabled @endif>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('purchase-requests.downloadPdf', $request) }}"
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .table-hover tr:hover {
            background-color: #f8f9fa;
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
