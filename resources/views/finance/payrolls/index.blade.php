@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Payroll Records</h1>
    <a href="{{ route('finance.payrolls.create') }}" class="btn btn-primary mb-3">Add Payroll</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Pay Period</th>
                    <th>Basic Salary</th>
                    <th>Allowances</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th>Prepared By</th>
                    <th>Approved By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->employee->username }}</td>
                        <td>{{ $payroll->pay_period }}</td>
                        <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>${{ number_format($payroll->allowances ?? 0, 2) }}</td>
                        <td>${{ number_format($payroll->deductions ?? 0, 2) }}</td>
                        <td>${{ number_format($payroll->net_salary, 2) }}</td>
                        <td>{{ ucfirst($payroll->status) }}</td>
                        <td>{{ $payroll->preparedBy->username ?? 'N/A' }}</td>
                        <td>{{ $payroll->approver ? $payroll->approver->full_name : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('finance.payrolls.edit', $payroll->payroll_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('finance.payrolls.destroy', $payroll->payroll_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No payroll records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection