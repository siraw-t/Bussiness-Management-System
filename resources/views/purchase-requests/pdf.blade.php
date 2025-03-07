<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Request PDF</title>
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
        }

        .company-name {
            text-align: center;
            font-size: 18px;
            /* Font size */
            color: #007bff;
            margin: 5px 0;
            /* Margin */
            width: 100%;
        }

        .purchase-request-title {
            text-align: center;
            font-size: 20px;
            /* Font size */
            font-weight: bold;
            margin: 5px 0;
            /* Margin */
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
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
            padding: 3px;
            /* Reduced padding */
            text-align: left;
            font-size: 20px;
            /* Font size */
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 5mm;
            /* Margin */
            font-size: 20px;
            /* Font size */
        }

        footer {
            text-align: center;
            margin-top: 10mm;
            /* Margin */
            font-style: italic;
            font-size: 20px;
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
                <p>Ref: {{ $request->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="purchase-request-title">Purchase Request</h2>

        <!-- Details Table -->
        <table class="details-table">
            <tr>
                <th class="label">Detail</th>
                <th class="value">Information</th>
            </tr>
            <tr>
                <td class="label"><strong>Full Name:</strong></td>
                <td class="value">{{ Auth::user()->full_name }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Item Name:</strong></td>
                <td class="value">{{ $request->item_name }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Description:</strong></td>
                <td class="value">{{ $request->item_description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Quantity:</strong></td>
                <td class="value">{{ $request->quantity }} {{ $request->unit }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Price:</strong></td>
                <td class="value">{{ $request->price }} {{ $request->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Total:</strong></td>
                <td class="value">{{ $request->total_price }} {{ $request->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Company:</strong></td>
                <td class="value">{{ $request->company_country }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Status:</strong></td>
                <td class="value">{{ ucfirst($request->status) }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Created At:</strong></td>
                <td class="value">{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Updated At:</strong></td>
                <td class="value">{{ $request->updated_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <div>
                <p><strong>Finance Comments:</strong> {{ $request->finance_comment ?? 'N/A' }}</p>
                <p><strong>Checked By:</strong>
                    {{ $request->checked_by ? $request->checkedBy->full_name : 'Not Checked' }}</p>
            </div>
            <div>
                <p><strong>Approved By:</strong>
                    {{ $request->approved_by ? $request->approvedBy->full_name : 'Not Approved' }}</p>
                <p><strong>Manager Comments:</strong> {{ $request->manager_comment ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <p>Thank you for your purchase request.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
