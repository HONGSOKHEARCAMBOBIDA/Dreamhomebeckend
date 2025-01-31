<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Hash; // Import Hash for password hashing

class AuthController extends Controller
{
    use AuthorizesRequests;
    public function register(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'village_id' => 'required|exists:villages,id',
            'password' => 'required|string|min:6', // Add minimum password length
        ]);

        // Handle file upload
        $input = $request->all();
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/profile_image');
            $image->move($destinationPath, $name);
            $input['profile_image'] = $name;
        }

        // Hash the password before saving
        $input['password'] = Hash::make($request->password);

        // Create the user
        $user = User::create($input);

        // Return the created user as JSON response
        return response()->json($user, 201); // 201 status code for resource creation
    }
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Extract phone and password from the request
        $phone = $request->phone;
        $password = $request->password;

        // Attempt to authenticate the user
        if (Auth::attempt(['phone' => $phone, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken('salaitapp')->accessToken;

            // Return success response with user data and token
            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            // Return error response if authentication fails
            return response()->json([
                'message' => 'Invalid phone number or password',
            ], 401);
        }
    }
    public function me()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Fetch the authenticated user's details with joins
            $user = User::join('roles', 'roles.id', '=', 'users.role_id')
                ->join('villages', 'villages.id', '=', 'users.village_id')
                ->join('communces', 'communces.id', '=', 'villages.communce_id')
                ->join('districts', 'districts.id', '=', 'communces.district_id')
                ->join('provinces', 'provinces.id', '=', 'districts.province_id')
                ->select(
                    'users.id as user_id',
                    'users.name as user_name',
                    'users.phone',
                    'users.profile_image', // Use underscore instead of hyphen
                    'roles.name as role_name',
                    'provinces.name as province_name',
                    'districts.name as district_name',
                    'communces.name as communce_name',
                    'villages.name as village_name'
                )
                ->where('users.id', Auth::id()) // Fetch only the authenticated user's data
                ->first(); // Use `first()` instead of `get()` since we're fetching a single user

            // Return the user data as JSON
            return response()->json($user);
        } else {
            // Return an error response if the user is not authenticated
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }
    public function update(Request $request, $id = null)
    {
        // Fetch the user to be updated
        $user = $id ? User::findOrFail($id) : Auth::user();

        // Authorize the action (using Laravel policies)
        $this->authorize('update', $user);

        // Validate the request data
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:15',
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare the data to be updated
        $data = $request->except('profile_image');

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = uniqid() . '.' . $image->extension();
            $destinationPath = public_path('/profile_images');
            $image->move($destinationPath, $name);

            // Delete the old image if it exists
            if ($user->profile_image && file_exists(public_path('profile_images/' . $user->profile_image))) {
                unlink(public_path('profile_images/' . $user->profile_image));
            }

            // Add the new image name to the data array
            $data['profile_image'] = $name;
        }

        // Log the data to be updated
        \Log::info('Data to be updated:', $data);

        // Update the user with the new data
        $user->update($data);

        // Return the updated user as JSON
        return response()->json($user);
    }
    public function delete($id)
    {
        // Fetch the user to be deleted
        $user = User::findOrFail($id); // Corrected method name

        // Delete the user
        $user->delete(); // Use delete() instead of destroy()

        // Return a success response
        return response()->json(['message' => 'deleted']); // Corrected message
    }

}