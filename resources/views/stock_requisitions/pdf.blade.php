<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Requisition — {{ $requisition->reference_number }}</title>
    <style>
        @page { margin: 24px; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            color: #1e293b;
            font-size: 12px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 28px 32px;
            border: 2px solid #1e293b;
            border-radius: 10px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 14px;
        }
        header img {
            max-width: 100px;
            max-height: 80px;
            margin-right: 20px;
        }
        .company-info {
            text-align: right;
            font-size: 11px;
            color: #475569;
            flex: 1;
            line-height: 1.5;
        }
        .company-info p { margin: 2px 0; }
        .company-name {
            text-align: center;
            font-size: 17px;
            color: #4f46e5;
            margin: 10px 0 0;
            width: 100%;
            font-weight: 700;
        }
        .requisition-title {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            margin: 18px 0;
            letter-spacing: 0.04em;
            color: #0f172a;
            text-transform: uppercase;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .details-table,
        .details-table th,
        .details-table td {
            border: 1px solid #475569;
        }
        .details-table th {
            background: #f1f5f9;
            padding: 5px 8px;
            text-align: left;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #334155;
        }
        .details-table td {
            padding: 5px 8px;
            text-align: left;
            font-size: 11px;
            vertical-align: top;
        }
        .details-table td.label {
            width: 40%;
            background: #f8fafc;
            color: #334155;
        }
        .details-table td.value {
            width: 60%;
            color: #0f172a;
            font-weight: 500;
        }
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
            margin-top: 24px;
            padding: 14px 16px;
            border: 1px dashed #cbd5e1;
            border-radius: 5px;
        }
        .bottom-section p { margin: 4px 0; }
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            font-style: italic;
            color: #64748b;
            font-size: 10.5px;
        }
        footer p { margin: 3px 0; }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <img src="{{ public_path('storage/' . Auth::user()->company->logo) }}" alt="Company Logo">
            <div class="company-info">
                <p>{{ $company->city }}, {{ $company->country }}</p>
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>Reference Number: {{ $requisition->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="requisition-title">Stock Requisition</h2>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="label"><strong>Item Name:</strong></td><td class="value">{{ $requisition->item_name }}</td></tr>
                <tr><td class="label"><strong>Description:</strong></td><td class="value">{{ $requisition->description ?? 'N/A' }}</td></tr>
                <tr><td class="label"><strong>Quantity Requested:</strong></td><td class="value">{{ $requisition->quantity_requested }} {{ $requisition->unit_requested ?? 'N/A' }}</td></tr>
                <tr><td class="label"><strong>Quantity Approved:</strong></td><td class="value">{{ $requisition->quantity_approved ?? 'N/A' }} {{ $requisition->unit_requested ?? 'N/A' }}</td></tr>
                <tr>
                    <td class="label"><strong>Status:</strong></td>
                    <td class="value">
                        <span class="status-pill status-{{ $requisition->status }}">{{ ucfirst($requisition->status) }}</span>
                    </td>
                </tr>
                <tr><td class="label"><strong>Created At:</strong></td><td class="value">{{ $requisition->created_at->format('Y-m-d H:i:s') }}</td></tr>
                <tr><td class="label"><strong>Updated At:</strong></td><td class="value">{{ $requisition->updated_at->format('Y-m-d H:i:s') }}</td></tr>
            </tbody>
        </table>

        <div class="bottom-section">
            <p><strong>Employee Comment:</strong> {{ $requisition->employee_comment ?? 'N/A' }}</p>
            <p><strong>Approved By:</strong> {{ optional($requisition->approver)->full_name ?? 'Not Approved' }}</p>
            <p><strong>Manager Comment:</strong> {{ $requisition->manager_comment ?? 'N/A' }}</p>
        </div>

        <footer>
            <p>Thank you for your stock requisition.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>
</html>