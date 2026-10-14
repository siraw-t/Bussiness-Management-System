<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Report — {{ $report->reference_number }}</title>
    <style>
        @page { margin: 24px; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            color: #1e293b;
            font-size: 12px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 28px 32px;
            border: 2px solid #1e293b;
            border-radius: 10px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 14px;
        }
        header img {
            max-width: 100px;
            max-height: 80px;
            margin-right: 20px;
        }
        .company-info {
            text-align: right;
            font-size: 11px;
            color: #475569;
            flex: 1;
            line-height: 1.5;
        }
        .company-info p { margin: 2px 0; }
        .company-name {
            text-align: center;
            font-size: 17px;
            color: #4f46e5;
            margin: 10px 0 0;
            width: 100%;
            font-weight: 700;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            margin: 18px 0;
            letter-spacing: 0.04em;
            color: #0f172a;
            text-transform: uppercase;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .details-table > tbody > tr > td {
            vertical-align: top;
            padding: 6px 12px 6px 0;
        }
        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }
        .inner-table td {
            padding: 5px 0;
            font-size: 11.5px;
            vertical-align: top;
        }
        .inner-table .label {
            font-weight: bold;
            width: 42%;
            color: #334155;
        }
        .inner-table .value {
            width: 58%;
            color: #0f172a;
        }
        .status-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-pending  { background: #fef3c7; color: #92400e; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px dashed #cbd5e1;
        }
        .bottom-left,
        .bottom-right {
            width: 48%;
        }
        .bottom-left p,
        .bottom-right p { margin: 4px 0; }
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            font-style: italic;
            color: #64748b;
            font-size: 10.5px;
        }
        footer p { margin: 3px 0; }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <img src="{{ public_path('storage/' . Auth::user()->company->logo) }}" alt="Company Logo">
            <div class="company-info">
                <p>{{ $company->city }}, {{ $company->country }}</p>
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>Reference Number: {{ $report->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="report-title">Weekly Report</h2>

        <table class="details-table">
            <tr>
                <td style="width:50%;">
                    <table class="inner-table">
                        <tr>
                            <td class="label">Full Name:</td>
                            <td class="value">{{ $report->employee->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="label">Week:</td>
                            <td class="value">{{ $report->week }}</td>
                        </tr>
                        <tr>
                            <td class="label">Last Week's Plan:</td>
                            <td class="value">
                                @if ($lastWeekPlan)
                                    {{ $lastWeekPlan->plan }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Report:</td>
                            <td class="value">{{ $report->report }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tasks In Progress:</td>
                            <td class="value">{{ $report->tasks_in_progress ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table class="inner-table">
                        <tr>
                            <td class="label">Tasks Completed:</td>
                            <td class="value">{{ $report->tasks_completed ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Self Assessment:</td>
                            <td class="value">{{ $report->self_assessment ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Status:</td>
                            <td class="value">
                                <span class="status-pill status-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Submitted At:</td>
                            <td class="value">{{ $report->submitted_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Created At:</td>
                            <td class="value">{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Updated At:</td>
                            <td class="value">{{ $report->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="bottom-section">
            <div class="bottom-left">
                <p><strong>Manager Comments:</strong> {{ $report->manager_comment ?? 'N/A' }}</p>
            </div>
            <div class="bottom-right">
                <p><strong>Approved By:</strong>
                    {{ optional(\App\Models\User::find($report->approved_by))->full_name ?? 'N/A' }}</p>
            </div>
        </div>

        <footer>
            <p>Thank you for your report submission.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>
</html>