<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::where('managed_by', Auth::id())->get();
        return view('inventory.index', compact('inventories'));
    }

    public function create()
    {
        $users = User::all(); // Get all users for dropdowns
        return view('inventory.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'stock_in_date' => 'nullable|date',
            'stock_out_date' => 'nullable|date',
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

        // Retrieve the manager for approval
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager')
                       ->first();

        Inventory::create([
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'stock_in_date' => $request->stock_in_date,
            'stock_out_date' => $request->stock_out_date,
            'managed_by' => Auth::id(),
            'approved_by' => $manager ? $manager->user_id : null, // Set approved_by if manager exists
        ]);

        return redirect()->route('inventory.index')->with('success', 'Inventory item created successfully.');
    }

    public function edit(Inventory $inventory)
    {
        $users = User::all(); // Get all users for dropdowns
        return view('inventory.edit', compact('inventory', 'users'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $inventory->update($request->only([
            'item_name',
            'quantity',
            'description',
            'stock_in_date',
            'stock_out_date',
            'approved_by',
        ]));

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
    }
}