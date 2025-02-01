<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            background-color: #f9f9f9;
            /* Light background color */
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            /* Blue border for header */
            padding-bottom: 10px;
            color: #007bff;
            /* Header color */
        }

        .form-section {
            margin-bottom: 20px;
            padding: 20px;
            border: 2px solid #007bff;
            /* Blue border for sections */
            border-radius: 8px;
            background-color: #ffffff;
            /* White background for sections */
        }

        .form-row {
            display: flex;
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: bold;
            color: #333;
            /* Darker color for labels */
            width: 200px;
            /* Fixed width for labels */
        }

        .form-value {
            border-bottom: 1px solid #ccc;
            /* Underline for value fields */
            padding: 5px;
            flex: 1;
            /* Takes remaining space */
        }

        .signature-section {
            margin-top: 30px;
            border-top: 2px solid #007bff;
            /* Line above signature */
            padding-top: 10px;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
            /* Italic style for footer */
        }
    </style>
</head>

<body>
    <h1>Payroll Record</h1>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Employee Name:</span>
            <span class="form-value">{{ $payroll->employee->full_name }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Job Title:</span>
            <span class="form-value">{{ $payroll->job_title }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Pay Period:</span>
            <span class="form-value">{{ $payroll->pay_period }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Basic Salary:</span>
            <span class="form-value">${{ number_format($payroll->basic_salary, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Transport Allowance:</span>
            <span class="form-value">${{ number_format($payroll->transport_allowance ?? 0, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Taxable Transport Allowance:</span>
            <span class="form-value">${{ number_format($payroll->taxable_transport_allowance ?? 0, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Position Allowance:</span>
            <span class="form-value">${{ number_format($payroll->position_allowance ?? 0, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Total Taxable Earnings:</span>
            <span class="form-value">${{ number_format($payroll->total_taxable_earnings, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Income Tax:</span>
            <span class="form-value">${{ number_format($payroll->income_tax, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Pension Fund 7%:</span>
            <span class="form-value">${{ number_format($payroll->pension_fund_7, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Pension Fund 11%:</span>
            <span class="form-value">${{ number_format($payroll->pension_fund_11, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Salary Advance:</span>
            <span class="form-value">${{ number_format($payroll->salary_advance ?? 0, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Net Salary:</span>
            <span class="form-value">${{ number_format($payroll->net_payment, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Status:</span>
            <span class="form-value">{{ ucfirst($payroll->status) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Prepared By:</span>
            <span class="form-value">{{ optional($payroll->preparedBy)->full_name ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Approved By:</span>
            <span class="form-value">{{ optional($payroll->approvedBy)->full_name ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Manager Comments:</span>
            <span class="form-value">{{ $payroll->manager_comment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Created At:</span>
            <span class="form-value">{{ $payroll->created_at->format('Y-m-d H:i:s') }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Updated At:</span>
            <span class="form-value">{{ $payroll->updated_at->format('Y-m-d H:i:s') }}</span>
        </div>
    </div>

    <footer>
        <p>Thank you for reviewing this payroll record.</p>
        <p>Please retain this document for your records.</p>
    </footer>
</body>

</html>
