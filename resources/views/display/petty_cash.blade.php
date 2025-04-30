@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <h2 class="text-center text-info mb-4">{{ Auth::check() ? Auth::user()->company->company_name : 'Company' }} Petty
            Cash Requests</h2>
        @if (Auth::check() && Auth::user()->role === 'manager')
            <a href="{{ route('summaries.manager') }}" class="btn btn-primary mt-3">View Manager Summary</a>
        @endif

        @if (Auth::check() && Auth::user()->role === 'finance')
            <a href="{{ route('summaries.finance') }}" class="btn btn-success mt-3">View Finance Summary</a>
        @endif


        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Reference Number</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Amount Requested</th>
                        <th>Amount Requested in Words</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Finance Comment</th>
                        <th>Amount አፕሩፕድ</th>
                        <th>Amount Approved in Words</th>
                        <th>Manager Comment</th>
                        <th>Checked By</th>
                        <th>Approved By</th>
                        <th>Company Country</th>
                        <th>Attachements</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Check if the user is authenticated
                        $companyId = Auth::check() ? auth()->user()->company_id : null;

                        // Filter petty cash requests to show only those from the user's company
                        $filteredCashRequests = $pettyCashRequests->filter(function ($request) use ($companyId) {
                            return $request->user && $request->user->company_id == $companyId;
                        });
                    @endphp

                    @if ($filteredCashRequests->isEmpty())
                        <tr>
                            <td colspan="14" class="text-center">No petty cash requests found.</td>
                        </tr>
                    @else
                        @foreach ($filteredCashRequests as $request)
                            <tr class="align-middle" style="transition: background-color 0.3s;">
                                <td>{{ $request->reference_number }}</td>
                                <td>{{ $request->title }}</td>
                                <td>{{ $request->description ?? 'N/A' }}</td>
                                <td>{{ number_format($request->amount_requested, 2) }} {{ $request->currency }}</td>
                                <td>{{ $request->amount_requested_in_words ?? 'N/A' }}</td>
                                <td>{{ $request->currency }}</td>
                                <td>
                                    <span
                                        class="badge 
                                        {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->finance_comment ?? 'N/A' }}</td>
                                <td>{{ number_format($request->amount_approved, 2) }}</td>
                                <td>{{ $request->amount_approved_in_words ?? 'N/A' }}</td>
                                <td>{{ $request->manager_comment ?? 'N/A' }}</td>
                                <td>{{ optional(\App\Models\User::find($request->checked_by))->full_name ?? 'N/A' }}</td>
                                <td>{{ optional(\App\Models\User::find($request->approved_by))->full_name ?? 'N/A' }}</td>
                                <td>{{ $request->company_country }}</td>
                                <td>
                                    @php
                                        // Decode attachments if stored as JSON string
                                        $attachments = is_string($request->attachments)
                                            ? json_decode($request->attachments, true)
                                            : $request->attachments;
                                    @endphp

                                    @if (!empty($attachments))
                                        <ul>
                                            @foreach ($attachments as $attachment)
                                                <li>
                                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank">
                                                        {{ basename($attachment) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('petty_cash_requests.downloadPdf', $request) }}"
                                        class="btn btn-info">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    @if (Auth::check() && Auth::user()->role === 'finance' && $request->status === 'approved')
                                        <form
                                            action="{{ route('finance.petty_cash.attach', ['petty_cash_request_id' => $request->petty_cash_request_id]) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <!-- File Input -->
                                            <input type="file" name="attachments[]" multiple>
                                            <!-- Submit Button -->
                                            <button type="submit">Submit</button>
                                        </form>
                                    @endif



                                </td>
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
            cursor: pointer;
        }
    </style>
@endsection
