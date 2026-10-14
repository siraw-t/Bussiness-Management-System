<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll Record — {{ $payroll->reference_number }}</title>
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
        .payroll-title {
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
            width: 45%;
            background: #f8fafc;
            color: #334155;
        }
        .details-table td.value {
            width: 55%;
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
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px dashed #cbd5e1;
        }
        .bottom-section > div { flex: 1; }
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
            <img src="{{ public_path('storage/' . Auth::user()->company->logo) }}" alt="Company Logo">
            <div class="company-info">
                <p>{{ $company->city }}, {{ $company->country }}</p>
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>Ref: {{ $payroll->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="payroll-title">Payroll Record</h2>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="label"><strong>Basic Salary:</strong></td><td class="value">{{ number_format($payroll->basic_salary, 2) }} {{ $payroll->currency }}</td></tr>

                @if ($payroll->company_country !== 'Uganda')
                    <tr><td class="label"><strong>Transport Allowance:</strong></td><td class="value">{{ number_format($payroll->transport_allowance ?? 0, 2) }} {{ $payroll->currency }}</td></tr>
                    <tr><td class="label"><strong>Taxable Transport Allowance:</strong></td><td class="value">{{ number_format($payroll->taxable_transport_allowance ?? 0, 2) }} {{ $payroll->currency }}</td></tr>
                @endif

                <tr><td class="label"><strong>Position Allowance:</strong></td><td class="value">{{ number_format($payroll->position_allowance ?? 0, 2) }} {{ $payroll->currency }}</td></tr>
                <tr><td class="label"><strong>Total Taxable Earnings:</strong></td><td class="value">{{ number_format($payroll->total_taxable_earnings, 2) }} {{ $payroll->currency }}</td></tr>
                <tr><td class="label"><strong>Income Tax:</strong></td><td class="value">{{ number_format($payroll->income_tax, 2) }} {{ $payroll->currency }}</td></tr>

                @if ($payroll->company_country !== 'Uganda')
                    <tr><td class="label"><strong>Pension Fund 7%:</strong></td><td class="value">{{ number_format($payroll->pension_fund_7, 2) }} {{ $payroll->currency }}</td></tr>
                    <tr><td class="label"><strong>Pension Fund 11%:</strong></td><td class="value">{{ number_format($payroll->pension_fund_11, 2) }} {{ $payroll->currency }}</td></tr>
                @endif

                <tr><td class="label"><strong>Salary Advance:</strong></td><td class="value">{{ number_format($payroll->salary_advance ?? 0, 2) }} {{ $payroll->currency }}</td></tr>
                <tr><td class="label"><strong>Net Salary:</strong></td><td class="value">{{ number_format($payroll->net_payment, 2) }} {{ $payroll->currency }}</td></tr>
                <tr>
                    <td class="label"><strong>Status:</strong></td>
                    <td class="value"><span class="status-pill status-{{ $payroll->status }}">{{ ucfirst($payroll->status) }}</span></td>
                </tr>
            </tbody>
        </table>

        <div class="bottom-section">
            <div>
                <p><strong>Prepared By:</strong> {{ optional($payroll->preparedBy)->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p><strong>Approved By:</strong> {{ optional($payroll->approvedBy)->full_name ?? 'Not Approved' }}</p>
                <p><strong>Manager Comments:</strong> {{ $payroll->manager_comment ?? 'N/A' }}</p>
            </div>
        </div>

        <footer>
            <p>Thank you for your service.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>
</html>