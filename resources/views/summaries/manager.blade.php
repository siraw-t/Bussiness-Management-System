@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center my-4" style="color: #007bff; font-size: 2.5rem;">Approved Requests</h3>

        <div class="card mb-4 border-info shadow-lg animate__animated animate__fadeIn">
            <div class="card-header bg-info text-white">
                <h4>Payment Requests ({{ $paymentRequests->count() }})</h4>
            </div>
            <div class="card-body">
                @foreach ($paymentRequests as $request)
                    <div class="request card mb-2 p-3 border-light shadow-sm">
                        <h5 class="text-primary">{{ $request->item_name }}</h5>
                        <p>
                            <strong>Full Name:</strong> {{ $request->user->full_name }}<br>
                            <strong>Net Payment:</strong> {{ $request->currency }}
                            {{ number_format($request->net_payment, 2) }}<br>
                            <strong>Requested Date:</strong> {{ $request->request_date->format('d/m/Y') }}<br>
                            <strong>Item Description:</strong> {{ $request->item_description }}<br>
                            <strong>Manager Comment:</strong> {{ $request->manager_comment ?? 'N/A' }}<br>
                        </p>
                        <a href="{{ route('display.payment') }}" class="btn btn-success btn-sm">Review</a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card mb-4 border-warning shadow-lg animate__animated animate__fadeIn">
            <div class="card-header bg-warning text-dark">
                <h4>Petty Cash Requests ({{ $pettyCashRequests->count() }})</h4>
            </div>
            <div class="card-body">
                @foreach ($pettyCashRequests as $request)
                    <div class="request card mb-2 p-3 border-light shadow-sm">
                        <h5 class="text-warning">{{ $request->title }}</h5>
                        <p>
                            <strong>Requested Amount:</strong> {{ $request->currency }}
                            {{ number_format($request->amount_requested, 2) }}<br>
                            <strong>Approved Amount:</strong> {{ $request->currency }}
                            {{ number_format($request->amount_approved, 2) }}<br>
                            <strong>Full Name:</strong> {{ $request->user->full_name }}<br>
                            <strong>Requested Date:</strong> {{ $request->created_at->format('d/m/Y') }}<br>
                            <strong>Attachments:</strong>
                            @if ($request->attachments)
                                <a href="{{ Storage::url($request->attachments) }}" target="_blank">View Attachments</a>
                            @else
                                None
                            @endif
                        </p>
                        <a href="{{ route('display.petty_cash') }}" class="btn btn-success btn-sm">Review</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
