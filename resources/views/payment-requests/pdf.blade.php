<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher — {{ $request->reference_no }}</title>
    <style>
        @media print {
            @page { size: A4; margin: 8mm; }
        }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 6mm;
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
            margin-bottom: 5mm;
            border-bottom: 1.5px solid #1e293b;
            padding-bottom: 8px;
        }
        header img {
            max-width: 80px;
            max-height: 70px;
            margin-right: 12px;
        }
        .company-info {
            text-align: right;
            font-size: 11px;
            color: #475569;
            flex: 1;
            margin-left: 20px;
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
        .payment-voucher-title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            margin: 8px 0 12px;
            letter-spacing: 0.05em;
            color: #0f172a;
            text-transform: uppercase;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .details-table,
        .details-table th,
        .details-table td {
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
            width: 35%;
            background: #f8fafc;
            color: #334155;
        }
        .details-table td.value {
            width: 65%;
            color: #0f172a;
            font-weight: 500;
        }
        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            font-size: 11px;
            gap: 20px;
        }
        .bottom-section > div {
            flex: 1;
            padding: 8px 10px;
            border: 1px dashed #cbd5e1;
            border-radius: 5px;
        }
        .bottom-section p { margin: 3px 0; }
        footer {
            text-align: center;
            margin-top: 10mm;
            font-style: italic;
            font-size: 10.5px;
            color: #64748b;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
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
                <p>Ref: {{ $request->reference_no }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="payment-voucher-title">Payment Voucher</h2>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="label"><strong>Full Name:</strong></td><td class="value">{{ $request->user->full_name }}</td></tr>
                <tr><td class="label"><strong>Request Date:</strong></td><td class="value">{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}</td></tr>
                <tr><td class="label"><strong>Department:</strong></td><td class="value">{{ $request->department }}</td></tr>
                <tr><td class="label"><strong>Item Name:</strong></td><td class="value">{{ $request->item_name }}</td></tr>
                <tr><td class="label"><strong>Item Type:</strong></td><td class="value">{{ $request->item_type }}</td></tr>
                <tr><td class="label"><strong>Description:</strong></td><td class="value">{{ $request->item_description }}</td></tr>
                <tr><td class="label"><strong>Quantity:</strong></td><td class="value">{{ $request->quantity }} {{ $request->unit }}</td></tr>
                <tr><td class="label"><strong>Unit Price:</strong></td><td class="value">{{ number_format($request->unit_price, 2) }} {{ $request->currency }}</td></tr>
                <tr><td class="label"><strong>Amount:</strong></td><td class="value">{{ number_format($request->amount, 2) }} {{ $request->currency }}</td></tr>
                <tr><td class="label"><strong>VAT:</strong></td><td class="value">{{ number_format($request->vat, 2) ?? '0.00' }} {{ $request->currency }}</td></tr>
                <tr><td class="label"><strong>Total:</strong></td><td class="value">{{ number_format($request->total, 2) }} {{ $request->currency }}</td></tr>
                <tr><td class="label"><strong>Withholding:</strong></td><td class="value">{{ number_format($request->with_holding, 2) ?? '0.00' }} {{ $request->currency }}</td></tr>
                <tr><td class="label"><strong>Net Payment:</strong></td><td class="value">{{ number_format($request->net_payment, 2) }} {{ $request->currency }}</td></tr>
                <tr><td class="label"><strong>Cost Center:</strong></td><td class="value">{{ $request->cost_center }}</td></tr>
                <tr><td class="label"><strong>Account Code:</strong></td><td class="value">{{ $request->account_code }}</td></tr>
                <tr><td class="label"><strong>Status:</strong></td><td class="value">{{ ucfirst($request->status) }}</td></tr>
                <tr><td class="label"><strong>Created At:</strong></td><td class="value">{{ $request->created_at->format('Y-m-d H:i:s') }}</td></tr>
                <tr><td class="label"><strong>Updated At:</strong></td><td class="value">{{ $request->updated_at->format('Y-m-d H:i:s') }}</td></tr>
            </tbody>
        </table>

        <div class="bottom-section">
            <div>
                <p><strong>Finance Comments:</strong> {{ $request->finance_comment ?? 'N/A' }}</p>
                <p><strong>Checked By:</strong> {{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p><strong>Approved By:</strong> {{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</p>
                <p><strong>Manager Comments:</strong> {{ $request->manager_comment ?? 'N/A' }}</p>
            </div>
        </div>

        <footer>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>
</html>