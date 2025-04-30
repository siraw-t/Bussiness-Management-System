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
                margin: 5mm;
                /* Reduced margins */
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
            max-width: 80px;
            /* Slightly smaller logo */
            margin-right: 10px;
        }

        .company-info {
            text-align: right;
            /* Align text to the right */
            font-size: 12px;
            /* Reduced font size */
            color: #555;
            flex: 1;
            /* Allow it to take remaining space */
            margin-left: 20px;
            /* Add margin to separate from logo */
        }

        .company-name {
            text-align: center;
            font-size: 16px;
            /* Reduced font size */
            color: #007bff;
            margin: 5px 0;
        }

        .payment-voucher-title {
            text-align: center;
            font-size: 18px;
            /* Reduced font size */
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
            padding: 2px;
            /* Reduced padding */
            text-align: left;
            font-size: 12px;
            /* Reduced font size */
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 12px;
            /* Reduced font size */
        }

        footer {
            text-align: center;
            margin-top: 10mm;
            font-style: italic;
            font-size: 12px;
            /* Reduced font size */
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
                <td class="label"><strong>Item Type:</strong></td>
                <td class="value">{{ $request->item_type }}</td>
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
                <td class="label"><strong>Unit Price:</strong></td>
                <td class="value">{{ number_format($request->unit_price, 2) }} {{ $request->currency }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Amount:</strong></td>
                <td class="value">{{ number_format($request->amount, 2) }} {{ $request->currency }}</td>
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
                <td class="label"><strong>Withholding:</strong></td>
                <td class="value">{{ number_format($request->with_holding, 2) ?? '0.00' }} {{ $request->currency }}
                </td>
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
