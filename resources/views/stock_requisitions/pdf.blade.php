<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Requisition PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 3px solid #000;
            border-radius: 10px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        header img {
            max-width: 100px;
            margin-right: 20px;
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
            font-size: 26px;
            color: #007bff;
            margin: 10px 0;
            width: 100%;
        }

        .requisition-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table,
        .details-table th,
        .details-table td {
            border: 1px solid #000;
        }

        .details-table th,
        .details-table td {
            padding: 10px;
            text-align: left;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
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
                <p>Reference Number: {{ $requisition->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>

        </header>


        <h2 class="requisition-title">Stock Requisition</h2>

        <!-- Main Details Table -->
        <table class="details-table">
            <tr>
                <th class="label">Detail</th>
                <th class="value">Information</th>
            </tr>
            <tr>
                <td class="label"><strong>Item Name:</strong></td>
                <td class="value">{{ $requisition->item_name }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Description:</strong></td>
                <td class="value">{{ $requisition->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Quantity Requested:</strong></td>
                <td class="value">{{ $requisition->quantity_requested }} {{ $requisition->unit_requested ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="label"><strong>Quantity Approved:</strong></td>
                <td class="value">{{ $requisition->quantity_approved ?? 'N/A' }}
                    {{ $requisition->unit_requested ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Status:</strong></td>
                <td class="value">{{ ucfirst($requisition->status) }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Created At:</strong></td>
                <td class="value">{{ $requisition->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Updated At:</strong></td>
                <td class="value">{{ $requisition->updated_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <div>
                <p><strong>Employee Comment:</strong> {{ $requisition->employee_comment ?? 'N/A' }}</p>
                <p><strong>Approved By:</strong> {{ optional($requisition->approver)->full_name ?? 'Not Approved' }}
                </p>
                <p><strong>Manager Comment:</strong> {{ $requisition->manager_comment ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <p>Thank you for your stock requisition.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
