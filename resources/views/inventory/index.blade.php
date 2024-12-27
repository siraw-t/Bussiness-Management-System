@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Inventory List</h1>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary">Add New Item</a>
    <table class="table mt-3">
        <thead>
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
            <tr>
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
                </td>                <td>
                    <a href="{{ route('inventory.edit', $inventory->inventory_id) }}" class="btn btn-secondary">Edit</a>
                    <form action="{{ route('inventory.destroy', $inventory->inventory_id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection