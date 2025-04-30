@extends('layouts.app')

@section('styles')
    <style>
        /* --- General Enhancements --- */
        body {
            /* Subtle gradient background */
            background: linear-gradient(to bottom right, #f8f9fa, #e9ecef);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Cleaner font */
        }

        .container {
            /* Add some padding and shadow for a card-like effect */
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            /* Fade-in animation */
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- Heading --- */
        .main-heading {
            font-weight: 600;
            color: #0d6efd;
            /* Bootstrap primary blue */
            margin-bottom: 2rem;
            /* More space below heading */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            font-size: 2rem;
            /* Slightly larger */
        }

        /* --- Table Styling --- */
        .table {
            margin-bottom: 1.5rem;
            /* Space below table */
            font-size: 0.95rem;
            /* Adjust base font size */
            border: 1px solid #dee2e6;
            /* Ensure border consistency */
        }

        .table thead th {
            background-color: #0d6efd;
            /* Primary color background */
            color: #ffffff;
            /* White text */
            font-weight: 600;
            /* Bolder headers */
            text-transform: uppercase;
            /* Uppercase headers */
            letter-spacing: 0.5px;
            vertical-align: middle;
            /* Align text vertically */
            white-space: nowrap;
            /* Prevent header text wrapping */
            padding: 0.85rem 0.75rem;
            /* Adjust padding */
        }

        .table tbody tr {
            transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
            /* Smooth transitions */
        }

        .table-hover tbody tr:hover {
            background-color: #eef5ff;
            /* Lighter blue on hover */
            transform: scale(1.01);
            /* Slight scale effect on hover */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow on hover */
            cursor: pointer;
            /* Indicate interactivity */
        }

        /* Zebra striping */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
            /* Ensure even rows are white */
        }

        .table-striped tbody tr:hover {
            /* Ensure hover overrides striping */
            background-color: #eef5ff;
        }


        .table td {
            vertical-align: middle;
            /* Align cell content vertically */
            padding: 0.75rem;
            /* Consistent padding */
        }

        /* Align numeric data to the right */
        .table td:nth-child(3),
        /* Quantity */
        .table td:nth-child(4),
        /* Unit Price */
        .table td:nth-child(5),
        /* Total Price */
        .table td:nth-child(9),
        /* Qty Requested */
        .table td:nth-child(10),
        /* Qty Approved */
        .table td:nth-child(12),
        /* Balance Qty */
        .table td:nth-child(13)

        /* Balance Total Price */
            {
            text-align: right;
        }

        /* --- Total Value Display --- */
        .total-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: #198754;
            /* Bootstrap success green */
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            background-color: #e9f5ec;
            border-radius: 5px;
            display: inline-block;
            /* Make it wrap content */
            float: right;
            /* Align to right */
        }

        /* --- Button Enhancements --- */
        .btn-custom-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
            padding: 0.6rem 1.2rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 5px;
            transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, transform 0.15s ease-out, box-shadow 0.2s ease-in-out;
            margin-top: 1rem;
            /* Ensure spacing */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: inline-block;
            /* Needed if float is cleared */
        }

        .btn-custom-primary:hover {
            background-color: #0b5ed7;
            /* Darker blue on hover */
            border-color: #0a58ca;
            color: #ffffff;
            transform: translateY(-2px);
            /* Lift effect */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            /* Enhanced shadow */
        }

        .btn-custom-primary:focus,
        .btn-custom-primary:active {
            box-shadow: 0 0 0 0.25rem rgba(49, 132, 253, 0.5);
            /* Focus ring */
        }

        /* --- Responsive Table --- */
        .table-responsive {
            /* Optional: Add a subtle shadow or border to the scrollable container */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            /* Add space below responsive container */
        }

        /* --- Clearfix for floated elements --- */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* --- Mobile Specific Adjustments (Example) --- */
        @media (max-width: 768px) {
            .main-heading {
                font-size: 1.75rem;
                /* Smaller heading on mobile */
            }

            .container {
                padding: 1.5rem;
                /* Less padding on smaller screens */
            }

            .table {
                font-size: 0.85rem;
                /* Slightly smaller font in table for mobile */
            }

            .table thead th,
            .table td {
                padding: 0.6rem 0.5rem;
                /* Reduce padding */
            }

            .total-value {
                float: none;
                /* Stack total value below table on small screens */
                display: block;
                text-align: center;
                margin-top: 1.5rem;
                font-size: 1.1rem;
            }

            .back-button-container {
                /* Container to center button on mobile */
                text-align: center;
                margin-top: 1rem;
            }

            .btn-custom-primary {
                width: auto;
                /* Allow button to size naturally */
                display: inline-block;
                margin-top: 0;
                /* Reset margin as it's handled by container */
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .main-heading {
                font-size: 1.5rem;
            }

            .container {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <h1 class="text-center main-heading mb-4">Combined Inventory and Stock Requisition Summary</h1>

        {{-- Responsive Table Wrapper --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        {{-- Consider shorter headers for mobile if possible, or rely on horizontal scroll --}}
                        <th>Item Name</th>
                        <th>Type</th> {{-- Shortened --}}
                        <th>Qty</th> {{-- Shortened --}}
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Stock In</th> {{-- Shortened --}}
                        <th>Currency</th>
                        <th>Unit</th>
                        <th>Qty Req.</th> {{-- Shortened --}}
                        <th>Qty Appr.</th> {{-- Shortened --}}
                        <th>Stock Out</th> {{-- Shortened --}}
                        <th>Balance Qty</th>
                        <th>Balance Total</th> {{-- Shortened --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($combinedData as $data)
                        {{-- Use forelse for empty check --}}
                        <tr>
                            <td>{{ $data['item_name'] }}</td>
                            <td>{{ $data['item_type'] ?? '' }}</td>
                            <td>{{ $data['quantity'] ?? 0 }}</td>
                            <td>{{ isset($data['unit_price']) ? number_format($data['unit_price'], 2) : '' }}</td>
                            <td>{{ isset($data['total_price']) ? number_format($data['total_price'], 2) : '' }}</td>
                            <td>{{ $data['stock_in_date'] ? \Carbon\Carbon::parse($data['stock_in_date'])->format('Y-m-d') : '' }}
                            </td> {{-- Format date --}}
                            <td>{{ $data['currency'] ?? '' }}</td>
                            <td>{{ $data['unit'] ?? '' }}</td>
                            <td>{{ $data['quantity_requested'] ?? 0 }}</td>
                            <td>{{ $data['quantity_approved'] ?? 0 }}</td>
                            <td>{{ $data['stock_out_date'] ? \Carbon\Carbon::parse($data['stock_out_date'])->format('Y-m-d') : '' }}
                            </td> {{-- Format date --}}
                            <td>{{ $data['balance_quantity'] ?? '' }}</td>
                            <td>{{ isset($data['balance_total_price']) ? number_format($data['balance_total_price'], 2) : '' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted">No combined data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Container for Total and Button to handle layout and clearing floats --}}
        <div class="clearfix">
            @if (!empty($combinedData))
                <div class="total-value">
                    Total Value: {{ number_format($totalValue, 2) }}
                    {{ $combinedData[0]['currency'] ?? '' }}
                </div>
            @endif
        </div>

        <div class="back-button-container mt-4">
            <a href="{{ route('inventory.index') }}" class="btn btn-custom-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Inventory
            </a>
        </div>

    </div>
@endsection

@section('scripts')
    {{-- Optional: Add Font Awesome if you want icons --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script> --}}
@endsection
