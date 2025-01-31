<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Rolehaspermission;

class RolehaspermissionController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422); // 422 is the HTTP status code for Unprocessable Entity
        }

        // Get the validated data
        $validatedData = $validator->validated();

        // Create a new Rolehaspermission record
        $rolehaspermission = Rolehaspermission::create($validatedData);

        // Return the created record as a JSON response
        return response()->json($rolehaspermission, 201); // 201 is the HTTP status code for Created
    }
    public function index()
    {
        $rolehaspermission = Rolehaspermission::join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->select(
                'roles.name as role_name', // Alias to avoid conflict
                'permissions.name as permission_name' // Alias to avoid conflict
            )
            ->get();
    
        return response()->json($rolehaspermission);
    }
    public function update(Request $request, $role_id, $permission_id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|exists:permissions,id', // Validate the new permission_id
        ]);
    
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422); // 422 is the HTTP status code for Unprocessable Entity
        }
    
        // Get the new permission_id from the request
        $new_permission_id = $request->input('permission_id');
    
        // Check if the new permission_id is the same as the old one
        if ($new_permission_id == $permission_id) {
            return response()->json([
                'message' => 'The new permission_id is the same as the old one.',
            ], 400); // 400 is the HTTP status code for Bad Request
        }
    
        // Use a transaction to ensure data consistency
        DB::transaction(function () use ($role_id, $permission_id, $new_permission_id) {
            // Delete the existing record
            Rolehaspermission::where('role_id', $role_id)
                ->where('permission_id', $permission_id)
                ->delete();
    
            // Create a new record with the updated permission_id
            Rolehaspermission::create([
                'role_id' => $role_id,
                'permission_id' => $new_permission_id,
            ]);
        });
    
        // Return success response
        return response()->json([
            'message' => 'Permission updated successfully.',
        ], 200);
    }
    public function delete($role_id, $permission_id)
{
    DB::transaction(function () use ($role_id, $permission_id) {
        // Delete the existing record
        Rolehaspermission::where('role_id', $role_id)
            ->where('permission_id', $permission_id)
            ->delete();
    });
    return response()->json(['message'=>'deleted']);
}
}