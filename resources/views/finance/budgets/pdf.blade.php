<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            background-color: #f9f9f9;
            /* Light background color */
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            /* Blue border for header */
            padding-bottom: 10px;
            color: #007bff;
            /* Header color */
        }

        .form-section {
            margin-bottom: 20px;
            padding: 20px;
            border: 2px solid #007bff;
            /* Blue border for sections */
            border-radius: 8px;
            background-color: #ffffff;
            /* White background for sections */
        }

        .form-row {
            display: flex;
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: bold;
            color: #333;
            /* Darker color for labels */
            width: 200px;
            /* Fixed width for labels */
        }

        .form-value {
            border-bottom: 1px solid #ccc;
            /* Underline for value fields */
            padding: 5px;
            flex: 1;
            /* Takes remaining space */
        }

        .signature-section {
            margin-top: 30px;
            border-top: 2px solid #007bff;
            /* Line above signature */
            padding-top: 10px;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
            /* Italic style for footer */
        }
    </style>
</head>

<body>
    <h1>Budget Record</h1>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Budget ID:</span>
            <span class="form-value">{{ $budget->budget_id }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Forecast Period:</span>
            <span class="form-value">{{ ucfirst($budget->forecast_period) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Budget Description:</span>
            <span class="form-value">{{ $budget->budget_description }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Budget Item:</span>
            <span class="form-value">{{ $budget->budget_item }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Budget Code:</span>
            <span class="form-value">{{ $budget->budget_code }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Department:</span>
            <span class="form-value">{{ $budget->department }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Budget Source:</span>
            <span class="form-value">{{ $budget->budget_source }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Quantity with Unit:</span>
            <span class="form-value">{{ $budget->quantity_with_unit }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Unit Cost:</span>
            <span class="form-value">${{ number_format($budget->unit_cost, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Total Budget:</span>
            <span class="form-value">${{ number_format($budget->total_budget, 2) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Status:</span>
            <span class="form-value">{{ ucfirst($budget->status) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Created By:</span>
            <span class="form-value">{{ $budget->creator ? $budget->creator->full_name : 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Approved By:</span>
            <span class="form-value">{{ $budget->approver ? $budget->approver->full_name : 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Manager Comments:</span>
            <span class="form-value">{{ $budget->manager_comment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Created At:</span>
            <span class="form-value">{{ $budget->created_at->format('Y-m-d H:i:s') }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Updated At:</span>
            <span class="form-value">{{ $budget->updated_at->format('Y-m-d H:i:s') }}</span>
        </div>
    </div>

    <footer>
        <p>Thank you for reviewing this budget record.</p>
        <p>Please retain this document for your records.</p>
    </footer>
</body>

</html>
