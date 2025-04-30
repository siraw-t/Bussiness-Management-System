<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Petty Cash Requests</title>
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

        .action-buttons .btn {
            margin-left: 5px;
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
    </style>
</head>

<body>
    <div class="container mt-4 mb-5">
        <h2 class="header text-center text-primary mb-4">Pending Petty Cash Requests</h2>
        <div class="text-center mb-4">
            <a class="btn btn-outline-primary btn-lg" href="{{ route('finance.dashboard') }}">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if ($pendingPettyCashRequests->isEmpty())
            <div class="alert alert-info text-center" role="alert">
                No pending petty cash requests at this time.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Reference Number</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Amount Requested</th>
                            <th>Amount Requested in Words</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Manager Comment</th>
                            <th>Finance Comment</th>
                            <th>Approved By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingPettyCashRequests as $request)
                            <tr>
                                <td>{{ $request->reference_number }}</td>
                                <td>{{ $request->title }}</td>
                                <td>{{ $request->description ?? 'N/A' }}</td>
                                <td>{{ number_format($request->amount_requested, 2) }} {{ $request->currency }}</td>
                                <td>{{ $request->amount_requested_in_words ?? 'N/A' }}</td>
                                <td>{{ $request->currency }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ ucfirst($request->status) }}</span>
                                </td>
                                <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                                <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                                <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}
                                </td>

                                <td class="action-buttons">
                                    <form
                                        action="{{ route('finance.petty_cash.comment', ['petty_cash_request_id' => $request->petty_cash_request_id]) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <!-- Comment Textarea -->
                                        <textarea name="finance_comment" rows="3" placeholder="Add a comment..." required></textarea>
                                        {{-- <!-- File Input -->
                                        <input type="file" name="attachments[]" multiple> --}}
                                        <!-- Submit Button -->
                                        <button type="submit">Submit</button>
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
