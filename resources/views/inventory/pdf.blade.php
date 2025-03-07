<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Details</title>
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

        .inventory-title {
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
                <p>Ref: {{ $inventory->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="inventory-title">Inventory Details</h2>

        <!-- Details Table -->
        <table class="details-table">
            <tr>
                <th class="label">Detail</th>
                <th class="value">Information</th>
            </tr>
            <tr>
                <td class="label"><strong>Item Name:</strong></td>
                <td class="value">{{ $inventory->item_name }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Description:</strong></td>
                <td class="value">{{ $inventory->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Tag Number:</strong></td>
                <td class="value">{{ $inventory->tag_number }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Item Type:</strong></td>
                <td class="value">{{ ucfirst($inventory->item_type) }}</td>
            </tr>

            <tr>
                <td class="label"><strong>Quantity:</strong></td>
                <td class="value">{{ $inventory->quantity }} {{ $inventory->unit }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Unit Price:</strong></td>
                <td class="value">{{ number_format($inventory->unit_price, 2) }} {{ $inventory->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Total Price:</strong></td>
                <td class="value">{{ number_format($inventory->total_price, 2) }} {{ $inventory->currency }}</td>
            </tr>

            <tr>
                <td class="label"><strong>Stock In Date:</strong></td>
                <td class="value">{{ $inventory->stock_in_date ? $inventory->stock_in_date->format('Y-m-d') : 'N/A' }}
                </td>
            </tr>

            <tr>
                <td class="label"><strong>Issued Quantity:</strong></td>
                <td class="value">{{ $inventory->issued_quantity ?? 'N/A' }} {{ $inventory->issued_unit }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Stock Out Date:</strong></td>
                <td class="value">
                    {{ $inventory->stock_out_date ? $inventory->stock_out_date->format('Y-m-d') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Issued Department:</strong></td>
                <td class="value">{{ $inventory->issued_department ?? 'N/A' }}</td>
            </tr>

            <tr>
                <td class="label"><strong>Balance Quantity:</strong></td>
                <td class="value">{{ $inventory->balance_quantity ?? 'N/A' }} {{ $inventory->unit }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Balance Total Price:</strong></td>
                <td class="value">{{ number_format($inventory->balance_total_price ?? 0, 2) }}
                    {{ $inventory->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Created At:</strong></td>
                <td class="value">{{ $inventory->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Updated At:</strong></td>
                <td class="value">{{ $inventory->updated_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <!-- Bottom Section with Finance Comments -->
        <div class="bottom-section">

            <div>
                <p><strong>Received By:</strong>
                    {{ optional(\App\Models\User::find($inventory->received_by))->full_name ?? 'N/A' }}</p>
                <p><strong>Managed By:</strong>
                    {{ optional(\App\Models\User::find($inventory->managed_by))->full_name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <p>Thank you for managing your inventory.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
