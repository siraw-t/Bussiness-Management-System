<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher</title>
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
            /* Padding */
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
            /* Rounded corners */
            box-sizing: border-box;
            /* Include padding and border in width and height */
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

        .payment-voucher-title {
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
            padding: 3px;
            /* Reduced padding */
            text-align: left;
            /* Text alignment */
            font-size: 16px;
            /* Font size */
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            /* Space between sections */
            margin-top: 10px;
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
                <p>Ref: {{ $request->reference_no }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="payment-voucher-title">Payment Voucher</h2>

        <!-- Details Table -->
        <table class="details-table">
            <tr>
                <th class="label">Detail</th>
                <th class="value">Information</th>
            </tr>
            <tr>
                <td class="label"><strong>Full Name:</strong></td>
                <td class="value">{{ $request->user->full_name }}</td>
            </tr>

            <tr>
                <td class="label"><strong>Request Date:</strong></td>
                <td class="value">{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Department:</strong></td>
                <td class="value">{{ $request->department }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Item Name:</strong></td>
                <td class="value">{{ $request->item_name }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Description:</strong></td>
                <td class="value">{{ $request->item_description }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Quantity:</strong></td>
                <td class="value">{{ $request->quantity }} {{ $request->unit }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Amount:</strong></td>
                <td class="value">{{ number_format($request->amount, 2) }} {{ $request->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Withholding:</strong></td>
                <td class="value">{{ number_format($request->with_holding, 2) ?? '0.00' }} {{ $request->currency }}
                </td>
            </tr>
            <tr>
                <td class="label"><strong>VAT:</strong></td>
                <td class="value">{{ number_format($request->vat, 2) ?? '0.00' }} {{ $request->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Total:</strong></td>
                <td class="value">{{ number_format($request->total, 2) }} {{ $request->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Net Payment:</strong></td>
                <td class="value">{{ number_format($request->net_payment, 2) }} {{ $request->currency }}</td>
            </tr>

            <tr>
                <td class="label"><strong>Cost Center:</strong></td>
                <td class="value">{{ $request->cost_center }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Account Code:</strong></td>
                <td class="value">{{ $request->account_code }}</td>
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
                    {{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p><strong>Approved By:</strong>
                    {{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</p>
                <p><strong>Manager Comments:</strong> {{ $request->manager_comment ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
