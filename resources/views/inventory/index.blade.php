@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-success mb-4">
            Inventory List
        </h1>

        {{-- @if (session('success'))
            <div class="alert alert-success fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif --}}

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
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Currency</th>
                        <th>Total Price</th>
                        <th>Description</th>
                        <th>Stock In Date</th>
                        <th>Managed By</th>
                        <th>Company Country</th>
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
                            <td
                                style="color: {{ $inventory->quantity <= 3 ? 'red' : ($inventory->quantity <= 10 ? 'yellow' : 'green') }};">
                                {{ $inventory->quantity }}
                            </td>
                            <td>{{ $inventory->unit }}</td>
                            <td>{{ number_format($inventory->unit_price, 2) }}</td>
                            <td>{{ $inventory->currency }}</td>
                            <td>{{ number_format($inventory->total_price, 2) }} {{ $inventory->currency }}</td>
                            <td>{{ $inventory->description }}</td>
                            <td>{{ $inventory->stock_in_date }}</td>
                            <td>{{ optional(\App\Models\User::find($inventory->managed_by))->full_name ?? 'N/A' }}</td>
                            <td>{{ $inventory->company_country }}</td>

                            <td>
                                <button class="btn btn-info btn-sm" data-toggle="modal"
                                    data-target="#stockOutModal{{ $inventory->inventory_id }}">
                                    <i class="fas fa-arrow-out"></i> Stock Out
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#balanceModal{{ $inventory->inventory_id }}">
                                    <i class="fas fa-eye"></i> View Balance
                                </button>
                            </td>
                            <td>
                                <a href="{{ route('inventory.edit', $inventory->inventory_id) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('inventory.destroy', $inventory->inventory_id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this item?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        <!-- Stock Out Modal -->
                        <div class="modal fade" id="stockOutModal{{ $inventory->inventory_id }}" tabindex="-1"
                            role="dialog" aria-labelledby="stockOutModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="stockOutModalLabel">Stock Out Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('inventory.stockOut', $inventory->inventory_id) }}"
                                            method="POST">
                                            @csrf

                                            <div class="form-group">
                                                <label for="issued_quantity">Issued Quantity</label>
                                                <input type="number" name="issued_quantity" class="form-control"
                                                    min="1" max="{{ $inventory->quantity }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="issued_unit_price">Issued Unit Price</label>
                                                <input type="number" name="issued_unit_price" class="form-control" required
                                                    step="0.01" min="0">
                                            </div>

                                            <div class="form-group">
                                                <label for="issued_total_price">Issued Total Price</label>
                                                <p id="issuedTotalPrice{{ $inventory->inventory_id }}">0.00</p>
                                            </div>

                                            <div class="form-group">
                                                <label for="stock_out_date">Stock Out Date</label>
                                                <input type="date" name="stock_out_date" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="issued_currency">Currency</label>
                                                <input type="text" name="issued_currency" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="issued_unit">Unit</label>
                                                <input type="text" name="issued_unit" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="issued_department">Issued Department</label>
                                                <input type="text" name="issued_department" class="form-control"
                                                    maxlength="255" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="received_by">Received By</label>
                                                <select name="received_by" class="form-control" required>
                                                    <option value="">Select User</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->user_id }}">{{ $user->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Balance Modal -->
                        <div class="modal fade" id="balanceModal{{ $inventory->inventory_id }}" tabindex="-1"
                            role="dialog" aria-labelledby="balanceModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="balanceModalLabel">Balance Information</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Balance Quantity: {{ $inventory->balance_quantity ?? 'N/A' }}
                                            {{ $inventory->unit }}</p>
                                        <p>Balance Total: {{ number_format($inventory->balance_total_price ?? 0, 2) }}
                                            {{ $inventory->currency }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function calculateTotals(form) {
            const issuedQuantity = parseInt(form.issued_quantity.value) || 0;
            const issuedUnitPrice = parseFloat(form.issued_unit_price.value) || 0;

            // Calculate the issued total price
            const issuedTotalPrice = issuedQuantity * issuedUnitPrice;
            document.getElementById('issuedTotalPrice' + form.issued_quantity.id.replace('issued_quantity', '')).innerText =
                issuedTotalPrice.toFixed(2);
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

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
