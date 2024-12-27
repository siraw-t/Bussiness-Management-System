<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'company_id' => 'required|exists:companies,company_id', // Adjust as necessary
        ]);

        User::create([
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'company_id' => $request->company_id,
        ]);

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
    // Allow users to update their own information
    if (Auth::user()->user_id !== $user->user_id) {
        return redirect()->route('users.index')->with('error', 'Unauthorized access.');
    }

    $request->validate([
        'username' => 'required|string|max:255',
        'full_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:admin,employee,finance,manager,storekeeper',
        'company_id' => 'required|exists:companies,company_id',
    ]);

    $user->username = $request->username;
    $user->full_name = $request->full_name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // Check if the role can be edited by the user
    if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
        $user->role = $request->role;
    }

    $user->company_id = $request->company_id;
    $user->save();

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}

    public function destroy(User $user)
    {
        $user->delete(); // Delete the user
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function uploadAvatar(Request $request)
{
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('avatar')) {
        // Generate a unique filename for the avatar
        $filename = time() . '.' . $request->file('avatar')->getClientOriginalExtension();

        // Move the file to the public/avatars directory
        $request->file('avatar')->move(public_path('avatars'), $filename);

        // Set the avatar URL (you can customize this path)
        $avatarUrl = '/avatars/' . $filename;

        // Store the avatar URL in the session (or a similar temporary storage)
        session(['avatar_url' => $avatarUrl]);
    }

    return back()->with('success', 'Avatar uploaded successfully!');
}
    
}
