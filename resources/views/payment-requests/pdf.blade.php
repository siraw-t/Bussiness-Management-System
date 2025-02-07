<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher</title>
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
    <h1>Payment Voucher</h1>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Full Name:</span>
            <span class="form-value">{{ $request->user->full_name }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Reference Number:</span>
            <span class="form-value">{{ $request->ref_no }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Request Date:</span>
            <span
                class="form-value">{{ $request->request_date ? $request->request_date->format('Y-m-d') : 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">From:</span>
            <span class="form-value">{{ $request->from }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Description:</span>
            <span class="form-value">{{ $request->description }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Unit & Quantity:</span>
            <span class="form-value">{{ $request->unit_and_qty }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Amount:</span>
            <span class="form-value">${{ number_format($request->amount, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">VAT:</span>
            <span class="form-value">${{ number_format($request->vat, 2) ?? '0.00' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Total:</span>
            <span class="form-value">${{ number_format($request->total, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Net Payment:</span>
            <span class="form-value">${{ number_format($request->net_payment, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Withholding:</span>
            <span class="form-value">${{ number_format($request->with_holding, 2) ?? '0.00' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Cost Center:</span>
            <span class="form-value">{{ $request->cost_center }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Account Code:</span>
            <span class="form-value">{{ $request->account_code }}</span>
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
            <span class="form-label">Reviewed By:</span>
            <span
                class="form-value">{{ optional(\App\Models\User::find($request->reviewed_by))->full_name ?? 'N/A' }}</span>
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
        <p>Thank you for your payment request.</p>
        <p>Please retain this document for your records.</p>
    </footer>
</body>

</html>
