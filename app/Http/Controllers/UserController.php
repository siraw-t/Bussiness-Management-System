<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UserRegisteredNotification;


class UserController extends Controller
{
    public function index()
    {
        // Fetch users based on the admin's company
        $users = (Auth::user()->role === 'superadmin')
            ? User::all() // Superadmin can see all users
            : User::where('company_id', Auth::user()->company_id)->get(); // Admin sees only their company's users

        $totalUsers = User::where('company_id', Auth::user()->company_id)->count();

        return view('users.index', compact('users', 'totalUsers')); // Pass data to the view
    }

    public function create()
    {
        return view('users.create'); // Show the form for creating a new user
    }


    

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,employee,finance,manager,storekeeper',
            'company_id' => 'required|exists:companies,company_id',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $user = User::create([
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'company_id' => $request->company_id,
        ]);
    
        if ($request->hasFile('profile_image')) {
            $filePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $filePath;
            $user->save();
        }
    
        // Notify the user of successful registration and their company information
        $user->notify(new UserRegisteredNotification($user));
    
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }




    public function edit(User $user)
{
    // Allow users to edit their own information
    if (Auth::user()->user_id !== $user->user_id) {
        return redirect()->route('users.index')->with('error', 'Unauthorized access.');
    }
    return view('users.edit', compact('user')); // Show the form for editing a user
}

public function update(Request $request, User $user)
{
    $request->validate([
        'username' => 'required|string|max:255',
        'full_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:admin,employee,finance,manager,storekeeper',
        'company_id' => 'required|exists:companies,company_id',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user->username = $request->username;
    $user->full_name = $request->full_name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    if ($request->hasFile('profile_image')) {
        $filePath = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $filePath;
    }

    $user->role = $request->role;
    $user->company_id = $request->company_id;
    $user->save();

    // Redirect based on user role
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
        case 'employee':
            return redirect()->route('employee.dashboard')->with('success', 'User updated successfully.');
        case 'finance':
            return redirect()->route('finance.dashboard')->with('success', 'User updated successfully.');
        case 'manager':
            return redirect()->route('manager.dashboard')->with('success', 'User updated successfully.');
        case 'storekeeper':
            return redirect()->route('storekeeper.dashboard')->with('success', 'User updated successfully.');
        default:
            return redirect()->route('users.index')->with('success', 'User updated successfully.'); // Fallback
    }
}

    public function destroy(User $user)
    {
        $user->delete(); // Delete the user
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function uploadProfileImage(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You need to log in first.');
    }

    $request->validate([
        'profile_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = Auth::user(); // Get the currently authenticated user

    if ($request->hasFile('profile_image')) {
        // Handle file upload
        $file = $request->file('profile_image');
        $filePath = $file->store('profile_images', 'public'); // Store in 'storage/app/public/profile_images'

        // Update the user's profile image
        $user->profile_image = $filePath;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Profile image uploaded successfully!');
    }

    return redirect()->back()->with('error', 'Failed to upload profile image.');
}
    
}