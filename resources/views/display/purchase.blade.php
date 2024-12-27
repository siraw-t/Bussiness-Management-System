@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Purchase Requests</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
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
                    <tr>
                        <td>{{ $request->item_name }}</td>
                        <td>{{ $request->quantity }}</td>
                        <td>{{ $request->estimated_cost }}</td>
                        <td>{{ $request->status }}</td>
                        <td>{{ $request->comment }}</td>
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
@endsection