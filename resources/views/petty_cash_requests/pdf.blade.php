<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Petty Cash Request — {{ $request->reference_number }}</title>
    <style>
        @media print {
            @page { size: A4; margin: 10mm; }
        }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 5mm;
            background: #ffffff;
            color: #1e293b;
            font-size: 11px;
        }
        .container {
            width: 100%;
            padding: 6mm;
            border: 1.5px solid #1e293b;
            border-radius: 6px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6mm;
            border-bottom: 1.5px solid #1e293b;
            padding-bottom: 8px;
        }
        header img {
            max-width: 90px;
            max-height: 75px;
            margin-right: 12px;
        }
        .company-info {
            text-align: right;
            font-size: 11px;
            color: #475569;
            flex: 1;
            line-height: 1.6;
        }
        .company-info p { margin: 1px 0; }
        .company-name {
            text-align: center;
            font-size: 15px;
            color: #4f46e5;
            margin: 6px 0 0;
            font-weight: 700;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            margin: 8px 0 14px;
            letter-spacing: 0.05em;
            color: #0f172a;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table, th, td {
            border: 1px solid #475569;
        }
        th {
            background: #f1f5f9;
            padding: 5px 8px;
            text-align: left;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #334155;
        }
        td {
            padding: 5px 8px;
            text-align: left;
            font-size: 11px;
            vertical-align: top;
            color: #0f172a;
        }
        td strong { color: #334155; }
        .status-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-pending  { background: #fef3c7; color: #92400e; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .bottom-section {
            margin-top: 14px;
            padding: 14px 16px;
            border: 1px dashed #cbd5e1;
            border-radius: 5px;
        }
        .bottom-section table {
            width: 100%;
            border: none;
            border-collapse: collapse;
            margin: 0;
        }
        .bottom-section td {
            border: none;
            padding: 4px 8px 4px 0;
            font-size: 11px;
        }
        .footer {
            text-align: center;
            margin-top: 10mm;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-style: italic;
            font-size: 10.5px;
            color: #64748b;
        }
        .footer p { margin: 2px 0; }
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
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr><td><strong>Request ID:</strong></td><td>{{ $request->petty_cash_request_id }}</td></tr>
                <tr><td><strong>User ID:</strong></td><td>{{ $request->user_id }}</td></tr>
                <tr><td><strong>Requested By:</strong></td><td>{{ Auth::user()->full_name }}</td></tr>
                <tr><td><strong>Title:</strong></td><td>{{ $request->title }}</td></tr>
                <tr><td><strong>Description:</strong></td><td>{{ $request->description ?? 'N/A' }}</td></tr>
                <tr><td><strong>Amount Requested:</strong></td><td>{{ number_format($request->amount_requested, 2) }} {{ $request->currency }}</td></tr>
                <tr><td><strong>Amount in Words:</strong></td><td>{{ $request->amount_requested_in_words ?? 'N/A' }}</td></tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td><span class="status-pill status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                </tr>
                <tr><td><strong>Amount Approved:</strong></td><td>{{ number_format($request->amount_approved, 2) }} {{ $request->currency }}</td></tr>
                <tr><td><strong>Approved in Words:</strong></td><td>{{ $request->amount_approved_in_words ?? 'N/A' }}</td></tr>
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
                <tr><td><strong>Company Country:</strong></td><td>{{ $request->company_country ?? 'N/A' }}</td></tr>
            </tbody>
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

        <div class="footer">
            <p>Thank you for your petty cash request.</p>
            <p>Please retain this document for your records.</p>
        </div>
    </div>
</body>
</html>