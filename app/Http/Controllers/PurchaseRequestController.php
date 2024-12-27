<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::where('user_id', Auth::id())->get();
        return view('purchase-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('purchase-requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'estimated_cost' => 'required|numeric',
            'comment' => 'nullable|string', // Validate comment
        ]);

        // Get the currently logged-in user
        $employee = Auth::user();

        // Retrieve the manager associated with the employee's company
        $manager = User::where('company_id', $employee->company_id)
                       ->where('role', 'manager') // Ensure we get the manager
                       ->first();

        // Check if a manager exists for the company
        if (!$manager) {
            return redirect()->route('purchase-requests.index')->with('error', 'No manager found for your company.');
        }

        // Create the purchase request
        PurchaseRequest::create([
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'estimated_cost' => $request->estimated_cost,
            'status' => 'pending', // Default status
            'comment' => $request->comment,
            'approved_by' => $manager->user_id, // Set approved_by to manager's ID
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request created successfully.');
    }

    public function edit(PurchaseRequest $purchaseRequest)
    {
        return view('purchase-requests.edit', compact('purchaseRequest'));
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'estimated_cost' => 'required|numeric',
            'status' => 'nullable|string', // Validate status
            'comment' => 'nullable|string', // Validate comment
        ]);

        $purchaseRequest->update($request->only([
            'item_name', 
            'quantity', 
            'estimated_cost', 
            'status', 
            'comment',
            'approved_by'
        ]));

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request updated successfully.');
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->delete();
        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request deleted successfully.');
    }

    public function approve(PurchaseRequest $purchaseRequest)
    {
        // Ensure the logged-in user is a manager
        $manager = Auth::user();
        if ($manager->role !== 'manager') {
            return redirect()->route('purchase-requests.index')->with('error', 'You are not authorized to approve purchase requests.');
        }

        // Update the purchase request status and approved_by field
        $purchaseRequest->update([
            'status' => 'approved',
            'approved_by' => $manager->user_id,
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request approved successfully.');
    }
}