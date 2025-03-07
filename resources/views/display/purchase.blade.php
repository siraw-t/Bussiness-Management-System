@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center text-info mb-4">{{ Auth::check() ? Auth::user()->company->company_name : 'Company' }} Purchase
            Requests</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Reference Number</th>
                        <th>Item Name</th>
                        <th>Item Description</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Finance Comment</th>
                        <th>Manager Comment</th>
                        <th>Checked By</th>
                        <th>Approved By</th>
                        <th>Company Country</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Check if the user is authenticated
                        $companyId = Auth::check() ? auth()->user()->company_id : null;

                        // Filter purchase requests to show only those from the user's company
                        $filteredPurchaseRequests = $purchaseRequests->filter(function ($request) use ($companyId) {
                            return $request->user && $request->user->company_id == $companyId;
                        });
                    @endphp

                    @if ($filteredPurchaseRequests->isEmpty())
                        <tr>
                            <td colspan="16" class="text-center">No purchase requests found.</td>
                        </tr>
                    @else
                        @foreach ($filteredPurchaseRequests as $request)
                            <tr class="align-middle" style="transition: background-color 0.3s;">
                                <td>{{ $request->reference_number }}</td>
                                <td>{{ $request->item_name }}</td>
                                <td>{{ $request->item_description ?? 'N/A' }}</td>
                                <td>{{ $request->quantity }} {{ $request->unit }}</td>
                                <td>{{ $request->unit }}</td>
                                <td>{{ $request->price }} {{ $request->currency }}</td>
                                <td>{{ $request->total_price }} {{ $request->currency }}</td>
                                <td>
                                    <span
                                        class="badge 
                                        {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                                <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                                <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</td>
                                <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                                <td>{{ $request->company_country }}</td>
                                <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $request->updated_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('purchase-requests.downloadPdf', $request) }}" class="btn btn-info">
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
