@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center text-primary mb-4">Stock Requisitions</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th>Quantity Requested</th>
                        <th>Unit Requested</th>
                        <th>Quantity Approved</th>
                        <th>Status</th>
                        <th>Requested By</th>
                        <th>Approved By</th>
                        <th>Manager Comments</th>
                        <th>Employee Comments</th>
                        <th>Reference Number</th>
                        <th>Company Country</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @auth
                        @php
                            $user = auth()->user();
                            $companyId = $user->company_id ?? null;

                            if ($companyId) {
                                $filteredStockRequisitions = $stockRequisitions->filter(function ($requisition) use (
                                    $companyId,
                                ) {
                                    return optional($requisition->user)->company_id == $companyId;
                                });
                            } else {
                                $filteredStockRequisitions = collect();
                            }
                        @endphp

                        @if ($filteredStockRequisitions->isEmpty())
                            <tr>
                                <td colspan="15" class="text-center text-danger">No stock requisitions found.</td>
                            </tr>
                        @else
                            @foreach ($filteredStockRequisitions as $requisition)
                                <tr class="align-middle" style="transition: background-color 0.3s;">
                                    <td>{{ $requisition->item_name }}</td>
                                    <td>{{ $requisition->description ?? 'N/A' }}</td>
                                    <td>{{ $requisition->quantity_requested }}</td>
                                    <td>{{ $requisition->unit_requested ?? 'N/A' }}</td>
                                    <td>{{ $requisition->quantity_approved ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $requisition->status === 'approved' ? 'bg-success' : ($requisition->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($requisition->status) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($requisition->user)->full_name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $approver = \App\Models\User::find($requisition->approved_by);
                                        @endphp
                                        {{ optional($approver)->full_name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $requisition->manager_comment ?? 'N/A' }}</td>
                                    <td>{{ $requisition->employee_comment ?? 'N/A' }}</td>
                                    <td>{{ $requisition->reference_number }}</td>
                                    <td>{{ $requisition->company_country }}</td>
                                    <td>{{ $requisition->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $requisition->updated_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('stock_requisitions.downloadPdf', $requisition) }}"
                                            class="btn btn-info btn-sm">Download PDF</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @else
                        <tr>
                            <td colspan="15" class="text-center">
                                <a href="{{ route('login') }}" class="btn btn-primary">Please log in to view stock
                                    requisitions.</a>
                            </td>
                        </tr>
                    @endauth
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
            transition: background-color 0.3s;
            /* Smooth transition */
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                /* Enables horizontal scroll on smaller screens */
            }

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

    <script>
        function updateEmployeeComment(requisitionId, commentValue) {
            fetch(`/stock_requisitions/${requisitionId}/updateComment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        employee_comment: commentValue
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Employee comment updated successfully.');
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    </script>
@endsection
