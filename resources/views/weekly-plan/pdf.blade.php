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
            background-color: #ffffff;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 3px solid #000;
            border-radius: 10px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        header img {
            max-width: 100px;
            margin-right: 20px;
        }

        .company-info {
            text-align: right;
            /* Align text to the right */
            font-size: 14px;
            color: #555;
            flex: 1;
            /* Allow it to take remaining space */
        }

        .company-name {
            text-align: center;
            font-size: 18px;
            color: #007bff;
            margin: 10px 0;
            width: 100%;
        }

        .employee-plan-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table td {
            vertical-align: top;
            padding: 10px;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inner-table td {
            padding: 5px 0;
        }

        .inner-table .label {
            font-weight: bold;
            width: 40%;
        }

        .inner-table .value {
            width: 60%;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .bottom-left,
        .bottom-right {
            width: 48%;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
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
                <p>Reference Number: {{ $plan->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>

        </header>


        <h2 class="employee-plan-title">Employee Plan</h2>

        <!-- Main Details in Two Columns -->
        <table class="details-table">
            <tr>
                <!-- Left Column -->
                <td style="width:50%;">
                    <table class="inner-table">
                        <tr>
                            <td class="label"><strong>Full Name:</strong></td>
                            <td class="value">{{ Auth::user()->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Week:</strong></td>
                            <td class="value">{{ $plan->week }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Plan:</strong></td>
                            <td class="value">{{ $plan->plan }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Urgent Issues:</strong></td>
                            <td class="value">{{ $plan->urgent_issues ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
                <!-- Right Column -->
                <td style="width:50%;">
                    <table class="inner-table">
                        <tr>
                            <td class="label"><strong>Submitted At:</strong></td>
                            <td class="value">{{ $plan->submitted_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Created At:</strong></td>
                            <td class="value">{{ $plan->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Updated At:</strong></td>
                            <td class="value">{{ $plan->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <div class="bottom-left">
                <p><strong>Manager Comments:</strong> {{ $plan->manager_comment ?? 'N/A' }}</p>
            </div>
            <div class="bottom-right">
                <p><strong>Approved By:</strong>
                    {{ optional(\App\Models\User::find($plan->approved_by))->full_name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <p>Thank you for your plan submission.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
