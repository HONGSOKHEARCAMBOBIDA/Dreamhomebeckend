<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
class RoleController extends Controller
{
    //
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id', // Ensure department_id exists in the departments table
        ]);

        // If validation fails, return error messages
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the role
        $role = Role::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
        ]);

        // Return the created role
        return response()->json(['data' => $role], 201);
    }
    public function index()
    {
        $role= Role::all();
        return response()->json($role);
        
    }
  public function getRoleByDepartment($departmentID=null)
  {
    if($departmentID)
    {
        $role= Role::where('department_id',$departmentID)->get();
       
    }
    else
    {
        $role=Role::all();
    }
    return response()->json($role);
  }
  public function update(Request $request, $id)
  {
      // Find the role by ID or fail with a 404 error
      $role = Role::findOrFail($id);
  
      // Validate the request data
      $validatedData = $request->validate([
          'name' => 'required|string|max:255',
          'department_id' => 'nullable|exists:departments,id',
      ]);
  
      // Update the role with validated data
      $role->update($validatedData);
  
      // Return the updated role as a JSON response
      return response()->json(['message'=>'updated']);
  }
  public function delete($id)
  {
    $role= Role::find($id);
    if($role)
    {
        $role->delete();
        return response()->json(['message'=>'Deleted'],200);
    }
    else
    {
        return response()->json(['message'=>'role not found'],404);
    }
  }
}
