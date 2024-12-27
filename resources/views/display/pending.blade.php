<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .no-requests {
            text-align: center;
            font-size: 1.1rem;
            color: #6c757d;
        }
        .action-buttons {
            text-align: center;
        }
        .table-container {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<div class="container mb-5">
    <h2 class="header">Pending Requests</h2>
    <a class="nav-link" href="{{ route('manager.dashboard') }}">Back</a>

    <!-- Pending Leave Requests -->
    <div class="table-container">
        <h5>Pending Leave Requests</h5>
        @if ($pendingLeaveRequests->isEmpty())
            <p class="no-requests">No pending leave requests at this time.</p>
        @else
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Reason</th>
                        <th>Requested By</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingLeaveRequests as $request)
                    <tr>
                        <td>{{ $request->reason }}</td>
                        <td>{{ $request->user->full_name }}</td>
                        <td>{{ $request->start_date }}</td>
                        <td>{{ $request->end_date }}</td>
                        <td>{{ ucfirst($request->status) }}</td>
                        <td class="action-buttons">
                            <form action="{{ route('manager.leave.approve', ['leave_request_id' => $request->leave_request_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Approve Leave">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('manager.leave.reject', ['leave_request_id' => $request->leave_request_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" title="Reject Leave">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Pending Payment Requests -->
    <div class="table-container">
        <h5>Pending Payment Requests</h5>
        @if ($pendingPaymentRequests->isEmpty())
            <p class="no-requests">No pending payment requests at this time.</p>
        @else
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingPaymentRequests as $request)
                    <tr>
                        <td>{{ $request->description }}</td>
                        <td>${{ number_format($request->amount, 2) }}</td>
                        <td>{{ $request->user->full_name }}</td>
                        <td>{{ ucfirst($request->status) }}</td>
                        <td class="action-buttons">
                            <form action="{{ route('manager.payment.approve', ['payment_request_id' => $request->payment_request_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Approve Payment">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('manager.payment.reject', ['payment_request_id' => $request->payment_request_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" title="Reject Payment">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Pending Purchase Requests -->
    <div class="table-container">
        <h5>Pending Purchase Requests</h5>
        @if ($pendingPurchaseRequests->isEmpty())
            <p class="no-requests">No pending purchase requests at this time.</p>
        @else
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Estimated Cost</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingPurchaseRequests as $request)
                    <tr>
                        <td>{{ $request->item_name }}</td>
                        <td>{{ $request->quantity }}</td>
                        <td>${{ number_format($request->estimated_cost, 2) }}</td>
                        <td>{{ $request->user->full_name }}</td>
                        <td>{{ ucfirst($request->status) }}</td>
                        <td class="action-buttons">
                            <form action="{{ route('manager.purchase.approve', ['purchase_request_id' => $request->purchase_request_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Approve Purchase">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('manager.purchase.reject', ['purchase_request_id' => $request->purchase_request_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" title="Reject Purchase">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Pending Payrolls -->
    <div class="table-container">
        <h5>Pending Payrolls</h5>
        @if ($pendingPayrolls->isEmpty())
            <p class="no-requests">No pending payrolls at this time.</p>
        @else
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Pay Period</th>
                        <th>Basic Salary</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingPayrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->employee->full_name }}</td>
                        <td>{{ $payroll->pay_period }}</td>
                        <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>${{ number_format($payroll->deductions ?? 0, 2) }}</td>
                        <td>${{ number_format($payroll->net_salary, 2) }}</td>
                        <td>{{ ucfirst($payroll->status) }}</td>
                        <td class="action-buttons">
                            <form action="{{ route('manager.payroll.approve', ['payroll_id' => $payroll->payroll_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Approve Payroll">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('manager.payroll.reject', ['payroll_id' => $payroll->payroll_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" title="Reject Payroll">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

</body>
</html>