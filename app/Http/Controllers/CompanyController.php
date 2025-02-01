<?php


namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    // Display the list of companies with their associated admins
    public function index()
    {
        $companies = Company::with('admin')->get();
        return view('companies.index', compact('companies'));
    }

    // Show the form for creating a new company and admin
    public function create()
    {
        $companies = Company::all();
        $roles = ['admin', 'employee', 'manager', 'finance', 'storekeeper'];

        return view('companies.create', compact('companies', 'roles'));
    }

    // Store a new company and admin
    public function store(Request $request)
{
    // Validate company creation
    $request->validate([
        'company_name' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'contact_email' => 'required|email|max:255',
        'contact_phone' => 'required|string|max:15',
        'admin_username' => 'required|string|max:255|unique:users,username',
        'admin_full_name' => 'required|string|max:255',
        'admin_email' => 'required|email|max:255|unique:users,email',
        'admin_password' => 'required|string|min:8|confirmed',
        'role' => 'required|string|in:admin,employee,manager,finance,storekeeper',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate logo
    ]);

    // Begin transaction
    DB::beginTransaction();

    try {
        // Create the Company
        $companyData = [
            'company_name' => $request->company_name,
            'country' => $request->country,
            'city' => $request->city,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'created_by' => Auth::id(), // Track who created the company
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $companyData['logo'] = $logoPath;
        }

        $company = Company::create($companyData);

        // Create the admin user
        $admin = User::create([
            'username' => $request->admin_username,
            'full_name' => $request->admin_full_name,
            'email' => $request->admin_email,
            'password' => bcrypt($request->admin_password),
            'role' => $request->role,
            'company_id' => $company->company_id, // Associate with the company
        ]);

        // Update the company with the admin's user ID
        $company->company_admin = $admin->user_id; // Set the company admin ID
        $company->save();

        // Commit transaction
        DB::commit();

        return redirect()->route('companies.index')->with('success', 'Company and Admin created successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
}

    // Show the form for editing the specified company
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    // Update the specified company
    public function update(Request $request, Company $company)
{
    // Validate the incoming request
    $request->validate([
        'company_name' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'contact_email' => 'required|email|max:255',
        'contact_phone' => 'required|string|max:15',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate logo
    ]);

    // Update the company details
    $companyData = $request->only(['company_name', 'country', 'city', 'contact_email', 'contact_phone']);

    // Handle logo upload
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('logos', 'public');
        $companyData['logo'] = $logoPath;
    }

    $company->update($companyData);

    return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
}

    // Remove the specified company
    public function destroy(Company $company)
    {
        // Optional: Handle deletion of associated users if necessary
        // User::where('company_id', $company->id)->delete();

        // Delete the company
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
