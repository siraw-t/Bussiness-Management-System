@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center text-info mb-4">Payment Requests</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Reference No</th>
                        <th>Request Date</th>
                        <th>Department</th>
                        <th>Finance Comment</th>
                        <th>Item Description</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>VAT</th>
                        <th>Total</th>
                        <th>Net Payment</th>
                        <th>Withholding</th>
                        <th>Cost Center</th>
                        <th>Account Code</th>
                        <th>Status</th>
                        <th>Manager Comment</th>
                        <th>Checked By</th>
                        <th>Approved By</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $companyId = auth()->user()->company_id;

                        // Filter payment requests to show only those from the user's company
                        $filteredPaymentRequests = $paymentRequests->filter(function ($request) use ($companyId) {
                            return $request->user->company_id == $companyId;
                        });
                    @endphp

                    @if ($filteredPaymentRequests->isEmpty())
                        <tr>
                            <td colspan="21" class="text-center">No payment requests found.</td>
                        </tr>
                    @else
                        @foreach ($filteredPaymentRequests as $request)
                            <tr class="align-middle" style="transition: background-color 0.3s;">
                                <td>{{ $request->reference_no ?? 'N/A' }}</td>
                                <td>{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $request->department ?? 'N/A' }}</td>
                                <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                                <td>{{ $request->item_description }}</td>
                                <td>{{ $request->item_name ?? 'N/A' }}</td>
                                <td>{{ $request->quantity ?? 'N/A' }} {{ $request->unit ?? 'N/A' }}</td>
                                <td>{{ number_format($request->amount, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ number_format($request->vat, 2) ?? '0.00' }} {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ number_format($request->total, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ number_format($request->net_payment, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ number_format($request->with_holding, 2) ?? '0.00' }}
                                    {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ $request->cost_center ?? 'N/A' }}</td>
                                <td>{{ $request->account_code ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                                <td>{{ $request->reviewed_by ? optional(\App\Models\User::find($request->reviewed_by))->full_name : 'Not Checked' }}
                                </td>
                                <td>{{ $request->approved_by ? optional(\App\Models\User::find($request->approved_by))->full_name : 'Not Approved' }}
                                </td>
                                <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $request->updated_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('payment-requests.downloadPdf', $request) }}" class="btn btn-info">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Optional: Add Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Custom CSS for hover effects -->
    <style>
        .table-hover tr:hover {
            background-color: #f8f9fa;
            /* Light grey on hover */
            cursor: pointer;
            /* Pointer cursor on hover */
        }
    </style>
@endsection
