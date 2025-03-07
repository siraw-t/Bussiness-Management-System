@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Payroll Records</h1>

        <div class="text-center mb-3">
            <a href="{{ route('finance.payrolls.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Add Payroll
            </a>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif --}}

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Pay Period</th>
                        <th>Job Title</th>
                        <th>Reference Number</th>
                        <th>Basic Salary</th>
                        <th>No of Days</th>
                        <th>Transport Allowance</th>
                        <th>Taxable Transport Allowance</th>
                        <th>Position Allowance</th>
                        <th>Total Taxable Earnings</th>
                        <th>Income Tax</th>
                        <th>Pension Fund 7%</th>
                        <th>Pension Fund 11%</th>
                        <th>Salary Advance</th>
                        <th>Net Payment</th>
                        <th>Prepared By</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Manager Comments</th>
                        <th>Company Country</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payrolls as $payroll)
                        <tr class="align-middle">
                            <td>{{ $payroll->employee->username }}</td>
                            <td>{{ $payroll->pay_period }}</td>
                            <td>{{ $payroll->job_title }}</td>
                            <td>{{ $payroll->reference_number }}</td>
                            <td>{{ number_format($payroll->basic_salary, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ $payroll->no_of_days }}</td>
                            <td>{{ number_format($payroll->transport_allowance, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ number_format($payroll->taxable_transport_allowance, 2) }} {{ $payroll->currency }}
                            </td>
                            <td>{{ number_format($payroll->position_allowance, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ number_format($payroll->total_taxable_earnings, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ number_format($payroll->income_tax, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ number_format($payroll->pension_fund_7, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ number_format($payroll->pension_fund_11, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ number_format($payroll->salary_advance, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ number_format($payroll->net_payment, 2) }} {{ $payroll->currency }}</td>
                            <td>{{ optional(\App\Models\User::find($payroll->prepared_by))->full_name ?? 'N/A' }}</td>
                            <td>
                                <span
                                    class="badge {{ $payroll->status === 'approved' ? 'bg-success' : ($payroll->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                            <td>{{ optional(\App\Models\User::find($payroll->approved_by))->full_name ?? 'N/A' }}</td>
                            <td>{{ $payroll->manager_comment ?? 'N/A' }}</td>
                            <td>{{ $payroll->company_country }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if ($payroll->status !== 'approved')
                                        <a href="{{ route('finance.payrolls.edit', $payroll) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('finance.payrolls.destroy', $payroll) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this payroll record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="btn btn-warning btn-sm disabled" title="Cannot edit approved payrolls">
                                            <i class="bi bi-pencil"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm disabled"
                                            title="Cannot delete approved payrolls">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                    @endif
                                    <a href="{{ route('finance.payrolls.downloadPdf', $payroll) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" class="text-center">No payroll records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .table-hover tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>
@endsection
