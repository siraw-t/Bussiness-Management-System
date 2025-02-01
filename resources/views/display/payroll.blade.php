@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center text-info mb-4">{{ Auth::user()->company->company_name }} Payroll Records</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Job Title</th>
                        <th>Pay Period</th>
                        <th>Basic Salary</th>
                        <th>Transport Allowance</th>
                        <th>Taxable Transport Allowance</th>
                        <th>Position Allowance</th>
                        <th>Total Taxable Earnings</th>
                        <th>Income Tax</th>
                        <th>Pension Fund 7%</th>
                        <th>Pension Fund 11%</th>
                        <th>Salary Advance</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Manager Comments</th>
                        <th>Prepared By</th>
                        <th>Approved By</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $userCompanyId = auth()->user()->company_id; // Get the user's company ID

                        // Filter payroll records for users belonging to the same company
                        $filteredPayrolls = $payrolls->filter(function ($payroll) use ($userCompanyId) {
                            return $payroll->employee->company_id == $userCompanyId; // Ensure the employee is in the same company
                        });
                    @endphp

                    @if ($filteredPayrolls->isEmpty())
                        <tr>
                            <td colspan="17" class="text-center">No payroll records found.</td>
                        </tr>
                    @else
                        @foreach ($filteredPayrolls as $payroll)
                            <tr class="align-middle" style="transition: background-color 0.3s;">
                                <td>{{ $payroll->employee->full_name }}</td>
                                <td>{{ $payroll->job_title }}</td>
                                <td>{{ $payroll->pay_period }}</td>
                                <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                                <td>${{ number_format($payroll->transport_allowance ?? 0, 2) }}</td>
                                <td>${{ number_format($payroll->taxable_transport_allowance ?? 0, 2) }}</td>
                                <td>${{ number_format($payroll->position_allowance ?? 0, 2) }}</td>
                                <td>${{ number_format($payroll->total_taxable_earnings, 2) }}</td>
                                <td>${{ number_format($payroll->income_tax, 2) }}</td>
                                <td>${{ number_format($payroll->pension_fund_7, 2) }}</td>
                                <td>${{ number_format($payroll->pension_fund_11, 2) }}</td>
                                <td>${{ number_format($payroll->salary_advance ?? 0, 2) }}</td>
                                <td>${{ number_format($payroll->net_payment, 2) }}</td>
                                <td>
                                    <span
                                        class="badge {{ $payroll->status === 'approved' ? 'bg-success' : ($payroll->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($payroll->status) }}
                                    </span>
                                </td>
                                <td>{{ $payroll->manager_comment ?? 'N/A' }}</td>
                                <td>{{ $payroll->preparedBy ? $payroll->preparedBy->full_name : 'N/A' }}</td>
                                <td>{{ optional(\App\Models\User::find($payroll->approved_by))->full_name ?? 'N/A' }}</td>
                                <td>{{ $payroll->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $payroll->updated_at->format('Y-m-d H:i:s') }}</td>
                                <td><a href="{{ route('finance.payrolls.downloadPdf', $payroll) }}"
                                        class="btn btn-info">Download
                                        PDF</a></td>
                            </tr>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Optional: Add Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Custom CSS for hover effects -->
    <style>
        .table-hover tr:hover {
            background-color: #f8f9fa;
            /* Light grey on hover */
            cursor: pointer;
            /* Pointer cursor on hover */
        }
    </style>
@endsection
