<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Requisition Details</title>
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

        footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h1>Stock Requisition Details</h1>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Item Name:</span>
            <span class="form-value">{{ $requisition->item_name_with_description }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Quantity Requested:</span>
            <span class="form-value">{{ $requisition->quantity_requested }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Quantity Approved:</span>
            <span class="form-value">{{ $requisition->quantity_approved ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Status:</span>
            <span class="form-value">{{ ucfirst($requisition->status) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Requested By:</span>
            <span class="form-value">{{ optional($requisition->user)->full_name ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Approved By:</span>
            <span
                class="form-value">{{ optional(\App\Models\User::find($requisition->approved_by))->full_name ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Manager Comments:</span>
            <span class="form-value">{{ $requisition->manager_comment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Employee Comments:</span>
            <span class="form-value">{{ $requisition->employee_comment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Created At:</span>
            <span class="form-value">{{ $requisition->created_at->format('Y-m-d H:i:s') }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Updated At:</span>
            <span class="form-value">{{ $requisition->updated_at->format('Y-m-d H:i:s') }}</span>
        </div>
    </div>

    <footer>
        <p>Thank you for reviewing the stock requisition.</p>
        <p>Please retain this document for your records.</p>
    </footer>
</body>

</html>
