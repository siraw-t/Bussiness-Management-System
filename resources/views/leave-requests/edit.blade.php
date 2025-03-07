@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Edit Leave Request</h1>

        <form action="{{ route('leave-requests.update', $leaveRequest) }}" method="POST" enctype="multipart/form-data"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $leaveRequest->start_date }}"
                    required>
            </div>

            <div class="form-group mb-3">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $leaveRequest->end_date }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="leave_type">Leave Type</label>
                <select name="leave_type" class="form-control" required>
                    <option value="annual_leave" {{ $leaveRequest->leave_type === 'annual_leave' ? 'selected' : '' }}>Annual
                        Leave</option>
                    <option value="bereavement_leave"
                        {{ $leaveRequest->leave_type === 'bereavement_leave' ? 'selected' : '' }}>Bereavement Leave</option>
                    <option value="maternity_leave" {{ $leaveRequest->leave_type === 'maternity_leave' ? 'selected' : '' }}>
                        Maternity Leave</option>
                    <option value="sick_leave" {{ $leaveRequest->leave_type === 'sick_leave' ? 'selected' : '' }}>Sick Leave
                    </option>
                    <option value="unpaid_leave" {{ $leaveRequest->leave_type === 'unpaid_leave' ? 'selected' : '' }}>Unpaid
                        Leave</option>
                    <option value="parental_leave" {{ $leaveRequest->leave_type === 'parental_leave' ? 'selected' : '' }}>
                        Parental Leave</option>
                    <option value="other" {{ $leaveRequest->leave_type === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="other_reason">If Other, Please Specify</label>
                <input type="text" name="other_reason" class="form-control" value="{{ $leaveRequest->other_reason }}">
            </div>

            <div class="form-group mb-3">
                <label for="employee_signature">Upload New Signature</label>
                <input type="file" name="employee_signature" id="employee_signature" class="form-control">
                @if ($leaveRequest->employee_signature)
                    <img src="{{ asset('storage/' . $leaveRequest->employee_signature) }}" alt="Signature" class="mt-3"
                        style="width: 200px;">
                @endif
            </div>

            <div class="form-group mb-3">
                <label for="attachment">Attach New File</label>
                <input type="file" name="attachment" class="form-control">
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">Update</button>
        </form>
    </div>
@endsection
