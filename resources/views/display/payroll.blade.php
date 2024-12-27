{{-- @extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Payroll</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pay Period</th>
                    <th>Basic Salary</th>
                    <th>Allowances</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Status</th>
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
                        <td colspan="9" class="text-center">No payroll records found.</td>
                    </tr>
                @else
                    @foreach($filteredPayrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->pay_period }}</td>
                        <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>{{ number_format($payroll->allowances, 2) }}</td>
                        <td>{{ number_format($payroll->deductions, 2) }}</td>
                        <td>{{ number_format($payroll->net_salary, 2) }}</td>
                        <td>{{ $payroll->status }}</td>
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
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Payroll</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
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
                    <tr>
                        <td>{{ $payroll->pay_period }}</td>
                        <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>{{ number_format($payroll->allowances, 2) }}</td>
                        <td>{{ number_format($payroll->deductions, 2) }}</td>
                        <td>{{ number_format($payroll->net_salary, 2) }}</td>
                        <td>{{ ucfirst($payroll->status) }}</td>
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
@endsection