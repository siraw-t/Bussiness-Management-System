@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <h2 class="text-center text-info mb-4">{{ Auth::user()->company->company_name }} Purchase Requests</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Estimated Cost</th>
                    <th>Status</th>
                    <th>Comment</th>
                    <th>Approved By</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Get the currently logged-in user's company ID
                    $companyId = auth()->user()->company_id;

                    // Filter purchase requests to show only those from the user's company
                    $filteredPurchaseRequests = $purchaseRequests->filter(function ($request) use ($companyId) {
                        return $request->user->company_id == $companyId;
                    });
                @endphp

                @if($filteredPurchaseRequests->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center">No purchase requests found.</td>
                    </tr>
                @else
                    @foreach($filteredPurchaseRequests as $request)
                    <tr class="align-middle" style="transition: background-color 0.3s;">
                        <td>{{ $request->item_name }}</td>
                        <td>{{ $request->quantity }}</td>
                        <td>${{ number_format($request->estimated_cost, 2) }}</td>
                        <td>
                            <span class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>{{ $request->comment ?? 'N/A' }}</td>
                        <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                        <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $request->updated_at->format('Y-m-d H:i:s') }}</td>
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
        background-color: #f8f9fa; /* Light grey on hover */
        cursor: pointer; /* Pointer cursor on hover */
    }
</style>
@endsection