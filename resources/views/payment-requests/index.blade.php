@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4"> Payment Requests</h1>

        {{-- @if (session('success'))
            <div class="alert alert-success fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif --}}

        <div class="text-center mb-4">
            <a href="{{ route('payment-requests.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Create New Payment Request
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Request Date</th>
                        <th>Reference No</th>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>VAT</th>
                        <th>Total</th>
                        <th>Withholding</th>
                        <th>Net Payment</th>
                        <th>Cost Center</th>
                        <th>Account Code</th>
                        <th>Company Country</th>
                        <th>Status</th>
                        <th>Finance Comment</th>
                        <th>Manager Comment</th>
                        <th>Reviewed By</th>
                        <th>Approved By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr class="align-middle" style="transition: background-color 0.3s;">
                            <td>{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}</td>
                            <td>{{ $request->reference_no ?? 'N/A' }}</td>
                            <td>{{ optional($request->user)->full_name }}</td>
                            <td>{{ $request->department ?? 'N/A' }}</td>
                            <td>{{ $request->item_name }}</td>
                            <td>{{ $request->quantity ?? 'N/A' }} {{ $request->unit ?? 'N/A' }}</td>
                            <td>{{ number_format($request->amount, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                            <td>{{ number_format($request->vat, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                            <td>{{ number_format($request->total, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                            <td>{{ number_format($request->with_holding, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                            <td>{{ number_format($request->net_payment, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                            <td>{{ $request->cost_center ?? 'N/A' }}</td>
                            <td>{{ $request->account_code ?? 'N/A' }}</td>
                            <td>{{ $request->company_country }}</td>
                            <td>
                                <span
                                    class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                            <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                            <td>{{ $request->reviewed_by ? optional(\App\Models\User::find($request->reviewed_by))->full_name : 'Not Checked' }}
                            </td>
                            <td>{{ $request->approved_by ? optional(\App\Models\User::find($request->approved_by))->full_name : 'Not Approved' }}
                            </td>
                            <td>
                                @if ($request->status === 'approved')
                                    <span class="btn btn-warning btn-sm me-2 disabled"
                                        title="Cannot edit approved requests">
                                        <i class="bi bi-pencil"></i>
                                    </span>
                                @else
                                    <a href="{{ route('payment-requests.edit', $request) }}"
                                        class="btn btn-warning btn-sm me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                                <form action="{{ route('payment-requests.destroy', $request) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this request?');"
                                        @if ($request->status === 'approved') disabled @endif>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                <a href="{{ route('payment-requests.downloadPdf', $request) }}"
                                    class="btn btn-info btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style>
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
