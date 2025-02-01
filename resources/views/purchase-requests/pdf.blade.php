<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Request PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
            color: #007bff;
        }

        .form-section {
            margin-bottom: 20px;
            padding: 20px;
            border: 2px solid #007bff;
            border-radius: 8px;
            background-color: #ffffff;
        }

        .form-row {
            display: flex;
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: bold;
            color: #333;
            width: 200px;
        }

        .form-value {
            border-bottom: 1px solid #ccc;
            padding: 5px;
            flex: 1;
        }

        .signature-section {
            margin-top: 30px;
            border-top: 2px solid #007bff;
            padding-top: 10px;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h1>Purchase Request Details</h1>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Full Name:</span>
            <span class="form-value">{{ $request->user->full_name }}
            </span>
        </div>
        <div class="form-row">
            <span class="form-label">Reference Number:</span>
            <span class="form-value">{{ $request->reference_number }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Item Info:</span>
            <span class="form-value">{{ $request->item_info }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Quantity:</span>
            <span class="form-value">{{ $request->quantity_with_unit }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Price:</span>
            <span class="form-value">{{ $request->price_with_currency }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Status:</span>
            <span class="form-value">{{ ucfirst($request->status) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Approved By:</span>
            <span
                class="form-value">{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Manager Comments:</span>
            <span class="form-value">{{ $request->manager_comment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Finance Comments:</span>
            <span class="form-value">{{ $request->finance_comment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Checked By:</span>
            <span
                class="form-value">{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Created At:</span>
            <span class="form-value">{{ $request->created_at->format('Y-m-d H:i:s') }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Updated At:</span>
            <span class="form-value">{{ $request->updated_at->format('Y-m-d H:i:s') }}</span>
        </div>

    </div>

    <footer>
        <p>Thank you for your purchase request.</p>
        <p>Please retain this document for your records.</p>
    </footer>
</body>

</html>
