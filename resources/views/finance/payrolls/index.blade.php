@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">{{ Auth::user()->full_name }}'s Payroll Records</h1>
    
    <div class="text-center mb-3">
        <a href="{{ route('finance.payrolls.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Add Payroll
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered mt-3">
            <thead class="thead-dark">
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
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $payroll)
                    <tr class="align-middle" style="transition: background-color 0.3s;">
                        <td>{{ $payroll->employee->username }}</td>
                        <td>{{ $payroll->pay_period }}</td>
                        <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>${{ number_format($payroll->allowances ?? 0, 2) }}</td>
                        <td>${{ number_format($payroll->deductions ?? 0, 2) }}</td>
                        <td>${{ number_format($payroll->net_salary, 2) }}</td>
                        <td>
                            <span class="badge {{ $payroll->status === 'approved' ? 'bg-success' : ($payroll->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($payroll->status) }}
                            </span>
                        </td>
                        <td>{{ $payroll->preparedBy->username ?? 'N/A' }}</td>
                        <td>{{ $payroll->approver ? $payroll->approver->full_name : 'N/A' }}</td>
                        <td class="text-center">
                            <a href="{{ route('finance.payrolls.edit', $payroll->payroll_id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('finance.payrolls.destroy', $payroll->payroll_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payroll?');">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
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

<!-- Optional: Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<!-- Optional: Add custom CSS for additional effects -->
<style>
    .table-hover tr:hover {
        background-color: #f8f9fa; /* Light grey on hover */
        cursor: pointer; /* Pointer cursor on hover */
    }
</style>
@endsection