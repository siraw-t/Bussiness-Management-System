<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request PDF</title>
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

        .leave-request-title {
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

        .signature img {
            max-width: 150px;
            display: block;
            margin-top: 10px;
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
        <header>
            <img src="{{ public_path('storage/' . Auth::user()->company->logo) }}" alt="Company Logo">
            <div class="company-info">
                <p>{{ $company->city }}, {{ $company->country }}</p>
                <p>Date: {{ date('Y-m-d') }}</p>
                <p>Reference Number: {{ $request->reference_number }}</p>
            </div>
            <h1 class="company-name">{{ $company->company_name }}</h1>

        </header>

        <h2 class="leave-request-title">Leave Request</h2>

        <table class="details-table">
            <tr>
                <td style="width:50%;">
                    <table class="inner-table">
                        <tr>
                            <td class="label"><strong>Full Name:</strong></td>
                            <td class="value">{{ Auth::user()->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Company Country:</strong></td>
                            <td class="value">{{ $company->country }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Start Date:</strong></td>
                            <td class="value">{{ $request->start_date }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>End Date:</strong></td>
                            <td class="value">{{ $request->end_date }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Leave Type:</strong></td>
                            <td class="value">{{ ucfirst(str_replace('_', ' ', $request->leave_type)) }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table class="inner-table">
                        @if ($request->leave_type === 'other')
                            <tr>
                                <td class="label"><strong>Other Reason:</strong></td>
                                <td class="value">{{ $request->other_reason ?? 'N/A' }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="label"><strong>Uploaded File:</strong></td>
                            <td class="value">{{ $request->attachment ? 'Attached' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Status:</strong></td>
                            <td class="value">{{ ucfirst($request->status) }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Created At:</strong></td>
                            <td class="value">{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Updated At:</strong></td>
                            <td class="value">{{ $request->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="label"><strong>Remaining Annual Leave Days:</strong></td>
                            <td class="value">{{ $remainingLeaveDays }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="bottom-section">
            <div class="bottom-left">
                <p><strong>Employee Signature:</strong></p>
                <div class="signature">
                    @if ($request->employee_signature)
                        <img src="{{ public_path('storage/' . $request->employee_signature) }}" alt="Signature">
                    @else
                        <p>N/A</p>
                    @endif
                </div>
            </div>
            <div class="bottom-right">
                <p><strong>Manager Comments:</strong> {{ $request->manager_comments ?? 'N/A' }}</p>
                <p><strong>Approved By:</strong>
                    {{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</p>
            </div>
        </div>

        <footer>
            <p>Thank you for your request.</p>
            <p>Please retain this document for your records.</p>
        </footer>
    </div>
</body>

</html>
