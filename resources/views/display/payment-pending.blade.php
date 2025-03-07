<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .header {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 2em;
        }

        .table {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .thead-light th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.02);
            transition: all 0.2s ease;
        }

        .action-buttons {
            min-width: 120px;
        }

        .form-group {
            margin-bottom: 0;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        @media (max-width: 768px) {
            .table-responsive {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-4 mb-5">
        <h2 class="header text-center text-primary mb-4">Payment Requests</h2>
        <div class="text-center mb-4">
            <a class="btn btn-outline-primary btn-lg" href="{{ route('finance.dashboard') }}">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <h5 class="text-primary text-center mb-4">Pending Payment Requests</h5>

        @if ($pendingPaymentRequests->isEmpty())
            <div class="alert alert-info text-center" role="alert">
                No payment requests found at this time.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Reference No</th>
                            <th>Request Date</th>
                            <th>Department</th>
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
                            <th>Finance Comment</th>
                            <th>Manager Comment</th>
                            <th>Reviewed By</th>
                            <th>Approved By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingPaymentRequests as $request)
                            <tr>
                                <td>{{ $request->reference_no ?? 'N/A' }}</td>
                                <td>{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $request->department ?? 'N/A' }}</td>
                                <td>{{ $request->item_description }}</td>
                                <td>{{ $request->item_name ?? 'N/A' }}</td>
                                <td>{{ $request->quantity ?? 'N/A' }} {{ $request->unit ?? 'N/A' }}</td>
                                <td>{{ number_format($request->amount, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ number_format($request->vat, 2) ?? '0.00' }} {{ $request->currency ?? 'N/A' }}
                                </td>
                                <td>{{ number_format($request->total, 2) }} {{ $request->currency ?? 'N/A' }}</td>
                                <td>{{ number_format($request->net_payment, 2) }} {{ $request->currency ?? 'N/A' }}
                                </td>
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
                                <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                                <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                                <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}
                                </td>
                                <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}
                                </td>
                                <td class="action-buttons">
                                    <form
                                        action="{{ route('finance.payment.comment', ['payment_request_id' => $request->payment_request_id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <div class="form-group mb-0">
                                            <textarea class="form-control" id="finance-comment-{{ $request->payment_request_id }}" name="finance_comment"
                                                rows="2" placeholder="Add a comment..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm mt-1">Submit</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
