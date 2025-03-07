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
            text-align: left;
            font-size: 14px;
            color: #555;
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
            <div class="company-info">
                <img src="{{ public_path('storage/' . Auth::user()->company->logo) }}" alt="Company Logo">
                <p>{{ $company->city }}, {{ $company->country }}</p>
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>Reference Number: {{ $report->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>
        </header>

        <h2 class="employee-plan-title">Weekly Report</h2>

        <!-- Main Details in Two Columns -->
        <table class="details-table">
            <tr>
                <!-- Left Column -->
                <td style="width:50%;">
                    <table class="inner-table">
                        <tr>
                            <td class="label"><strong>Full Name:</strong></td>
                            <td class="value">Siraw Finance</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Week:</strong></td>
                            <td class="value">2025-02-17</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Last Week's Plan:</strong></td>
                            <td class="value">Update all modules</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Report:</strong></td>
                            <td class="value">DF</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Tasks In Progress:</strong></td>
                            <td class="value">FFESWE</td>
                        </tr>
                    </table>
                </td>
                <!-- Right Column -->
                <td style="width:50%;">
                    <table class="inner-table">
                        <tr>
                            <td class="label"><strong>Tasks Completed:</strong></td>
                            <td class="value">GWRTGV</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Self Assessment:</strong></td>
                            <td class="value">VGWGTWZ</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Status:</strong></td>
                            <td class="value">Pending</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Submitted At:</strong></td>
                            <td class="value">2025-02-17 13:09:09</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Created At:</strong></td>
                            <td class="value">2025-02-17 13:09:09</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Updated At:</strong></td>
                            <td class="value">2025-02-17 13:09:09</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <div class="bottom-left">
                <p><strong>Manager Comments:</strong> N/A</p>
                <p><strong>Approved By:</strong> Siraw Tadesse</p>
            </div>

        </div>

        <!-- Footer -->
        <footer>
            <p>Thank you for your report submission.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
