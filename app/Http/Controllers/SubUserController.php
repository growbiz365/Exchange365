<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class SubUserController extends Controller
{

    /**
     * Show the list of sub-users (belonging to the parent).
     */
    public function index(Request $request)
    {
        $title = 'Manage Sub Users';
        $parentUser = auth()->user();
        $isSuperAdmin = $parentUser->isSuperAdmin();
        $search = $request->input('search');

        $query = $isSuperAdmin
            ? User::query()->whereNotNull('parent_id')
            : $parentUser->subUsers();

        $users = $query
            ->with(['roles', 'parent'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('username', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('subusers.index', compact('users', 'title', 'isSuperAdmin'));
    }

    /**
     * Show the form to create a sub-user.
     */
    public function create()
    {
        $title = 'Create Sub User';
        $parentUser = auth()->user();

        // Get roles that the parent user can assign (including their own roles and created roles)
        $assignableRoles = $parentUser->getAssignableRoles();

        // Get businesses associated with the parent user
        $parentbusinesses = $parentUser->businesses;
        $assignedBusinesses = $parentbusinesses->pluck('id')->toArray(); // Add this line

        return view('subusers.create', compact('parentUser', 'assignableRoles', 'parentbusinesses', 'assignedBusinesses', 'title'));
    }


    /**
     * Store the sub-user.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'businesses' => 'nullable|array',  // Ensure Businesses are provided
            'businesses.*' => 'exists:businesses,id',  // Ensure each Businesses exists
            'roles' => 'nullable|array', // Ensure roles are passed
            'roles.*' => 'exists:roles,id', // Ensure each role exists
        ]);

        if ($validator->fails()) {
            return redirect()->route('subusers.create')->withErrors($validator)->withInput();
        }

        // Create new subuser
        $subUser = new User();
        $subUser->name = $request->name;
        $subUser->username = $request->username;
        $subUser->email = $request->email;
        $subUser->password = Hash::make($request->password);
        $subUser->parent_id = auth()->user()->id; // Assign the parent user ID
        $subUser->save();

        // Assign branches to the sub-user
        if ($request->has('businesses')) {
            $subUser->businesses()->sync($request->businesses);
        }

        // Assign roles to the sub-user if any
        if ($request->has('roles')) {
            $roles = array_map('intval', $request->roles); // Convert to integers
            $subUser->roles()->sync($roles); // Assign roles
        }

        return redirect()->route('subusers.index')->with('success', 'Sub-user created successfully');
    }


    /**
     * Edit a sub-user's details.
     */
    public function edit($id)
    {
        $subUser = User::findOrFail($id);
        $parentUser = auth()->user();

        if (!$this->canManageSubUser($subUser)) {
            return redirect()->route('subusers.index')->with('error', 'You can only edit your own sub-users.');
        }

        $assignableRoles = $parentUser->getAssignableRoles();
        $assignedRoles = $subUser->roles->pluck('id')->toArray();
        $assignedBusinesses = $subUser->businesses->pluck('id')->toArray();
        $businesses = $parentUser->isSuperAdmin()
            ? Business::orderBy('business_name')->get()
            : $parentUser->businesses;

        return view('subusers.edit', compact(
            'subUser',
            'parentUser',
            'assignableRoles',
            'assignedRoles',
            'assignedBusinesses',
            'businesses'
        ));
    }

    /**
     * Update a sub-user's details.
     */
    public function update(Request $request, $id)
    {
        $subUser = User::findOrFail($id);
        $parentUser = auth()->user();

        if (!$this->canManageSubUser($subUser)) {
            return redirect()->route('subusers.index')->with('error', 'You can only update your own sub-users.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'nullable|array', // For roles instead of permissions
            'roles.*' => 'exists:roles,id', // Ensure roles exist
            'businesses' => 'nullable|array',
            'businesses.*' => 'exists:businesses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('subusers.edit', $id)->withErrors($validator)->withInput();
        }

        // Update sub-user details
        $subUser->name = $request->name;
        $subUser->username = $request->username;
        $subUser->email = $request->email;
        $subUser->save();

        // Sync the roles and branches
        if ($request->has('roles')) {
            $roles = array_map('intval', $request->roles); // Convert to integers
            $subUser->roles()->sync($roles); // Sync roles instead of permissions
        }

        if ($request->has('businesses')) {
            $subUser->businesses()->sync($request->businesses);
        }

        return redirect()->route('subusers.index')->with('success', 'Sub User updated successfully');
    }

    /**
     * Suspend the specified sub-user.
     */
    public function suspend(Request $request, $id)
    {
        $subUser = User::findOrFail($id);
        $parentUser = auth()->user();
        
        if (!$this->canManageSubUser($subUser)) {
            return redirect()->route('subusers.index')->with('error', 'You can only suspend your own sub-users.');
        }
        
        // Prevent suspending super admins
        if ($subUser->isSuperAdmin()) {
            return redirect()->route('subusers.index')->with('error', 'Cannot suspend Super Admin users.');
        }
        
        $reason = $request->input('reason', 'No reason provided');
        $subUser->suspend($reason);
        
        return redirect()->route('subusers.index')->with('success', 'Sub-user suspended successfully.');
    }

    /**
     * Unsuspend the specified sub-user.
     */
    public function unsuspend($id)
    {
        $subUser = User::findOrFail($id);
        $parentUser = auth()->user();
        
        if (!$this->canManageSubUser($subUser)) {
            return redirect()->route('subusers.index')->with('error', 'You can only unsuspend your own sub-users.');
        }
        
        $subUser->unsuspend();
        
        return redirect()->route('subusers.index')->with('success', 'Sub-user unsuspended successfully.');
    }

    /**
     * Delete a sub-user.
     */
    public function destroy($id)
    {
        $subUser = User::findOrFail($id);
        $parentUser = auth()->user();
        
        if (!$this->canManageSubUser($subUser)) {
            return redirect()->route('subusers.index')->with('error', 'You can only delete your own sub-users.');
        }
        
        // Prevent deleting super admins
        if ($subUser->isSuperAdmin()) {
            return redirect()->route('subusers.index')->with('error', 'Cannot delete Super Admin users.');
        }
        
        $subUser->delete();
        return redirect()->route('subusers.index')->with('success', 'Sub-user deleted successfully');
    }

    protected function canManageSubUser(User $subUser): bool
    {
        $user = auth()->user();

        return $user->isSuperAdmin() || $subUser->parent_id === $user->id;
    }
}
