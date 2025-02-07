<?php


namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StockOutNotification; // Ensure this is imported
use Illuminate\Support\Facades\Notification;
use App\Models\StockRequisition;


class InventoryController extends Controller
{
    public function index()
    {   
      
        $inventories = Inventory::all();
        $inventories = Inventory::with('stockRequisition')->get();

        return view('inventory.index', compact('inventories'));
    }

    public function create()
    {

        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'tag_number' => 'nullable|string|max:255',
            'item_type' => 'required|in:fixed asset,consumable',
            'reference_number' => 'required|string|unique:inventory',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_in_date' => 'nullable|date',
            'managed_by' => 'nullable|integer',
        ]);

        Inventory::create([
            'item_name' => $request->item_name,
            'tag_number' => $request->tag_number,
            'item_type' => $request->item_type,
            'reference_number' => $request->reference_number,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $request->quantity * $request->unit_price,
            'description' => $request->description,
            'stock_in_date' => $request->stock_in_date,
            'managed_by' => Auth::id(),
        ]);

        return redirect()->route('inventory.index')->with('success', 'Inventory item created successfully.');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'tag_number' => 'nullable|string|max:255',
            'item_type' => 'required|in:fixed asset,consumable',
            'reference_number' => 'required|string|unique:inventory,reference_number,' . $inventory->inventory_id,
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_in_date' => 'nullable|date',
        ]);

        $inventory->update($request->only([
            'item_name', 'tag_number', 'item_type', 'reference_number',
            'quantity', 'unit_price', 'total_price', 'description', 'stock_in_date'
        ]));

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
    }

//     public function stockOut(Request $request, Inventory $inventory)
// {
//     $request->validate([
//         'issued_quantity' => 'required|integer|min:1|max:' . $inventory->quantity,
//         'issued_unit_price' => 'required|numeric|min:0',
//         'issued_department' => 'nullable|string|max:255',
//         'received_by' => 'nullable|exists:users,user_id' // Ensure this references a valid user
//     ]);

//     // Check if the employee comment is 'received'
//     $employeeComment = $request->input('employee_comment', 'not_received'); // Default to 'not_received'

//     // Update issued quantities
//     $inventory->issued_quantity = $request->issued_quantity;
//     $inventory->issued_unit_price = $request->issued_unit_price;
//     $inventory->issued_total_price = $request->issued_quantity * $request->issued_unit_price;
//     $inventory->issued_department = $request->issued_department; // If used
//     $inventory->received_by = $request->received_by; // If used
//     $inventory->stock_out_date = now();

//     // Only decrease the quantity if the employee comment is 'received'
//     if ($employeeComment === 'received') {
//         $inventory->quantity -= $request->issued_quantity; // Reduce the quantity
//     }

//     // Always update balance
//     $inventory->balance_quantity = $inventory->quantity; // Update balance
//     $inventory->balance_total_price = $inventory->balance_quantity * $inventory->unit_price; // Update total value

//     $inventory->save();

//     return redirect()->route('inventory.index')->with('success', 'Stock-out recorded successfully.');
// }

public function stockOut(Request $request, Inventory $inventory)
{
    // Validate the request
    $request->validate([
        'issued_quantity' => 'required|integer|min:1|max:' . $inventory->quantity,
        'issued_unit_price' => 'required|numeric|min:0',
        'issued_department' => 'nullable|string|max:255',
        'received_by' => 'nullable|exists:users,user_id', // Ensure this references a valid user
        'employee_comment' => 'required|string|in:received,not_received', // Add this validation
    ]);

    // Update issued quantities
    $inventory->issued_quantity = $request->issued_quantity;
    $inventory->issued_unit_price = $request->issued_unit_price;
    $inventory->issued_total_price = $request->issued_quantity * $request->issued_unit_price;
    $inventory->issued_department = $request->issued_department; // If used
    $inventory->received_by = $request->received_by; // If used
    $inventory->stock_out_date = now();

    // Update balance and quantity only if the comment is 'received'
    if ($request->employee_comment === 'received') {
        $inventory->quantity -= $request->issued_quantity; // Reduce the quantity
        $inventory->balance_quantity = $inventory->quantity; // Update balance
        $inventory->balance_total_price = $inventory->balance_quantity * $inventory->unit_price; // Update total value
        $inventory->total_price = $inventory->balance_total_price;

        // // Send notification to the user who received the stock
        // $requester = User::find($request->received_by);
        // if ($requester) {
        //     Notification::send($requester, new StockOutNotification($inventory));
        // }
    }

    // Save the inventory
    $inventory->save();

    return redirect()->route('inventory.index')->with('success', 'Stock-out recorded successfully.');
}

}