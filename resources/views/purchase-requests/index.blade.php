@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Purchase Requests</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="text-center mb-4">
            <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-lg"></i> Create New Purchase Request
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Reference Number</th>
                        <th>Item Info</th>
                        <th>Quantity</th>
                        <th>Price</th>
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
                            <td>{{ $request->item_info }}</td>
                            <td>{{ $request->quantity_with_unit }}</td>
                            <td>{{ $request->price_with_currency }}</td>
                            <td>
                                <span
                                    class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                            <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                            <td>
                                {{ $request->checked_by ? optional(\App\Models\User::find($request->checked_by))->full_name : 'Not Checked' }}
                            </td>
                            <td>
                                {{ $request->approved_by ? optional(\App\Models\User::find($request->approved_by))->full_name : 'Not Approved' }}
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

                                    <a href="{{ route('purchase-requests.edit', $request) }}"
                                        class="btn btn-warning btn-sm me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('purchase-requests.destroy', $request) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm me-2">
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
            /* Light grey on hover */
            cursor: pointer;
            /* Pointer cursor on hover */
        }
    </style>
@endsection
