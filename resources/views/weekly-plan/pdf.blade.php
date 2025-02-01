<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Plan PDF</title>
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
    <h1>Employee Plan Form</h1>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Full Name:</span>
            <span class="form-value">{{ Auth::user()->full_name }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Week:</span>
            <span class="form-value">{{ $plan->week }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Plan:</span>
            <span class="form-value">{{ $plan->plan }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Urgent Issues:</span>
            <span class="form-value">{{ $plan->urgent_issues ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Status:</span>
            <span class="form-value">{{ ucfirst($plan->status) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Reference Number:</span>
            <span class="form-value">{{ $plan->reference_number }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Approved By:</span>
            <span
                class="form-value">{{ optional(\App\Models\User::find($plan->approved_by))->full_name ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Manager Comments:</span>
            <span class="form-value">{{ $plan->manager_comment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Submitted At:</span>
            <span class="form-value">{{ $plan->submitted_at->format('Y-m-d H:i:s') }}</span>
            <span class="form-label">Created At:</span>
            <span class="form-value">{{ $plan->created_at->format('Y-m-d H:i:s') }}</span>
            <span class="form-label">Updated At:</span>
            <span class="form-value">{{ $plan->updated_at->format('Y-m-d H:i:s') }}</span>
        </div>
    </div>

    <footer>
        <p>Thank you for your plan submission.</p>
        <p>Please retain this document for your records.</p>
    </footer>
</body>

</html>
