<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'password'   => 'required|string|min:8',
            'role'       => 'required|in:admin,counter',
            'counter_id' => $request->role === 'counter' ? 'required|exists:counters,id' : 'nullable|exists:counters,id',
        ]);

        // Create a new user
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'role'       => $request->role,
            'counter_id' => $request->counter_id,
        ]);

        return back()->with('success', "User {$user->name} created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate the request
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'       => 'required|in:admin,counter',
            'counter_id' => $request->role === 'counter' ? 'required|exists:counters,id' : 'nullable|exists:counters,id',

        ]);

        // Update user information
        $user->update($request->only('name', 'email', 'role', 'counter_id'));

        // Redirect back with success message
        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Check if the user is not the currently authenticated user
        if ($user->id === request()->user()->id) {
            // Redirect back with error message
            return back()->withErrors(['You cannot delete your own account.']);
        }

        // Delete the user
        $user->delete();

        // Redirect back with success message
        return back()->with('success', 'User deleted successfully.');
    }

    // add change-password function
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        $user->update(['password' => bcrypt($request->new_password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}
