@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <h2 class="text-center text-info mb-4">{{ Auth::user()->company->company_name }} Payroll Records</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Pay Period</th>
                    <th>Basic Salary</th>
                    <th>Allowances</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th>Prepared By</th>
                    <th>Approved By</th>
                    <th>Created At</th>
                    <th>Updated At</th>
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

                @if($filteredPayrolls->isEmpty())
                    <tr>
                        <td colspan="10" class="text-center">No payroll records found.</td>
                    </tr>
                @else
                    @foreach($filteredPayrolls as $payroll)
                    <tr class="align-middle" style="transition: background-color 0.3s;">
                        <td>{{ $payroll->pay_period }}</td>
                        <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>${{ number_format($payroll->allowances, 2) }}</td>
                        <td>${{ number_format($payroll->deductions, 2) }}</td>
                        <td>${{ number_format($payroll->net_salary, 2) }}</td>
                        <td>
                            <span class="badge {{ $payroll->status === 'approved' ? 'bg-success' : ($payroll->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($payroll->status) }}
                            </span>
                        </td>
                        <td>{{ $payroll->preparedBy ? $payroll->preparedBy->full_name : 'N/A' }}</td>
                        <td>{{ $payroll->approver ? $payroll->approver->full_name : 'N/A' }}</td>
                        <td>{{ $payroll->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $payroll->updated_at->format('Y-m-d H:i:s') }}</td>
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
        background-color: #f8f9fa; /* Light grey on hover */
        cursor: pointer; /* Pointer cursor on hover */
    }
</style>
@endsection