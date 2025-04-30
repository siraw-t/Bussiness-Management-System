<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petty Cash Request PDF</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 5mm;
            background: #fff;
        }

        .container {
            width: 100%;
            padding: 5mm;
            border: 1px solid #000;
            border-radius: 5px;
            box-sizing: border-box;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5mm;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        header img {
            max-width: 90px;
            margin-right: 10px;
        }

        .company-info {
            text-align: right;
            font-size: 14px;
            color: #555;
            flex: 1;
        }

        .company-name {
            text-align: center;
            font-size: 18px;
            color: #007bff;
            margin: 5px 0;
            width: 100%;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            font-size: 16px;
        }

        .bottom-section {
            margin-top: 10mm;
        }

        .bottom-section table {
            width: auto;
            border: none;
            border-collapse: collapse;
        }

        .bottom-section td {
            border: none;
            padding: 5px;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            margin-top: 10mm;
            font-style: italic;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <img src="{{ public_path('storage/' . Auth::user()->company->logo) }}" alt="Company Logo">
            <div class="company-info">
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>Ref: {{ $request->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ Auth::user()->company->company_name }}</h1>
        </header>

        <h2 class="title">Petty Cash Request</h2>

        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td><strong>Request ID:</strong></td>
                <td>{{ $request->petty_cash_request_id }}</td>
            </tr>
            <tr>
                <td><strong>User ID:</strong></td>
                <td>{{ $request->user_id }}</td>
            </tr>
            <tr>
                <td><strong>Requested By:</strong></td>
                <td>{{ Auth::user()->full_name }}</td>
            </tr>
            <tr>
                <td><strong>Title:</strong></td>
                <td>{{ $request->title }}</td>
            </tr>
            <tr>
                <td><strong>Description:</strong></td>
                <td>{{ $request->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Amount Requested:</strong></td>
                <td>{{ number_format($request->amount_requested, 2) }} {{ $request->currency }}</td>
            </tr>
            <tr>
                <td><strong>Amount in Words:</strong></td>
                <td>{{ $request->amount_requested_in_words ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>{{ ucfirst($request->status) }}</td>
            </tr>
            <tr>
                <td><strong>Amount Approved:</strong></td>
                <td>{{ number_format($request->amount_approved, 2) }} {{ $request->currency }}</td>
            </tr>
            <tr>
                <td><strong>Approved in Words:</strong></td>
                <td>{{ $request->amount_approved_in_words ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Attachments:</strong></td>
                <td>
                    @if ($request->attachments)
                        @foreach (json_decode($request->attachments) as $file)
                            <div>{{ basename($file) }}</div>
                        @endforeach
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Company Country:</strong></td>
                <td>{{ $request->company_country ?? 'N/A' }}</td>
            </tr>
        </table>

        <div class="bottom-section">
            <table>
                <tr>
                    <td><strong>Manager Comment:</strong></td>
                    <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Finance Comment:</strong></td>
                    <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Approved By:</strong></td>
                    <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Checked By:</strong></td>
                    <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <footer class="footer">
            <p>Thank you for your petty cash request.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
