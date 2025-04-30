<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Record PDF</title>
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
            background-color: #ffffff;
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
            /* Align text to the right */
            font-size: 14px;
            color: #555;
            flex: 1;
            /* Allow it to take remaining space */
        }

        .company-name {
            text-align: center;
            font-size: 18px;
            color: #007bff;
            margin: 5px 0;
            width: 100%;
        }

        .payroll-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .details-table,
        .details-table th,
        .details-table td {
            border: 1px solid #000;
        }

        .details-table th,
        .details-table td {
            padding: 5px;
            text-align: left;
            font-size: 16px;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 5mm;
            font-size: 14px;
        }

        footer {
            text-align: center;
            margin-top: 10mm;
            font-style: italic;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
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

        <!-- Main Details Table -->
        <table class="details-table">
            <tr>
                <th class="label">Detail</th>
                <th class="value">Information</th>
            </tr>
            <tr>
                <td class="label"><strong>Basic Salary:</strong></td>
                <td class="value">{{ number_format($payroll->basic_salary, 2) }} {{ $payroll->currency }}</td>
            </tr>

            @if ($payroll->company_country !== 'Uganda')
                <tr>
                    <td class="label"><strong>Transport Allowance:</strong></td>
                    <td class="value">{{ number_format($payroll->transport_allowance ?? 0, 2) }}
                        {{ $payroll->currency }}</td>
                </tr>
                <tr>
                    <td class="label"><strong>Taxable Transport Allowance:</strong></td>
                    <td class="value">{{ number_format($payroll->taxable_transport_allowance ?? 0, 2) }}
                        {{ $payroll->currency }}</td>
                </tr>
            @endif

            <tr>
                <td class="label"><strong>Position Allowance:</strong></td>
                <td class="value">{{ number_format($payroll->position_allowance ?? 0, 2) }} {{ $payroll->currency }}
                </td>
            </tr>
            <tr>
                <td class="label"><strong>Total Taxable Earnings:</strong></td>
                <td class="value">{{ number_format($payroll->total_taxable_earnings, 2) }} {{ $payroll->currency }}
                </td>
            </tr>
            <tr>
                <td class="label"><strong>Income Tax:</strong></td>
                <td class="value">{{ number_format($payroll->income_tax, 2) }} {{ $payroll->currency }}</td>
            </tr>

            @if ($payroll->company_country !== 'Uganda')
                <tr>
                    <td class="label"><strong>Pension Fund 7%:</strong></td>
                    <td class="value">{{ number_format($payroll->pension_fund_7, 2) }} {{ $payroll->currency }}</td>
                </tr>
                <tr>
                    <td class="label"><strong>Pension Fund 11%:</strong></td>
                    <td class="value">{{ number_format($payroll->pension_fund_11, 2) }} {{ $payroll->currency }}</td>
                </tr>
            @endif

            <tr>
                <td class="label"><strong>Salary Advance:</strong></td>
                <td class="value">{{ number_format($payroll->salary_advance ?? 0, 2) }} {{ $payroll->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Net Salary:</strong></td>
                <td class="value">{{ number_format($payroll->net_payment, 2) }} {{ $payroll->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Status:</strong></td>
                <td class="value">{{ ucfirst($payroll->status) }}</td>
            </tr>
        </table>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <div>
                <p><strong>Prepared By:</strong> {{ optional($payroll->preparedBy)->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p><strong>Approved By:</strong> {{ optional($payroll->approvedBy)->full_name ?? 'Not Approved' }}</p>
                <p><strong>Manager Comments:</strong> {{ $payroll->manager_comment ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <p>Thank you for your service.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
