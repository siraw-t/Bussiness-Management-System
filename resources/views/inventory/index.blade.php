@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-success mb-4"><i class="fas fa-list-alt"></i> {{ Auth::user()->full_name }}'s Inventory List</h1>

    @if(session('success'))
        <div class="alert alert-success fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="text-center mb-4">
        <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus-circle"></i> Add New Item
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Stock In Date</th>
                    <th>Stock Out Date</th>
                    <th>Approved By</th>
                    <th>Managed By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inventory)
                <tr class="align-middle" style="transition: background-color 0.3s;">
                    <td>{{ $inventory->item_name }}</td>
                    <td>{{ $inventory->quantity }}</td>
                    <td>{{ $inventory->description }}</td>
                    <td>{{ $inventory->stock_in_date }}</td>
                    <td>{{ $inventory->stock_out_date }}</td>
                    <td>
                        @if ($inventory->approved_by)
                            {{ optional(\App\Models\User::find($inventory->approved_by))->full_name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>               
                    <td>
                        @if ($inventory->managed_by)
                            {{ optional(\App\Models\User::find($inventory->managed_by))->full_name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>                
                    <td>
                        <a href="{{ route('inventory.edit', $inventory->inventory_id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('inventory.destroy', $inventory->inventory_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
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