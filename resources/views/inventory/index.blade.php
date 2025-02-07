@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-success mb-4">
            <i class="fas fa-list-alt"></i> Inventory List
        </h1>

        @if (session('success'))
            <div class="alert alert-success fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-4">
            <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle"></i> Add New Item
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Tag Number</th>
                        <th>Item Type</th>
                        <th>Reference Number</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Description</th>
                        <th>Stock In Date</th>
                        <th>Managed By</th>
                        <th>Issued Stocks</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventories as $inventory)
                        <tr class="align-middle">
                            <td>{{ $inventory->item_name }}</td>
                            <td>{{ $inventory->tag_number }}</td>
                            <td>{{ ucfirst($inventory->item_type) }}</td>
                            <td>{{ $inventory->reference_number }}</td>
                            <td>{{ $inventory->quantity }}</td>
                            <td>{{ number_format($inventory->unit_price, 2) }}</td>
                            <td>{{ number_format($inventory->total_price, 2) }}</td>
                            <td>{{ $inventory->description }}</td>
                            <td>{{ $inventory->stock_in_date }}</td>
                            <td>{{ optional(\App\Models\User::find($inventory->managed_by))->full_name ?? 'N/A' }}</td>
                            <td>
                                <form action="{{ route('inventory.stockOut', $inventory->inventory_id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <div class="form-group">
                                        <label for="issued_quantity">Issued Quantity</label>
                                        <input type="number" name="issued_quantity" class="form-control" min="1"
                                            max="{{ $inventory->quantity }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="issued_unit_price">Issued Unit Price</label>
                                        <input type="number" name="issued_unit_price" class="form-control" required
                                            step="0.01" min="0">
                                    </div>
                                    <div class="form-group">
                                        <label for="issued_department">Issued Department</label>
                                        <input type="text" name="issued_department" class="form-control" maxlength="255">
                                    </div>
                                    <p>Issued Total Price: {{ number_format($inventory->issued_total_price ?? 0, 2) }}</p>

                                    <div class="form-group">
                                        <label for="employee_comment">Employee Comment</label>
                                        <select name="employee_comment" class="form-control" required>
                                            <option value="received">Received</option>
                                            <option value="not_received">Not Received</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="received_by">Received By</label>
                                        {{ optional($inventory->receivedBy)->full_name ?? 'N/A' }}
                                    </div>
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-arrow-out"></i> Stock Out
                                    </button>
                                </form>
                            </td>
                            <td>
                                <p>Balance Quantity: {{ $inventory->balance_quantity ?? 'N/A' }}</p>
                                <p>Balance Total: {{ number_format($inventory->balance_total_price ?? 0, 2) }}</p>
                            </td>
                            <td>
                                <a href="{{ route('inventory.edit', $inventory->inventory_id) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('inventory.destroy', $inventory->inventory_id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this item?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
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

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
@endsection
