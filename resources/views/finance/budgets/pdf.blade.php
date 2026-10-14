<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budget Record — {{ $budget->reference_number }}</title>
    <style>
        @media print {
            @page { size: A4; margin: 10mm; }
        }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 5mm;
            background-color: #ffffff;
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
        .budget-title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            margin: 8px 0 14px;
            letter-spacing: 0.05em;
            color: #0f172a;
            text-transform: uppercase;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .details-table, .details-table th, .details-table td {
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
            margin-top: 14px;
            padding: 14px 16px;
            border: 1px dashed #cbd5e1;
            border-radius: 5px;
        }
        .bottom-section p { margin: 4px 0; font-size: 11px; }
        .bottom-section strong { color: #334155; }
        footer {
            text-align: center;
            margin-top: 10mm;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-style: italic;
            font-size: 10.5px;
            color: #64748b;
        }
        footer p { margin: 2px 0; }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <img src="{{ public_path('storage/' . $company->logo) }}" alt="Company Logo">
            <div class="company-info">
                <p>{{ optional($company)->city ?? 'N/A' }}, {{ optional($company)->country ?? 'N/A' }}</p>
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>Budget Code: {{ $budget->budget_code }}</p>
                <p>Reference Number: {{ $budget->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ optional($company)->company_name ?? 'N/A' }}</h1>
        </header>

        <h2 class="budget-title">Budget Record</h2>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="label"><strong>Forecast Period:</strong></td><td class="value">{{ ucfirst($budget->forecast_period) }}</td></tr>
                <tr><td class="label"><strong>Budget Item:</strong></td><td class="value">{{ $budget->budget_item }}</td></tr>
                <tr><td class="label"><strong>Budget Description:</strong></td><td class="value">{{ $budget->budget_description }}</td></tr>
                <tr><td class="label"><strong>Department:</strong></td><td class="value">{{ $budget->department }}</td></tr>
                <tr><td class="label"><strong>Budget Source:</strong></td><td class="value">{{ $budget->budget_source }}</td></tr>
                <tr><td class="label"><strong>Quantity:</strong></td><td class="value">{{ $budget->quantity }} {{ $budget->unit }}</td></tr>
                <tr><td class="label"><strong>Unit Cost:</strong></td><td class="value">{{ number_format($budget->unit_cost, 2) }} {{ $budget->currency }}</td></tr>
                <tr><td class="label"><strong>Total Budget:</strong></td><td class="value">{{ number_format($budget->total_budget, 2) }} {{ $budget->currency }}</td></tr>
                <tr>
                    <td class="label"><strong>Status:</strong></td>
                    <td class="value"><span class="status-pill status-{{ $budget->status }}">{{ ucfirst($budget->status) }}</span></td>
                </tr>
                <tr><td class="label"><strong>Created At:</strong></td><td class="value">{{ $budget->created_at->format('Y-m-d H:i:s') }}</td></tr>
                <tr><td class="label"><strong>Updated At:</strong></td><td class="value">{{ $budget->updated_at->format('Y-m-d H:i:s') }}</td></tr>
            </tbody>
        </table>

        <div class="bottom-section">
            <p><strong>Created By:</strong> {{ $budget->creator ? $budget->creator->full_name : 'N/A' }}</p>
            <p><strong>Approved By:</strong> {{ $budget->approver ? $budget->approver->full_name : 'N/A' }}</p>
            <p><strong>Manager Comments:</strong> {{ $budget->manager_comment ?? 'N/A' }}</p>
        </div>

        <footer>
            <p>Thank you for reviewing this budget record.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>
</html>