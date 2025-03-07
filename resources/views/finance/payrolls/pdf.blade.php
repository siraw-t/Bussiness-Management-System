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
                /* Set the size to A4 */
                margin: 10mm;
                /* Set minimal margins */
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 5mm;
            /* Reduced padding */
            background-color: #ffffff;
            /* White background */
        }

        .container {
            width: 100%;
            /* Full width of the A4 */
            padding: 5mm;
            /* Padding inside the container */
            border: 1px solid #000;
            /* Border for the container */
            border-radius: 5px;
            /* Reduced border radius */
            box-sizing: border-box;
            /* Include padding and border in element's width and height */
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5mm;
            /* Reduced margin */
            border-bottom: 1px solid #000;
            /* Header border */
            padding-bottom: 5px;
            /* Reduced padding */
        }

        header img {
            max-width: 90px;
            /* Logo size */
            margin-right: 10px;
            /* Margin for logo */
        }

        .company-info {
            text-align: left;
            font-size: 14px;
            /* Font size */
            color: #555;
            /* Text color */
        }

        .company-name {
            text-align: center;
            font-size: 18px;
            /* Font size */
            color: #007bff;
            /* Company color */
            margin: 5px 0;
            /* Margin */
            width: 100%;
            /* Full width */
        }

        .header-right {
            text-align: center;
            /* Center align text */
            font-size: 14px;
            /* Font size */
            margin: 0;
            /* Remove margins */
            flex-basis: 100%;
            /* Take full width */
        }

        .payroll-title {
            text-align: center;
            font-size: 20px;
            /* Font size */
            font-weight: bold;
            /* Bold font */
            margin: 5px 0;
            /* Margin */
        }

        .details-table {
            width: 100%;
            /* Full width of the table */
            border-collapse: collapse;
            /* Collapse borders */
            margin-bottom: 10px;
            /* Margin */
        }

        .details-table,
        .details-table th,
        .details-table td {
            border: 1px solid #000;
            /* Table borders */
        }

        .details-table th,
        .details-table td {
            padding: 5px;
            /* Padding */
            text-align: left;
            /* Text alignment */
            font-size: 16px;
            /* Font size */
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            /* Space between sections */
            margin-top: 5mm;
            /* Margin */
            font-size: 14px;
            /* Font size */
        }

        footer {
            text-align: center;
            /* Centered footer */
            margin-top: 10mm;
            /* Margin */
            font-style: italic;
            /* Italic style */
            font-size: 14px;
            /* Font size */
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <header>
            <div class="company-info">
                <img src="{{ public_path('storage/' . Auth::user()->company->logo) }}" alt="Company Logo">
                <p>{{ $company->city }}, {{ $company->country }}</p>
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>{{ $payroll->reference_number }}</p>

            </div>
            <div class="header-right">
                <p><strong>Employee Name:</strong> {{ $payroll->employee->full_name }}</p>
                <p><strong>Job Title:</strong> {{ $payroll->job_title }}</p>
                <p><strong>Pay Period:</strong> {{ $payroll->pay_period }}</p>
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
