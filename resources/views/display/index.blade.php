{{-- @extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Inventory List</h2>
    <div class="table-responsive"> <!-- Make the table responsive -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Stock In Date</th>
                    <th>Stock Out Date</th>
                    <th>Approved By</th>
                    <th>Managed By</th>
                    <th>Created At</th> <!-- New column -->
                    <th>Updated At</th> <!-- New column -->
                </tr>
            </thead>
            <tbody>
                @if ($inventories->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No inventory items found.</td>
                    </tr>
                @else
                    @foreach ($inventories as $inventory)
                    <tr>
                        <td>{{ $inventory->item_name }}</td>
                        <td>{{ $inventory->quantity }}</td>
                        <td>{{ $inventory->description }}</td>
                        <td>{{ $inventory->stock_in_date }}</td>
                        <td>{{ $inventory->stock_out_date }}</td>
                        <td>
                            @if ($inventory->approved_by)
                                {{ optional(\App\Models\User::find($inventory->approved_by))->full_name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if ($inventory->managed_by)
                                {{ optional(\App\Models\User::find($inventory->managed_by))->full_name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $inventory->created_at->format('Y-m-d H:i:s') }}</td> <!-- Display created_at -->
                        <td>{{ $inventory->updated_at->format('Y-m-d H:i:s') }}</td> <!-- Display updated_at -->
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection --}}

@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center mb-4 text-info">Inventory Lists</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Tag Number</th>
                        <th>Item Type</th>
                        <th>Reference Number</th>
                        <th>Company Country</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Description</th>
                        <th>Stock In Date</th>
                        <th>Stock Out Date</th>
                        <th>Issued Quantity</th>
                        <th>Issued Unit Price</th>
                        <th>Issued Total Price</th>
                        <th>Issued Department</th>
                        <th>Managed By</th>
                        <th>Received By</th>
                        <th>Balance Quantity</th>
                        <th>Balance Total Price</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $userCompanyId = auth()->user()->company_id;

                        // Filter inventories based on the logged-in user's company
                        $filteredInventories = $inventories->filter(function ($inventory) use ($userCompanyId) {
                            return ($inventory->manager && $inventory->manager->company_id == $userCompanyId) ||
                                ($inventory->approver && $inventory->approver->company_id == $userCompanyId);
                        });
                    @endphp

                    @if ($filteredInventories->isEmpty())
                        <tr>
                            <td colspan="21" class="text-center">No inventory items found.</td>
                        </tr>
                    @else
                        @foreach ($filteredInventories as $inventory)
                            <tr class="align-middle" style="transition: background-color 0.3s;">
                                <td>{{ $inventory->item_name }}</td>
                                <td>{{ $inventory->tag_number ?? 'N/A' }}</td>
                                <td>{{ ucfirst($inventory->item_type) }}</td>
                                <td>{{ $inventory->reference_number }}</td>
                                <td>{{ $inventory->company_country }}</td>
                                <td
                                    style="background-color: {{ $inventory->quantity <= 3 ? 'red' : ($inventory->quantity <= 10 ? 'yellow' : 'green') }};">
                                    {{ $inventory->quantity }} {{ $inventory->unit }}
                                </td>
                                <td>{{ number_format($inventory->unit_price, 2) }} {{ $inventory->currency }}</td>
                                <td>{{ number_format($inventory->total_price, 2) }} {{ $inventory->currency }}</td>
                                <td>{{ $inventory->description ?? 'N/A' }}</td>
                                <td>{{ $inventory->stock_in_date ? \Illuminate\Support\Carbon::parse($inventory->stock_in_date)->format('Y-m-d') : 'N/A' }}
                                </td>
                                <td>{{ $inventory->stock_out_date ? \Illuminate\Support\Carbon::parse($inventory->stock_out_date)->format('Y-m-d') : 'N/A' }}
                                </td>
                                <td>{{ $inventory->issued_quantity ?? 'N/A' }} {{ $inventory->issued_unit }}</td>
                                <td>{{ $inventory->issued_unit_price ? number_format($inventory->issued_unit_price, 2) : 'N/A' }}
                                    {{ $inventory->issued_currency }}
                                </td>
                                <td>{{ $inventory->issued_total_price ? number_format($inventory->issued_total_price, 2) : 'N/A' }}
                                    {{ $inventory->issued_currency }}
                                </td>
                                <td>{{ $inventory->issued_department ?? 'N/A' }}</td>

                                <td>{{ $inventory->manager ? $inventory->manager->full_name : 'N/A' }}</td>
                                <td>{{ $inventory->received_by ? optional($inventory->receivedBy)->full_name : 'N/A' }}
                                </td>
                                <td>{{ $inventory->balance_quantity ?? 'N/A' }} {{ $inventory->unit }}</td>
                                <td>{{ $inventory->balance_total_price ? number_format($inventory->balance_total_price, 2) : 'N/A' }}
                                    {{ $inventory->currency }}
                                </td>
                                <td>{{ $inventory->created_at ? \Illuminate\Support\Carbon::parse($inventory->created_at)->format('Y-m-d H:i:s') : 'N/A' }}
                                </td>
                                <td>{{ $inventory->updated_at ? \Illuminate\Support\Carbon::parse($inventory->updated_at)->format('Y-m-d H:i:s') : 'N/A' }}
                                </td>

                                <td>
                                    <a href="{{ route('inventory.downloadPdf', $inventory->inventory_id) }}"
                                        class="btn btn-info">
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
