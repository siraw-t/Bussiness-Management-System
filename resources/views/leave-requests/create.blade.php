@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Create Leave Request</h1>

        <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data"
            class="shadow p-4 rounded bg-light" style="max-width: 600px; margin: auto;">
            @csrf

            <div class="form-group mb-3">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="leave_type">Leave Type</label>
                <select name="leave_type" class="form-control" required>
                    <option value="annual_leave">Annual Leave</option>
                    <option value="bereavement_leave">Bereavement Leave</option>
                    <option value="maternity_leave">Maternity Leave</option>
                    <option value="sick_leave">Sick Leave</option>
                    <option value="unpaid_leave">Unpaid Leave</option>
                    <option value="parental_leave">Parental Leave</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="other_reason">If Other, Please Specify</label>
                <input type="text" name="other_reason" class="form-control"
                    placeholder="Enter reason if 'Other' selected">
            </div>

            <div class="form-group mb-3">
                <label for="employee_signature">Upload Your Signature</label>
                <input type="file" name="employee_signature" id="employee_signature" class="form-control"
                    accept="image/*">
                <img id="signature-preview" class="mt-3" style="display: none; max-width: 100%; height: auto;">
            </div>


            <div class="form-group mb-3">
                <label for="attachment">Attach File</label>
                <input type="file" name="attachment" class="form-control">
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">Submit</button>
        </form>
    </div>

    <script>
        document.getElementById('employee_signature').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('signature-preview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
