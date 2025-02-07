<?php

namespace App\Http\Controllers;

use App\Models\StockRequisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use App\Models\Inventory; 
use PDF;

class StockRequisitionController extends Controller
{
    public function index()
    {

    // Fetch only the requisitions created by the logged-in user
    $userId = Auth::id();
    $requisitions = StockRequisition::where('user_id', $userId)->get();

       return view('stock_requisitions.index', compact('requisitions'));
    }

    public function create()
    {
        $inventories = Inventory::all();

        return view('stock_requisitions.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name_with_description' => 'required|string|max:255',
            'quantity_requested' => 'required|integer|min:1',
        ]);

         // Get the currently logged-in user
         $employee = Auth::user();

         // Retrieve the manager associated with the employee's company
         $manager = User::where('company_id', $employee->company_id)
                        ->where('role', 'manager') // Ensure we get the manager
                        ->first();
 
       

        StockRequisition::create([
            'item_name_with_description' => $request->item_name_with_description,
            'quantity_requested' => $request->quantity_requested,
            'requested_by' => Auth::id(),
            'user_id' => Auth::id(),
            'approved_by' => $manager->user_id,
        ]);

        return redirect()->route('stock_requisitions.index')->with('success', 'Stock requisition submitted successfully.');
    }

    public function edit(StockRequisition $stockRequisition)
    {
        $inventories = Inventory::all();

        return view('stock_requisitions.edit', compact('stockRequisition', 'inventories'));
    }

    public function update(Request $request, StockRequisition $stockRequisition)
    {
        $request->validate([
            'item_name_with_description' => 'required|string|max:255',
            'quantity_requested' => 'required|integer|min:1',
        ]);

        $stockRequisition->update($request->only([
            'item_name_with_description', 'quantity_requested'
        ]));

        return redirect()->route('stock_requisitions.index')->with('success', 'Stock requisition updated successfully.');
    }

    public function destroy(StockRequisition $stockRequisition)
    {
        $stockRequisition->delete();
        return redirect()->route('stock_requisitions.index')->with('success', 'Stock requisition deleted successfully.');
    }

    public function showPendingRequests()
    {
        $userId = Auth::id(); // Get the currently logged-in user's ID
 
        // Fetch pending stock requisition requests for the authenticated user
        $pendingStockRequisitionRequests = StockRequisition::where('status', 'pending')
            ->where('user_id', $userId)
            ->with('user') // Load the user relationship if needed
            ->get();
 
        // Pass the fetched requests to the view
        return view('display.pending', compact('pendingStockRequisitionRequests'));
    }

    public function showStockRequisitions()
    {
        $userId = Auth::id(); // Get the currently logged-in user's ID

        // Fetch stock requisitions for the authenticated user
        $stockRequisitions = StockRequisition::where('user_id', $userId)->with('user')->get();

        // Pass the variable to the view
        return view('display.stock-requisition', compact('stockRequisitions'));
    }

    public function downloadPdf($id)
    {
        $requisition = StockRequisition::findOrFail($id);
        
        // Generate PDF with A4 size and portrait orientation
        $pdf = PDF::loadView('stock_requisitions.pdf', compact('requisition'))
                    ->setPaper('A4', 'portrait');

        return $pdf->download('stock_requisition_' . $requisition->id . '.pdf');
    }

   

public function addEmployeeComment(Request $request, $stock_requisition_id)
{
    $request->validate([
        'employee_comment' => 'required|string|max:255',
    ]);

    $stockRequisition = StockRequisition::findOrFail($stock_requisition_id);
    $stockRequisition->employee_comment = $request->employee_comment; // Update employee's comment
    $stockRequisition->save();

    return redirect()->back()->with('success', 'Employee comment added successfully.');
}
}