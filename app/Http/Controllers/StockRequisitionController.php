<?php


namespace App\Http\Controllers;

use App\Models\StockRequisition;
use App\Models\User;
use App\Models\Company;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class StockRequisitionController extends Controller
{
    public function index()
    {
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
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity_requested' => 'required|integer|min:1',
            'unit_requested' => 'nullable|string',
        ]);

   // Get the currently logged-in user
   $employee = Auth::user();

   // Retrieve the manager associated with the employee's company
   $manager = User::where('company_id', $employee->company_id)
                  ->where('role', 'manager') // Ensure we get the manager
                  ->first();
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
             $requestName = 'Stock_Req'; // Using the table name
             $currentYear = date('Y');
         
             // Get the last reference number for the same request name and year to increment
             $lastRequest = StockRequisition::where('reference_number', 'LIKE', "{$companyAcronym}/{$requestName}/{$currentYear}/%")
                 ->orderBy('created_at', 'desc')
                 ->first();
         
             // Determine the next sequence number
             $sequenceNumber = $lastRequest ? intval(substr($lastRequest->reference_number, strrpos($lastRequest->reference_number, '/') + 1)) + 1 : 1;
         
             // Format the reference number
             $reference_number = "{$companyAcronym}/{$requestName}/{$currentYear}/" . str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);
         
         
                  
        StockRequisition::create([
            'item_name' => $request->item_name,
            'description' => $request->description,
            'quantity_requested' => $request->quantity_requested,
            'unit_requested' => $request->unit_requested,
            'requested_by' => $employee->user_id,
            'user_id' => $employee->user_id,
            'reference_number' => $reference_number,
            'company_country' => $employee->company->country ?? 'N/A',
            'approved_by' => $manager ? $manager->user_id : null,
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
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity_requested' => 'required|integer|min:1',
            'unit_requested' => 'nullable|string',
        ]);

        $stockRequisition->update($request->only([
            'item_name', 'description', 'quantity_requested', 'unit_requested'
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
        $company = Auth::user()->company;
        $pdf = PDF::loadView('stock_requisitions.pdf', compact('requisition','company'))->setPaper('A4', 'portrait');
        return $pdf->download('stock_requisition_' . $requisition->stock_requisition_id . '.pdf');
    }

    public function addEmployeeComment(Request $request, $stock_requisition_id)
    {
        $request->validate([
            'employee_comment' => 'required|string|max:255',
        ]);

        $stockRequisition = StockRequisition::findOrFail($stock_requisition_id);
        $stockRequisition->employee_comment = $request->employee_comment;
        $stockRequisition->save();

        return redirect()->back()->with('success', 'Employee comment added successfully.');
    }
}