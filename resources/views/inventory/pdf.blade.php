<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Details — {{ $inventory->reference_number }}</title>
    <style>
        @media print {
            @page { size: A4; margin: 10mm; }
        }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 5mm;
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
            margin-bottom: 6mm;
            border-bottom: 1.5px solid #1e293b;
            padding-bottom: 8px;
        }
        header img {
            max-width: 90px;
            max-height: 75px;
            margin-right: 12px;
        }
        .company-info {
            text-align: right;
            font-size: 11px;
            color: #475569;
            flex: 1;
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
        .inventory-title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            margin: 8px 0 14px;
            letter-spacing: 0.05em;
            color: #0f172a;
            text-transform: uppercase;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .details-table, .details-table th, .details-table td {
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
            width: 40%;
            background: #f8fafc;
            color: #334155;
        }
        .details-table td.value {
            width: 60%;
            color: #0f172a;
            font-weight: 500;
        }
        .bottom-section {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-top: 14px;
            padding: 12px 14px;
            border: 1px dashed #cbd5e1;
            border-radius: 5px;
        }
        .bottom-section > div { flex: 1; }
        .bottom-section p { margin: 4px 0; font-size: 11px; }
        .bottom-section strong { color: #334155; }
        footer {
            text-align: center;
            margin-top: 10mm;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-style: italic;
            font-size: 10.5px;
            color: #64748b;
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
                <p>Ref: {{ $inventory->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="inventory-title">Inventory Details</h2>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="label"><strong>Item Name:</strong></td><td class="value">{{ $inventory->item_name }}</td></tr>
                <tr><td class="label"><strong>Description:</strong></td><td class="value">{{ $inventory->description ?? 'N/A' }}</td></tr>
                <tr><td class="label"><strong>Tag Number:</strong></td><td class="value">{{ $inventory->tag_number }}</td></tr>
                <tr><td class="label"><strong>Item Type:</strong></td><td class="value">{{ ucfirst($inventory->item_type) }}</td></tr>
                <tr><td class="label"><strong>Quantity:</strong></td><td class="value">{{ $inventory->quantity }} {{ $inventory->unit }}</td></tr>
                <tr><td class="label"><strong>Unit Price:</strong></td><td class="value">{{ number_format($inventory->unit_price, 2) }} {{ $inventory->currency }}</td></tr>
                <tr><td class="label"><strong>Total Price:</strong></td><td class="value">{{ number_format($inventory->total_price, 2) }} {{ $inventory->currency }}</td></tr>
                <tr><td class="label"><strong>Stock In Date:</strong></td><td class="value">{{ $inventory->stock_in_date ? $inventory->stock_in_date->format('Y-m-d') : 'N/A' }}</td></tr>
                <tr><td class="label"><strong>Issued Quantity:</strong></td><td class="value">{{ $inventory->issued_quantity ?? 'N/A' }} {{ $inventory->issued_unit }}</td></tr>
                <tr><td class="label"><strong>Stock Out Date:</strong></td><td class="value">{{ $inventory->stock_out_date ? $inventory->stock_out_date->format('Y-m-d') : 'N/A' }}</td></tr>
                <tr><td class="label"><strong>Issued Department:</strong></td><td class="value">{{ $inventory->issued_department ?? 'N/A' }}</td></tr>
                <tr><td class="label"><strong>Balance Quantity:</strong></td><td class="value">{{ $inventory->balance_quantity ?? 'N/A' }} {{ $inventory->unit }}</td></tr>
                <tr><td class="label"><strong>Balance Total Price:</strong></td><td class="value">{{ number_format($inventory->balance_total_price ?? 0, 2) }} {{ $inventory->currency }}</td></tr>
                <tr><td class="label"><strong>Created At:</strong></td><td class="value">{{ $inventory->created_at->format('Y-m-d H:i:s') }}</td></tr>
                <tr><td class="label"><strong>Updated At:</strong></td><td class="value">{{ $inventory->updated_at->format('Y-m-d H:i:s') }}</td></tr>
            </tbody>
        </table>

        <div class="bottom-section">
            <div>
                <p><strong>Received By:</strong> {{ optional(\App\Models\User::find($inventory->received_by))->full_name ?? 'N/A' }}</p>
                <p><strong>Managed By:</strong> {{ optional(\App\Models\User::find($inventory->managed_by))->full_name ?? 'N/A' }}</p>
            </div>
        </div>

        <footer>
            <p>Thank you for managing your inventory.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>
</html>