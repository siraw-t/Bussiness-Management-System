<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Record PDF</title>
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

        .budget-title {
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
                <img src="{{ public_path('storage/' . $company->logo) }}" alt="Company Logo">
                <p>{{ optional($company)->city ?? 'N/A' }}, {{ optional($company)->country ?? 'N/A' }}</p>
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>Budget Code: {{ $budget->budget_code }}</p>
                <p>Reference Number: {{ $budget->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ optional($company)->company_name ?? 'N/A' }}</h1>
        </header>

        <h2 class="budget-title">Budget Record</h2>

        <!-- Main Details Table -->
        <table class="details-table">
            <tr>
                <th class="label">Detail</th>
                <th class="value">Information</th>
            </tr>


            <tr>
                <td class="label"><strong>Forecast Period:</strong></td>
                <td class="value">{{ ucfirst($budget->forecast_period) }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Budget Item:</strong></td>
                <td class="value">{{ $budget->budget_item }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Budget Description:</strong></td>
                <td class="value">{{ $budget->budget_description }}</td>
            </tr>


            <tr>
                <td class="label"><strong>Department:</strong></td>
                <td class="value">{{ $budget->department }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Budget Source:</strong></td>
                <td class="value">{{ $budget->budget_source }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Quantity:</strong></td>
                <td class="value">{{ $budget->quantity }} {{ $budget->unit }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Unit Cost:</strong></td>
                <td class="value">{{ number_format($budget->unit_cost, 2) }} {{ $budget->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Total Budget:</strong></td>
                <td class="value">{{ number_format($budget->total_budget, 2) }} {{ $budget->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Status:</strong></td>
                <td class="value">{{ ucfirst($budget->status) }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Created At:</strong></td>
                <td class="value">{{ $budget->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Updated At:</strong></td>
                <td class="value">{{ $budget->updated_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <p><strong>Created By:</strong> {{ $budget->creator ? $budget->creator->full_name : 'N/A' }}</p>
            <p><strong>Approved By:</strong> {{ $budget->approver ? $budget->approver->full_name : 'N/A' }}</p>
            <p><strong>Manager Comments:</strong> {{ $budget->manager_comment ?? 'N/A' }}</p>
        </div>

        <!-- Footer -->
        <footer>
            <p>Thank you for reviewing this budget record.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
