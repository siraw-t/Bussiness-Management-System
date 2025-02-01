<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Report PDF</title>
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
    <h1>Weekly Report</h1>

    <div class="form-section">
        <div class="form-row">
            <span class="form-label">Full Name:</span>
            <span class="form-value">{{ Auth::user()->full_name }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Week:</span>
            <span class="form-value">{{ $report->week }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Last Week's Plan:</span>
            @php
                $lastWeekPlan = \App\Models\EmployeePlan::where('employee_id', $report->employee_id)
                    ->where('week', $report->week) // Fetch plan for the same week
                    ->orderBy('week', 'desc')
                    ->first();
            @endphp
            <span class="form-value">{{ $lastWeekPlan ? $lastWeekPlan->plan : 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Report:</span>
            <span class="form-value">{{ $report->report }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Tasks In Progress:</span>
            <span class="form-value">{{ $report->tasks_in_progress ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Tasks Completed:</span>
            <span class="form-value">{{ $report->tasks_completed ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Self Assessment:</span>
            <span class="form-value">{{ $report->self_assessment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Status:</span>
            <span class="form-value">{{ ucfirst($report->status) }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Reference Number:</span>
            <span class="form-value">{{ $report->reference_number }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Approved By:</span>
            <span
                class="form-value">{{ optional(\App\Models\User::find($report->approved_by))->full_name ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Manager Comments:</span>
            <span class="form-value">{{ $report->manager_comment ?? 'N/A' }}</span>
        </div>
        <div class="form-row">
            <span class="form-label">Submitted At:</span>
            <span class="form-value">{{ $report->submitted_at->format('Y-m-d H:i:s') }}</span>
            <span class="form-label">Created At:</span>
            <span class="form-value">{{ $report->created_at->format('Y-m-d H:i:s') }}</span>
            <span class="form-label">Updated At:</span>
            <span class="form-value">{{ $report->updated_at->format('Y-m-d H:i:s') }}</span>
        </div>
    </div>

    <footer>
        <p>Thank you for your report submission.</p>
        <p>Please retain this document for your records.</p>
    </footer>
</body>

</html>
