<?php


namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\User;
use App\Models\Company;
use App\Models\StockRequisition;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StockFulfilledNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;
use PDF;

class InventoryController extends Controller
{
    public function index()
    {   
        $inventories = Inventory::with('stockRequisition')->get();
        $users = User::all();
        $notifications = Auth::user()->notifications;

        return view('inventory.index', compact('inventories', 'users', 'notifications'));
    }

    public function create()
    {

         // Fetch approved payment items for dropdown
         $approvedPaymentItems = PaymentRequest::where('status', 'approved')
         ->get(['item_name']);
     

        return view('inventory.create', compact('approvedPaymentItems'));
    }

    public function store(Request $request)
{
    $request->validate([
        'item_name' => 'required|string|max:255',
        'tag_number' => 'nullable|string|max:255',
        'item_type' => 'required|in:fixed asset,consumable',
        'quantity' => 'required|integer|min:0',
        'unit_price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'stock_in_date' => 'nullable|date',
        'currency' => 'required|string',
        'unit' => 'required|string',
    ]);

    $employee = Auth::user();

    // Retrieve the manager associated with the employee's company
    $manager = User::where('company_id', $employee->company_id)
                   ->where('role', 'manager') // Ensure we get the manager
                   ->first();

    // Get the user's company country
    $user = Auth::user();
    $companyCountry = $user->company ? $user->company->country : null;

    $company = Company::find($employee->company_id);

         // Function to create an acronym from the company name
    function createAcronym($companyName)
    {
        $words = explode(' ', $companyName);
        $acronym = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $acronym .= strtoupper($word[0]); // Take the first letter of each word
            }
        }

        return $acronym;
    }

    // Generate the acronym for the company name
    $companyAcronym = createAcronym($company->company_name);

    // Format the reference number
    $requestName = 'IVM_Req'; // Using the table name
    $currentYear = date('Y');

    // Get the last reference number for the same request name and year to increment
    $lastRequest = Inventory::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
        ->orderBy('created_at', 'desc')
        ->first();

    // Determine the next sequence number
    $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;

    // Format the reference number
    $reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);


    Inventory::create([
        'item_name' => $request->item_name,
        'tag_number' => $request->tag_number,
        'item_type' => $request->item_type,
        'reference_number' => $reference_number, // Use the generated reference number
        'quantity' => $request->quantity,
        'unit_price' => $request->unit_price,
        'total_price' => $request->quantity * $request->unit_price,
        'description' => $request->description,
        'stock_in_date' => $request->stock_in_date,
        'managed_by' => Auth::id(),
        'currency' => $request->currency,
        'unit' => $request->unit,
        'company_country' => $companyCountry, // Set the company country
        'approved_by' => $manager->user_id,

    ]);

    return redirect()->route('inventory.index')->with('success', 'Inventory item created successfully.');
}

    public function edit(Inventory $inventory)
    {
        $approvedPaymentItems = PaymentRequest::where('status', 'approved')
    ->get(['item_name']);


        return view('inventory.edit', compact('inventory','approvedPaymentItems'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'tag_number' => 'nullable|string|max:255',
            'item_type' => 'required|in:fixed asset,consumable',
            // 'reference_number' => 'required|string|unique:inventory,reference_number,' . $inventory->inventory_id,
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_in_date' => 'nullable|date',
            'currency' => 'required|string',
            'unit' => 'required|string',
        ]);

        $inventory->update($request->only([
            'item_name', 'tag_number', 'item_type',
            'quantity', 'unit_price', 'total_price', 'description', 'stock_in_date',
            'currency', 'unit', 
        ]));

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
    }

    public function stockOut(Request $request, Inventory $inventory)
    {
        $request->validate([
            'issued_quantity' => 'required|integer|min:1|max:' . $inventory->quantity,
            'issued_unit_price' => 'required|numeric|min:0',
            'issued_currency' => 'required|string',
            'issued_unit' => 'required|string',
            'issued_department' => 'nullable|string|max:255',
            'received_by' => 'nullable|exists:users,user_id',
        ]);

        // Get the latest stock requisition for this inventory item
        $requisition = StockRequisition::where('item_name', $inventory->item_name)
                                       ->where('status', 'approved')
                                       ->first();

        if ($requisition && $requisition->employee_comment === 'received') {
            // Proceed with issuing stock
            $inventory->issued_quantity = $request->issued_quantity;
            $inventory->issued_unit_price = $request->issued_unit_price;
            $inventory->issued_total_price = $request->issued_quantity * $request->issued_unit_price;
            $inventory->issued_department = $request->issued_department;
            $inventory->received_by = $request->received_by;
            $inventory->stock_out_date = now();
            $inventory->issued_currency = $request->issued_currency;
            $inventory->issued_unit = $request->issued_unit;

            // Decrease the quantity
            $inventory->quantity -= $request->issued_quantity;
            $inventory->balance_quantity = $inventory->quantity;
            $inventory->balance_total_price = $inventory->balance_quantity * $inventory->unit_price;
            $inventory->total_price = $inventory->balance_total_price;

            // Save the inventory
            $inventory->save();

           // Notify the requester
        $employee = User::find($request->received_by);
        if ($employee) {
            Notification::send($employee, new StockFulfilledNotification([
                'id' => $inventory->id, // Include the inventory ID
                'message' => 'Your stock request for ' . $inventory->item_name . ' has been fulfilled.'
            ]));
        }

             // Notify storekeeper if stock is low
        if ($inventory->quantity <= 5) {
            $storekeeper = User::where('role', 'storekeeper')->first();
            if ($storekeeper) {
                $storekeeper->notify(new LowStockNotification([
                    'id' => $inventory->id, // Include the inventory ID
                    'message' => 'Stock for ' . $inventory->item_name . ' is low. Only ' . $inventory->quantity . ' left.'
                ]));
            }
        }

            return redirect()->route('inventory.index')->with('success', 'Stock-out recorded successfully.');
        } else {
            return redirect()->route('inventory.index')->with('error', 'Cannot issue stock. Employee comment must be "received" or no approved requisition found.');
        }
    }

    public function downloadPdf($id)
    {
        $inventory = Inventory::findOrFail($id);
        $company = Auth::user()->company;

        $pdf = PDF::loadView('inventory.pdf', compact('inventory', 'company'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('inventory_' . $inventory->inventory_id . '.pdf');
    }

    public function combinedSummary()
{
    // Retrieve inventory items
    $inventories = Inventory::select(
        'item_name', 
        'item_type', 
        'quantity', 
        'unit_price', 
        'balance_quantity', 
        'balance_total_price', 
        'stock_in_date', 
        'stock_out_date', 
        'currency', 
        'unit'
    )->get();

    // Prepare combined data
    $combinedData = [];
    $totalValue = 0; // Initialize total value

    // Loop through inventories and calculate total prices
    foreach ($inventories as $inventory) {
        $totalPrice = $inventory->quantity * $inventory->unit_price; // Calculate total price for this item
        $totalValue += $totalPrice; // Add to overall total value

        // Add inventory item to combined data
        $combinedData[$inventory->item_name] = [
            'item_name' => $inventory->item_name,
            'item_type' => $inventory->item_type,
            'quantity' => $inventory->quantity,
            'unit_price' => $inventory->unit_price,
            'total_price' => $totalPrice,
            'stock_in_date' => $inventory->stock_in_date,
            'stock_out_date' => $inventory->stock_out_date,
            'currency' => $inventory->currency,
            'unit' => $inventory->unit,
            'balance_quantity' => $inventory->balance_quantity,
            'balance_total_price' => $inventory->balance_total_price,
            'quantity_requested' => 0, // Initialize for requisition
            // 'unit_requested' => null, // Initialize for requisition
            'quantity_approved' => 0, // Initialize for requisition
        ];
    }

    // Retrieve stock requisitions
    $stockRequisitions = StockRequisition::select(
        'item_name', 
        'quantity_requested', 
        'quantity_approved'
    )->get();

    // Add stock requisition data to combined summary
    foreach ($stockRequisitions as $requisition) {
        if (isset($combinedData[$requisition->item_name])) {
            // If the item already exists, update the quantities
            $combinedData[$requisition->item_name]['quantity_requested'] += $requisition->quantity_requested;
            $combinedData[$requisition->item_name]['quantity_approved'] += $requisition->quantity_approved;
        } else {
            // If the item does not exist in inventory, create a new entry
            $combinedData[$requisition->item_name] = [
                'item_name' => $requisition->item_name,
                'item_type' => null,
                'quantity' => 0, // No inventory quantity
                'unit_price' => null,
                'total_price' => null,
                'stock_in_date' => null,
                'stock_out_date' => null,
                'currency' => null,
                'unit' => null,
                'balance_quantity' => null,
                'balance_total_price' => null,
                'quantity_requested' => $requisition->quantity_requested,
                'quantity_approved' => $requisition->quantity_approved,
            ];
        }
    }

    // Reset combinedData to a simple array
    $combinedData = array_values($combinedData);

    return view('inventory.combined_summary', compact('combinedData', 'totalValue'));
}
}